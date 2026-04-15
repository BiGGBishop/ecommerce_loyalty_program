<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * Handle purchase event and unlock achievements/badges
     */
    public function handlePurchase(User $user): void
    {
        DB::transaction(function () use ($user) {
            $oldAchievementCount = $user->achievements()->count();
            $totalPurchases = $user->purchases()->count();
            
            // Check and unlock new achievements
            $this->unlockAchievements($user, $totalPurchases);
            
            // Check and unlock new badges
            $this->unlockBadges($user, $oldAchievementCount);
        });
    }
    
    /**
     * Unlock achievements based on purchase count
     */
    private function unlockAchievements(User $user, int $purchaseCount): void
    {
        $achievements = Achievement::where('required_purchases', '<=', $purchaseCount)
            ->whereNotIn('id', $user->achievements()->pluck('achievements.id'))
            ->get();
            
        foreach ($achievements as $achievement) {
            $user->achievements()->attach($achievement);
            event(new AchievementUnlocked($achievement, $user));
            
            Log::info("User {$user->id} unlocked achievement: {$achievement->name}");
        }
    }
    
    /**
     * Unlock badges based on achievement count
     */
    private function unlockBadges(User $user, int $previousCount): void
    {
        $currentCount = $user->achievements()->count();
        $currentBadge = $user->badges()
            ->orderBy('required_achievements', 'desc')
            ->first();
            
        $newBadge = Badge::where('required_achievements', '<=', $currentCount)
            ->where('required_achievements', '>', $currentBadge?->required_achievements ?? -1)
            ->orderBy('required_achievements', 'desc')
            ->first();
            
        if ($newBadge) {
            $user->badges()->attach($newBadge);
            event(new BadgeUnlocked($newBadge, $user));
            
            // Process cashback reward
            $this->processCashback($user, $newBadge->cashback_amount);
            
            Log::info("User {$user->id} earned badge: {$newBadge->name} with ₦{$newBadge->cashback_amount} cashback");
        }
    }
    
    /**
     * Process cashback payment
     */
    private function processCashback(User $user, int $amount): void
    {
        // Let us log cashback for now, this can be replace with actual payment gateway
        Log::channel('cashback')->info('Cashback payment processed', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'amount' => $amount,
            'currency' => 'NGN',
            'reference' => 'CASHBACK_' . time() . '_' . $user->id,
        ]);
    }
    
    /**
     * Get list of unlocked achievements for a user
     */
    public function getUserAchievements(User $user): array
    {
        return $user->achievements()
            ->orderBy('required_purchases')
            ->pluck('name')
            ->toArray();
    }
    
    /**
     * Get next available achievements for a user
     */
    public function getNextAchievements(User $user): array
    {
        $currentPurchases = $user->purchases()->count();
        $unlockedIds = $user->achievements()->pluck('achievements.id');
        
        return Achievement::where('required_purchases', '>', $currentPurchases)
            ->whereNotIn('id', $unlockedIds)
            ->orderBy('required_purchases')
            ->limit(3)
            ->pluck('name')
            ->toArray();
    }
    
    /**
     * Get badge information for a user
     */
    public function getBadgeInfo(User $user): array
    {
        $currentBadge = $user->badges()
            ->orderBy('required_achievements', 'desc')
            ->first();
            
        $nextBadge = Badge::where('required_achievements', '>', $user->achievements()->count())
            ->orderBy('required_achievements')
            ->first();
            
        $achievementCount = $user->achievements()->count();
        
        return [
            'current_badge' => $currentBadge?->name ?? 'Newbie',
            'next_badge' => $nextBadge?->name ?? 'Legend',
            'remaining' => $nextBadge 
                ? max(0, $nextBadge->required_achievements - $achievementCount)
                : 0,
        ];
    }
    
    /**
     * Get all loyalty data in one call (convenience method)
     */
    public function getAllLoyaltyData(User $user): array
    {
        $badgeInfo = $this->getBadgeInfo($user);
        
        return [
            'unlocked_achievements' => $this->getUserAchievements($user),
            'next_available_achievements' => $this->getNextAchievements($user),
            'current_badge' => $badgeInfo['current_badge'],
            'next_badge' => $badgeInfo['next_badge'],
            'remaining_to_unlock_next_badge' => $badgeInfo['remaining'],
        ];
    }
}