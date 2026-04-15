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
     * Create a single purchase (always successful for testing)
     */
    public function createPurchase(User $user, Product $product, int $quantity): Purchase
    {
        return DB::transaction(function () use ($user, $product, $quantity) {
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
            
            // Fire event for loyalty processing
            event(new PurchaseCompleted($user, $purchase));
            
            Log::info("Purchase created successfully", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'product' => $product->name,
                'quantity' => $quantity,
                'amount' => $totalAmount,
                'transaction_id' => $transactionId,
            ]);
            
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