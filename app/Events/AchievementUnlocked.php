<?php

namespace App\Events;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public Achievement $achievement;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Achievement $achievement, User $user)
    {
        $this->achievement = $achievement;
        $this->user = $user;
    }
}