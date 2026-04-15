<?php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private LoyaltyService $loyalty
    ) {}
    
    public function __invoke(): Response
    {
        $user = auth()->user();
        
        return Inertia::render('dashboard', [
            'loyaltyData' => $this->loyalty->getAllLoyaltyData($user),
        ]);
    }
}