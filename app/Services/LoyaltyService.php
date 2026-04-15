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
            $totalPurchases = $user->purchases()->count();
            $newAchievements = $this->unlockAchievements($user, $totalPurchases);
            $this->unlockBadges($user);
        });
    }
    
    /**
     * Unlock achievements based on purchase count
     */
    private function unlockAchievements(User $user, int $purchaseCount): array
    {
        $achievements = Achievement::where('required_purchases', '<=', $purchaseCount)
            ->whereNotIn('id', $user->achievements()->pluck('achievements.id'))
            ->get();
            
        foreach ($achievements as $achievement) {
            $user->achievements()->attach($achievement);
            event(new AchievementUnlocked($achievement, $user));
        }
        
        return $achievements->toArray();
    }
    
    /**
     * Unlock badges based on achievement count
     */
    private function unlockBadges(User $user): void
    {
        $currentAchievementCount = $user->achievements()->count();
        
        // Find all badges that should be unlocked based on current achievement count
        $badgesToUnlock = Badge::where('required_achievements', '<=', $currentAchievementCount)
            ->whereNotIn('id', $user->badges()->pluck('badges.id'))
            ->orderBy('required_achievements', 'asc')
            ->get();
            
        foreach ($badgesToUnlock as $badge) {
            $user->badges()->attach($badge);
            event(new BadgeUnlocked($badge, $user));
            
            $this->processCashback($user, $badge);
        }
    }
    
    /**
     * Process cashback payment
     */
    private function processCashback(User $user, Badge $badge): void
    {
        // Let's log cashback for now we'll replace with actual payment gateway
        Log::channel('cashback')->info('Cashback payment processed', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'badge' => $badge->name,
            'amount' => $badge->cashback_amount,
            'currency' => 'NGN',
            'reference' => 'CASHBACK_' . time() . '_' . $user->id . '_' . $badge->id,
        ]);
    }
    
    /**
     * Get list of unlocked achievements for a user
     */
    public function getUserAchievements(User $user): array
    {
        return $user->achievements()
            ->orderBy('required_purchases', 'asc')
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
        
        $nextAchievements = Achievement::where('required_purchases', '>', $currentPurchases)
            ->whereNotIn('id', $unlockedIds)
            ->orderBy('required_purchases', 'asc')
            ->limit(3)
            ->pluck('name')
            ->toArray();
        
        // If no next achievements found, return empty array
        return $nextAchievements;
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
            ->orderBy('required_achievements', 'asc')
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
     * Get total cashback from all badges earned
     */
    public function getTotalCashback(User $user): int
    {
        return $user->badges()->sum('cashback_amount');
    }

    /**
     * Get last cashback earned
     */
    public function getLastCashback(User $user): ?array
    {
        $lastBadge = $user->badges()
            ->orderBy('user_badges.created_at', 'desc')
            ->first();
            
        if ($lastBadge && $lastBadge->cashback_amount > 0) {
            return [
                'amount' => $lastBadge->cashback_amount,
                'badge' => $lastBadge->name,
                'date' => $lastBadge->pivot->created_at->format('M d, Y'),
            ];
        }
        
        return null;
    }    

    /**
     * Get all badges with their cashback amounts
     */
    public function getBadgesWithCashback(User $user): array
    {
        $badges = $user->badges()
            ->orderBy('required_achievements', 'asc')
            ->get();
        
        if ($badges->isEmpty()) {
            return [];
        }
        
        return $badges->map(function ($badge) {
            return [
                'name' => $badge->name,
                'cashback' => $badge->cashback_amount,
                'earned_at' => $badge->pivot->created_at->format('M d, Y'),
            ];
        })->toArray();
    }
    
    /**
     * Get all loyalty data in one call
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
            'total_cashback' => $this->getTotalCashback($user),
            'last_cashback' => $this->getLastCashback($user),
            'all_badges' => $this->getBadgesWithCashback($user),
        ];
    }
}