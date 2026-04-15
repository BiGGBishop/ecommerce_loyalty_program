<?php

namespace App\Events;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    public Badge $badge;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Badge $badge, User $user)
    {
        $this->badge = $badge;
        $this->user = $user;
    }
}