<?php

namespace App\Events;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseCompleted
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Purchase $purchase;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Purchase $purchase)
    {
        $this->user = $user;
        $this->purchase = $purchase;
    }
}