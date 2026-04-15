<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create achievements
        $achievements = [
            [
                'name' => 'First Purchase',
                'key' => 'first_purchase',
                'required_purchases' => 1,
                'description' => 'Made your first purchase',
            ],
            [
                'name' => '5 Purchases',
                'key' => 'five_purchases',
                'required_purchases' => 5,
                'description' => 'Completed 5 purchases',
            ],
            [
                'name' => '10 Purchases',
                'key' => 'ten_purchases',
                'required_purchases' => 10,
                'description' => 'Completed 10 purchases',
            ],
            [
                'name' => '25 Purchases',
                'key' => 'twenty_five_purchases',
                'required_purchases' => 25,
                'description' => 'Completed 25 purchases',
            ],
            [
                'name' => '50 Purchases',
                'key' => 'fifty_purchases',
                'required_purchases' => 50,
                'description' => 'Completed 50 purchases',
            ],
            [
                'name' => '100 Purchases',
                'key' => 'one_hundred_purchases',
                'required_purchases' => 100,
                'description' => 'Completed 100 purchases - Ultimate collector!',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }

        // Create badges
        $badges = [
            [
                'name' => 'Bronze',
                'key' => 'bronze',
                'required_achievements' => 0,
                'cashback_amount' => 300,
                'description' => 'Welcome to the loyalty program!',
            ],
            [
                'name' => 'Silver',
                'key' => 'silver',
                'required_achievements' => 1,
                'cashback_amount' => 500,
                'description' => 'You\'re getting the hang of it!',
            ],
            [
                'name' => 'Gold',
                'key' => 'gold',
                'required_achievements' => 3,
                'cashback_amount' => 1000,
                'description' => 'Top tier customer!',
            ],
            [
                'name' => 'Platinum',
                'key' => 'platinum',
                'required_achievements' => 5,
                'cashback_amount' => 2000,
                'description' => 'Elite status achieved!',
            ],
            [
                'name' => 'Diamond',
                'key' => 'diamond',
                'required_achievements' => 8,
                'cashback_amount' => 5000,
                'description' => 'Legendary customer!',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['key' => $badge['key']],
                $badge
            );
        }
    }
}