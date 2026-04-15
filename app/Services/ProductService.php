<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Get all products with optional filtering
     */
    public function getAllProducts(array $filters = []): Collection
    {
        $query = Product::query();
        
        // Filter by in-stock only
        if (!empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }
        
        // Filter by price range
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        // Search by name
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        
        // Order by
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        $products = $query->get();
        
        Log::info('Products fetched', ['count' => $products->count()]);
        
        return $products;
    }
    
    /**
     * Get single product by ID
     */
    public function getProduct(string $productId): ?Product
    {
        return Product::find($productId);
    }
    
    /**
     * Get popular products based on purchase count
     */
    public function getPopularProducts(int $limit = 5): Collection
    {
        return Product::withCount('purchases')
            ->orderBy('purchases_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get products low in stock
     */
    public function getLowStockProducts(int $threshold = 10): Collection
    {
        return Product::where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();
    }
    
    /**
     * Check if product is available
     */
    public function isProductAvailable(Product $product, int $quantity = 1): bool
    {
        return $product->stock >= $quantity;
    }
    
    /**
     * Update product stock
     */
    public function updateStock(Product $product, int $quantity, string $operation = 'decrease'): bool
    {
        try {
            if ($operation === 'decrease') {
                $product->decrement('stock', $quantity);
            } else {
                $product->increment('stock', $quantity);
            }
            
            Log::info("Product stock updated", [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'operation' => $operation,
                'quantity' => $quantity,
                'new_stock' => $product->fresh()->stock,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update product stock", [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create([
            'id' => (string) Str::uuid(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
        ]);
        
        Log::info("New product created", [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);
        
        return $product;
    }
    
    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, array $data): bool
    {
        try {
            $product->update($data);
            
            Log::info("Product updated", [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'changes' => array_keys($data),
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update product", [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Delete a product
     */
    public function deleteProduct(Product $product): bool
    {
        try {
            $productName = $product->name;
            $product->delete();
            
            Log::info("Product deleted", [
                'product_id' => $product->id,
                'product_name' => $productName,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete product", [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Get product statistics
     */
    public function getProductStats(): array
    {
        return [
            'total_products' => Product::count(),
            'total_value' => Product::sum('price'),
            'average_price' => Product::avg('price'),
            'low_stock_count' => Product::where('stock', '<=', 10)->count(),
            'out_of_stock_count' => Product::where('stock', 0)->count(),
            'most_expensive' => Product::orderBy('price', 'desc')->first(),
            'cheapest' => Product::orderBy('price', 'asc')->first(),
        ];
    }
    
    /**
     * Get product sales data
     */
    public function getProductSalesData(Product $product): array
    {
        $purchases = $product->purchases()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return [
            'product' => $product,
            'total_sold' => $purchases->count(),
            'total_revenue' => $purchases->sum('amount'),
            'average_order_value' => $purchases->avg('amount'),
            'last_purchase' => $purchases->first(),
            'purchases' => $purchases,
        ];
    }
    
    /**
     * Search products by name or description
     */
    public function searchProducts(string $searchTerm): Collection
    {
        return Product::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('description', 'like', "%{$searchTerm}%")
            ->orderBy('name')
            ->get();
    }
    
    /**
     * Get products by price range
     */
    public function getProductsByPriceRange(float $min, float $max): Collection
    {
        return Product::whereBetween('price', [$min, $max])
            ->orderBy('price', 'asc')
            ->get();
    }
}