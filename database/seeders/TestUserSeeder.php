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
        // User 1: New user with no purchases
        $user1 = User::updateOrCreate(
            ['email' => 'new@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => Hash::make('password'),
            ]
        );
        
        // User 2: Beginner with 2 purchases (1 achievement)
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
        
        // User 3: Regular with 7 purchases (2 achievements)
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
        
        // Unlock achievements for user3
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 7)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user3->achievements()->attach($achievement);
        }
        
        // Unlock Silver badge for user3
        $silverBadge = Badge::where('key', 'silver')->first();
        if ($silverBadge) {
            $user3->badges()->attach($silverBadge);
        }
        
        // User 4: Pro with 12 purchases (3 achievements)
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
        
        // Unlock achievements for user4
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 12)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user4->achievements()->attach($achievement);
        }
        
        // Unlock Gold badge for user4
        $goldBadge = Badge::where('key', 'gold')->first();
        if ($goldBadge) {
            $user4->badges()->attach($goldBadge);
        }
        
        // User 5: VIP with 30 purchases (4 achievements)
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
        
        // Unlock achievements for user5
        $achievementsToUnlock = Achievement::where('required_purchases', '<=', 30)->get();
        foreach ($achievementsToUnlock as $achievement) {
            $user5->achievements()->attach($achievement);
        }
        
        // Unlock Platinum badge for user5
        $platinumBadge = Badge::where('key', 'platinum')->first();
        if ($platinumBadge) {
            $user5->badges()->attach($platinumBadge);
        }
    }
}