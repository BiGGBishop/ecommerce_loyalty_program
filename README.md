# Bumpa Loyalty Program

An e-commerce loyalty program system that rewards customers with achievements, badges, and cashback based on their purchase history.

## Built With

- **Backend**: Laravel 11, PHP 8.2+, SQLite
- **Frontend**: React 18, Inertia.js, Tailwind CSS, TypeScript
- **Authentication**: Laravel Fortify, Laravel Sanctum
- **Icons**: Lucide React

## Features

- 🏆 Achievement system based on purchase milestones (1, 5, 10, 25, 50, 100 purchases)
- 🥇 Badge system based on achievements earned (Bronze, Silver, Gold, Platinum, Diamond)
- 💰 Automatic cashback rewards for badge unlocks (₦300 - ₦5,000)
- 📊 Real-time progress tracking dashboard
- 🔒 UUID primary keys for security
- 🌙 Dark mode support
- 📱 Fully responsive design
- 🔐 API endpoints with Sanctum authentication

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM or Yarn
- SQLite (or MySQL/PostgreSQL)

## Installation

```bash
### 1. Clone the Repository

git clone https://github.com/yourusername/bumpa-loyalty-program.git
cd bumpa-loyalty-program
2. Install Backend Dependencies
bash
composer install
3. Environment Setup
bash
cp .env.example .env
Update your .env file:

env
DB_CONNECTION=sqlite
# Create empty database file:
touch database/database.sqlite

# Or use MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=loyalty
# DB_USERNAME=root
# DB_PASSWORD=
4. Generate Application Key
bash
php artisan key:generate
5. Install Laravel Sanctum
bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
6. Run Migrations
bash
php artisan migrate
7. Seed Database
bash
php artisan db:seed
This creates:

6 achievements

5 badges

5 test users with different progress levels

8. Install Frontend Dependencies
bash
npm install
9. Build Assets
bash
npm run build
10. Start Development Servers
bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite development server (for hot reloading)
npm run dev
Visit http://localhost:8000 to access the application.

Test Users
The seeder creates the following test users (password: password for all):

Email	Name	Badges	Total Cashback
new@example.com	New User	None	₦0
beginner@example.com	Beginner User	Bronze	₦300
regular@example.com	Regular Customer	Bronze, Silver	₦800
pro@example.com	Pro Shopper	Bronze, Silver, Gold	₦1,800
vip@example.com	VIP Customer	Bronze, Silver, Gold, Platinum	₦3,800
API Endpoints
Get User Achievements
http
GET /api/v1/users/{user}/achievements
Authorization: Bearer {token}
Response:

json
{
    "unlocked_achievements": ["First Purchase", "5 Purchases", "10 Purchases"],
    "next_available_achievements": ["25 Purchases", "50 Purchases", "100 Purchases"],
    "current_badge": "Gold",
    "next_badge": "Platinum",
    "remaining_to_unlock_next_badge": 2
}
Generate API Token
bash
php artisan tinker

$user = App\Models\User::first();
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
exit;
Test API with cURL
bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/v1/users/USER_UUID/achievements
Project Structure
text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── AchievementController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Achievement.php
│   │   ├── Badge.php
│   │   └── Purchase.php
│   ├── Events/
│   │   ├── AchievementUnlocked.php
│   │   ├── BadgeUnlocked.php
│   │   └── PurchaseCompleted.php
│   ├── Listeners/
│   │   └── HandleLoyaltyRewards.php
│   └── Services/
│       └── LoyaltyService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── LoyaltySeeder.php
│       └── TestUserSeeder.php
├── resources/
│   └── js/
│       ├── pages/
│       │   └── dashboard.tsx
│       └── components/
├── routes/
│   ├── web.php
│   └── api.php
└── storage/
    └── logs/
        └── cashback.log
Cashback Structure
| Badge | Required

generate all the readme as a markdown
Here's the complete README.md content. Copy this and save as README.md:

markdown
# Bumpa Loyalty Program

An e-commerce loyalty program system that rewards customers with achievements, badges, and cashback based on their purchase history.

## Built With

- **Backend**: Laravel 11, PHP 8.2+, SQLite
- **Frontend**: React 18, Inertia.js, Tailwind CSS, TypeScript
- **Authentication**: Laravel Fortify, Laravel Sanctum
- **Icons**: Lucide React

## Features

- 🏆 Achievement system based on purchase milestones (1, 5, 10, 25, 50, 100 purchases)
- 🥇 Badge system based on achievements earned (Bronze, Silver, Gold, Platinum, Diamond)
- 💰 Automatic cashback rewards for badge unlocks (₦300 - ₦5,000)
- 📊 Real-time progress tracking dashboard
- 🔒 UUID primary keys for security
- 🌙 Dark mode support
- 📱 Fully responsive design
- 🔐 API endpoints with Sanctum authentication

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM or Yarn
- SQLite (or MySQL/PostgreSQL)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/bumpa-loyalty-program.git
cd bumpa-loyalty-program
2. Install Backend Dependencies
bash
composer install
3. Environment Setup
bash
cp .env.example .env
Update your .env file:

env
DB_CONNECTION=sqlite
# Create empty database file:
touch database/database.sqlite

# Or use MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=loyalty
# DB_USERNAME=root
# DB_PASSWORD=
4. Generate Application Key
bash
php artisan key:generate
5. Install Laravel Sanctum
bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
6. Run Migrations
bash
php artisan migrate
7. Seed Database
bash
php artisan db:seed
This creates:

6 achievements

5 badges

5 test users with different progress levels

8. Install Frontend Dependencies
bash
npm install
9. Build Assets
bash
npm run build
10. Start Development Servers
bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite development server (for hot reloading)
npm run dev
Visit http://localhost:8000 to access the application.

Test Users
The seeder creates the following test users (password: password for all):

Email	Name	Badges	Total Cashback
new@example.com	New User	None	₦0
beginner@example.com	Beginner User	Bronze	₦300
regular@example.com	Regular Customer	Bronze, Silver	₦800
pro@example.com	Pro Shopper	Bronze, Silver, Gold	₦1,800
vip@example.com	VIP Customer	Bronze, Silver, Gold, Platinum	₦3,800
API Endpoints
Get User Achievements
http
GET /api/v1/users/{user}/achievements
Authorization: Bearer {token}
Response:

json
{
    "unlocked_achievements": ["First Purchase", "5 Purchases", "10 Purchases"],
    "next_available_achievements": ["25 Purchases", "50 Purchases", "100 Purchases"],
    "current_badge": "Gold",
    "next_badge": "Platinum",
    "remaining_to_unlock_next_badge": 2
}
Generate API Token
bash
php artisan tinker

$user = App\Models\User::first();
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
exit;
Test API with cURL
bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/v1/users/USER_UUID/achievements
Project Structure
text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── AchievementController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Achievement.php
│   │   ├── Badge.php
│   │   └── Purchase.php
│   ├── Events/
│   │   ├── AchievementUnlocked.php
│   │   ├── BadgeUnlocked.php
│   │   └── PurchaseCompleted.php
│   ├── Listeners/
│   │   └── HandleLoyaltyRewards.php
│   └── Services/
│       └── LoyaltyService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── LoyaltySeeder.php
│       └── TestUserSeeder.php
├── resources/
│   └── js/
│       ├── pages/
│       │   └── dashboard.tsx
│       └── components/
├── routes/
│   ├── web.php
│   └── api.php
└── storage/
    └── logs/
        └── cashback.log
Cashback Structure
Badge	Required Achievements	Cashback Amount
Bronze	0	₦300
Silver	1	₦500
Gold	3	₦1,000
Platinum	5	₦2,000
Diamond	8	₦5,000
Achievement Structure
Achievement	Purchases Required
First Purchase	1
5 Purchases	5
10 Purchases	10
25 Purchases	25
50 Purchases	50
100 Purchases	100
How It Works
Achievement System
User makes a purchase

PurchaseCompleted event is fired

HandleLoyaltyRewards listener processes the purchase

System checks if user has reached new purchase milestones

New achievements are unlocked and AchievementUnlocked event fired

Badge System
After achievements are updated, system checks badge eligibility

Badges are awarded based on total achievement count

BadgeUnlocked event is fired when a new badge is earned

Cashback is automatically processed for each new badge

Cashback System
Cashback is logged to storage/logs/cashback.log

Each badge provides one-time cashback

Cashback is cumulative (users get cashback for every badge earned)

Ready for payment gateway integration (Paystack, Flutterwave, etc.)

Dashboard Features
Current badge display with icon

Progress bar showing percentage to next badge

List of unlocked achievements (green highlight)

List of next available achievements (locked state)

Total cashback earned

Cashback history with dates and badge names

Testing the Loyalty System
Simulate a Purchase
bash
php artisan tinker

$user = App\Models\User::where('email', 'beginner@example.com')->first();
$purchase = $user->purchases()->create([
    'id' => (string) Str::uuid(),
    'amount' => 5000,
    'product_name' => 'Test Product',
    'transaction_id' => 'TXN_' . Str::random(10),
]);

event(new App\Events\PurchaseCompleted($user, $purchase));
exit;
Check Cashback Logs
bash
cat storage/logs/cashback.log
Check User Progress
bash
php artisan tinker

$user = App\Models\User::where('email', 'pro@example.com')->first();
echo "Achievements: " . $user->achievements()->count() . "\n";
echo "Badges: " . $user->badges()->count() . "\n";
echo "Total Cashback: ₦" . $user->badges()->sum('cashback_amount') . "\n";
exit;
