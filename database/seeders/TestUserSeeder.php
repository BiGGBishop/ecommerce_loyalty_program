<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        // User 1: New user with no purchases (No badges)
        $user1 = User::updateOrCreate(
            ['email' => 'new@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // User 2: Beginner with 2 purchases (1 achievement) - Bronze badge only
        $user2 = User::updateOrCreate(
            ['email' => 'beginner@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Beginner User',
                'email' => 'beginner@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // Add 2 purchases for user2
        for ($i = 1; $i <= 2; $i++) {
            Purchase::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user2->id,
                'amount' => rand(1000, 50000),
                'product_name' => 'Product ' . $i,
                'transaction_id' => 'TXN_' . Str::random(10),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
        
        // Unlock first achievement for user2
        $firstAchievement = Achievement::where('required_purchases', 1)->first();
        if ($firstAchievement) {
            $user2->achievements()->attach($firstAchievement);
        }
        
        // Unlock Bronze badge for user2 (0 achievements required - automatic)
        $bronzeBadge = Badge::where('key', 'bronze')->first();
        if ($bronzeBadge) {
            $user2->badges()->attach($bronzeBadge);
        }
        
        // User 3: Regular with 7 purchases (2 achievements) - Bronze + Silver badges
        $user3 = User::updateOrCreate(
            ['email' => 'regular@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Regular Customer',
                'email' => 'regular@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // Add 7 purchases for user3
        for ($i = 1; $i <= 7; $i++) {
            Purchase::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user3->id,
                'amount' => rand(1000, 50000),
                'product_name' => 'Product ' . $i,
                'transaction_id' => 'TXN_' . Str::random(10),
                'created_at' => now()->subDays(rand(1, 60)),
            ]);
        }
        
        // Unlock achievements for user3 (First Purchase and 5 Purchases)
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 7)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user3->achievements()->attach($achievement);
        }
        
        // Unlock Bronze + Silver badges for user3
        $bronzeBadge = Badge::where('key', 'bronze')->first();
        $silverBadge = Badge::where('key', 'silver')->first();
        
        if ($bronzeBadge) {
            $user3->badges()->attach($bronzeBadge);
        }
        if ($silverBadge) {
            $user3->badges()->attach($silverBadge);
        }
        
        // User 4: Pro with 12 purchases (3 achievements) - Bronze + Silver + Gold badges
        $user4 = User::updateOrCreate(
            ['email' => 'pro@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Pro Shopper',
                'email' => 'pro@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // Add 12 purchases for user4
        for ($i = 1; $i <= 12; $i++) {
            Purchase::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user4->id,
                'amount' => rand(1000, 50000),
                'product_name' => 'Product ' . $i,
                'transaction_id' => 'TXN_' . Str::random(10),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
        
        // Unlock achievements for user4 (First Purchase, 5 Purchases, 10 Purchases)
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 12)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user4->achievements()->attach($achievement);
        }
        
        // Unlock Bronze + Silver + Gold badges for user4
        $bronzeBadge = Badge::where('key', 'bronze')->first();
        $silverBadge = Badge::where('key', 'silver')->first();
        $goldBadge = Badge::where('key', 'gold')->first();
        
        if ($bronzeBadge) {
            $user4->badges()->attach($bronzeBadge);
        }
        if ($silverBadge) {
            $user4->badges()->attach($silverBadge);
        }
        if ($goldBadge) {
            $user4->badges()->attach($goldBadge);
        }
        
        // User 5: VIP with 30 purchases (4 achievements) - Bronze + Silver + Gold + Platinum badges
        $user5 = User::updateOrCreate(
            ['email' => 'vip@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'VIP Customer',
                'email' => 'vip@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // Add 30 purchases for user5
        for ($i = 1; $i <= 30; $i++) {
            Purchase::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user5->id,
                'amount' => rand(1000, 50000),
                'product_name' => 'Product ' . $i,
                'transaction_id' => 'TXN_' . Str::random(10),
                'created_at' => now()->subDays(rand(1, 120)),
            ]);
        }
        
        // Unlock achievements for user5 (First Purchase, 5, 10, 25 Purchases)
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 30)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user5->achievements()->attach($achievement);
        }
        
        // Unlock Bronze + Silver + Gold + Platinum badges for user5
        $bronzeBadge = Badge::where('key', 'bronze')->first();
        $silverBadge = Badge::where('key', 'silver')->first();
        $goldBadge = Badge::where('key', 'gold')->first();
        $platinumBadge = Badge::where('key', 'platinum')->first();
        
        if ($bronzeBadge) {
            $user5->badges()->attach($bronzeBadge);
        }
        if ($silverBadge) {
            $user5->badges()->attach($silverBadge);
        }
        if ($goldBadge) {
            $user5->badges()->attach($goldBadge);
        }
        if ($platinumBadge) {
            $user5->badges()->attach($platinumBadge);
        }
    }
}