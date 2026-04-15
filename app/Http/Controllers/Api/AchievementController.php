<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;

class AchievementController extends Controller
{
    public function __construct(
        private LoyaltyService $loyalty
    ) {}

    /**
     * Get currency user
     */
    public function currentUser(): JsonResponse
    {
        return $this->show(auth()->user());
    }
    
    /**
     * Get achievements and badge information for a specific user
     */
    public function show(User $user): JsonResponse
    {
        $badgeInfo = $this->loyalty->getBadgeInfo($user);
        
        return response()->json([
            'unlocked_achievements' => $this->loyalty->getUserAchievements($user),
            'next_available_achievements' => $this->loyalty->getNextAchievements($user),
            'current_badge' => $badgeInfo['current_badge'],
            'next_badge' => $badgeInfo['next_badge'],
            'remaining_to_unlock_next_badge' => $badgeInfo['remaining'],
        ]);
    }
    
    /**
     * Get complete loyalty data including cashback
     */
    public function fullDetails(User $user): JsonResponse
    {
        return response()->json([
            'loyalty_data' => $this->loyalty->getAllLoyaltyData($user),
        ]);
    }
}