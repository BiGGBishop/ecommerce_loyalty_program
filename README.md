# Bumpa Loyalty Program

An e-commerce loyalty program system that rewards customers with achievements, badges, and cashback based on their purchase history.

## Built With

- **Backend**: Laravel 13, PHP 8.3+, SQLite
- **Frontend**: React 19, Inertia.js, Tailwind CSS, TypeScript
- **Authentication**: Laravel Fortify, Laravel Sanctum
- **Icons**: Lucide React

## Features

- Achievement system based on purchase milestones (1, 5, 10, 25, 50, 100 purchases)
- Badge system based on achievements earned (Bronze, Silver, Gold, Platinum, Diamond)
- Automatic cashback rewards for badge unlocks (₦300 - ₦5,000)
- Real-time progress tracking dashboard
- UUID primary keys for security
- Dark mode support
- Fully responsive design
- API endpoints with Sanctum authentication

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM or Yarn
- SQLite (or MySQL/PostgreSQL)

## Installation

### 1. Clone the Repository
```
git clone https://github.com/BiGGBishop/ecommerce_loyalty_program.git
cd ecommerce_loyalty_program
```
2. Install Backend Dependencies
```
composer install
```
3. Environment Setup
```
cp .env.example .env
```
Update your .env file:

env
DB_CONNECTION=sqlite
Create empty database file:
touch database/database.sqlite

Or use MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loyalty
DB_USERNAME=root
DB_PASSWORD=

4. Generate Application Key
```
php artisan key:generate
```
5. Run Migrations
```
php artisan migrate
```
6. Seed Database
```
php artisan db:seed
```
This creates:
6 achievements
5 badges
5 test users with different progress levels

7. Install Frontend Dependencies
```
npm install
```
8. Build Assets
```
npm run build
```
9. Start Development Servers

# Terminal - server
```
composer run dev
```
Visit http://127.0.0.1:8000 to access the application.

Test Users
The seeder creates the following test users (password: password for all):

Visit http://127.0.0.1:8000/register to register on the application.
After a successfull registration or login, you'll be redirected to the dashboard. you can purchase the product to see how the badge and acheivement and cashback works.

API Endpoints
Get User Achievements
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
