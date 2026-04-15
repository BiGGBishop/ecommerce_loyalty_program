<?php

namespace App\Listeners;

use App\Events\PurchaseCompleted;
use App\Services\LoyaltyService;

class HandleLoyaltyRewards
{
    protected LoyaltyService $loyaltyService;

    /**
     * Create the event listener.
     */
    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseCompleted $event): void
    {
        $this->loyaltyService->handlePurchase($event->user);
    }
}