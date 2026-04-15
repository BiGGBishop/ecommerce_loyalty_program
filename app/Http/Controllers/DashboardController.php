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
        $badgeInfo = $this->loyalty->getBadgeInfo($user);
        
        return Inertia::render('dashboard', [
            'loyaltyData' => [
                'unlocked_achievements' => $this->loyalty->getUserAchievements($user),
                'next_available_achievements' => $this->loyalty->getNextAchievements($user),
                'current_badge' => $badgeInfo['current_badge'],
                'next_badge' => $badgeInfo['next_badge'],
                'remaining_to_unlock_next_badge' => $badgeInfo['remaining'],
            ]
        ]);
    }
}