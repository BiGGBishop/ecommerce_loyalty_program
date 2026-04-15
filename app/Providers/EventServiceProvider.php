<?php

namespace App\Providers;

use App\Events\PurchaseCompleted;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\HandleLoyaltyRewards;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PurchaseCompleted::class => [
            HandleLoyaltyRewards::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        
        // Register event listeners for logging using Event facade
        \Illuminate\Support\Facades\Event::listen(AchievementUnlocked::class, function ($event) {
            Log::info("Achievement unlocked: {$event->achievement->name} for user {$event->user->email}");
        });
        
        \Illuminate\Support\Facades\Event::listen(BadgeUnlocked::class, function ($event) {
            Log::info("Badge unlocked: {$event->badge->name} with ₦{$event->badge->cashback_amount} cashback for user {$event->user->email}");
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}