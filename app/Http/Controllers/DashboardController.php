<?php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private LoyaltyService $loyalty,
        private PurchaseService $purchaseService,
        private ProductService $productService
    ) {}
    
    public function __invoke(): Response
    {
        $user = auth()->user();
        
        // Get all loyalty data
        $loyaltyData = $this->loyalty->getAllLoyaltyData($user);

        // Get purchase stats
        $purchaseStats = [
            'total_purchases' => $this->purchaseService->getUserPurchaseCount($user),
            'total_spent' => $this->purchaseService->getUserTotalSpent($user),
        ];
        
        //All Products for the dashboard
        $allProducts = $this->productService->getAllProducts();
        
        return Inertia::render('dashboard', [
            'loyaltyData' => $loyaltyData,
            'purchaseStats' => $purchaseStats,
            'allProducts' => $allProducts, 
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'member_since' => $user->created_at->format('M Y'),
            ],
        ]);
    }
}