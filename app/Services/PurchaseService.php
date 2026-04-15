<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Events\PurchaseCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PurchaseService
{
    /**
     * Create a single purchase
     */
    public function createPurchase(User $user, Product $product, int $quantity): Purchase
    {
        return DB::transaction(function () use ($user, $product, $quantity) {
            // Check stock
            if (!$this->checkStock($product, $quantity)) {
                throw new \Exception("Insufficient stock for product: {$product->name}");
            }
            
            // Calculate total amount
            $totalAmount = $this->calculateTotalAmount($product, $quantity);
            
            // Generate unique transaction ID
            $transactionId = $this->generateTransactionId();
            
            // Create purchase record
            $purchase = Purchase::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $totalAmount,
                'transaction_id' => $transactionId,
            ]);
            
            // Update product stock
            $this->updateStock($product, $quantity);
            
            // Fire event for loyalty processing
            event(new PurchaseCompleted($user, $purchase));
            
            return $purchase;
        });
    }
    
    /**
     * Get user purchase history
     */
    public function getUserPurchaseHistory(User $user): array
    {
        return $user->purchases()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->id,
                    'product_name' => $purchase->product->name,
                    'amount' => $purchase->amount,
                    'transaction_id' => $purchase->transaction_id,
                    'date' => $purchase->created_at->format('M d, Y'),
                ];
            })
            ->toArray();
    }
    
    /**
     * Get user total spent
     */
    public function getUserTotalSpent(User $user): float
    {
        return $user->purchases()->sum('amount');
    }
    
    /**
     * Get user purchase count
     */
    public function getUserPurchaseCount(User $user): int
    {
        return $user->purchases()->count();
    }
    
    /**
     * Check if product has sufficient stock
     */
    private function checkStock(Product $product, int $quantity): bool
    {
        return $product->stock >= $quantity;
    }
    
    /**
     * Update product stock after purchase
     */
    private function updateStock(Product $product, int $quantity): void
    {
        $product->decrement('stock', $quantity);
    }
    
    /**
     * Calculate total amount for purchase
     */
    private function calculateTotalAmount(Product $product, int $quantity): float
    {
        return $product->price * $quantity;
    }
    
    /**
     * Generate unique transaction ID
     */
    private function generateTransactionId(): string
    {
        return 'TXN_' . time() . '_' . Str::random(10);
    }
}