<?php

// app/Http/Controllers/PurchaseController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PurchaseService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseService $purchaseService,
        private LoyaltyService $loyaltyService
    ) {}
    
    /**
     * Make a single purchase
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);
        
        try {
            $user = auth()->user();
            $product = Product::findOrFail($request->product_id);
            
            $purchase = $this->purchaseService->createPurchase($user, $product, $request->quantity);
            
            // Get updated loyalty data after purchase
            $loyaltyData = $this->loyaltyService->getAllLoyaltyData($user);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase completed successfully!',
                    'purchase' => $purchase,
                    'loyalty_data' => $loyaltyData,
                ]);
            }
            
            return redirect()->back()->with('success', 'Purchase completed successfully!');
            
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Get user purchase history
     */
    public function history(): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'purchases' => $this->purchaseService->getUserPurchaseHistory($user),
            'total_spent' => $this->purchaseService->getUserTotalSpent($user),
            'total_purchases' => $this->purchaseService->getUserPurchaseCount($user),
        ]);
    }
}