# 🍽 Meal Management System

A complete, production-ready Meal Management System built with **Laravel 11**, **Tailwind CSS**, and **Alpine.js**.

---

## ✨ Features

- **Role-Based Access Control** — Super Admin, Manager, Staff
- **Meal Entry Management** — Individual + Bulk entries, daily breakdown
- **Bazar Management** — Categories, vendors, receipt images, verification
- **Meal Cost Calculation** — Auto per-meal cost, monthly finalization
- **Monthly Reports** — PDF & Excel export
- **User-Wise Annual Reports** — Per-member breakdown
- **Dark / Light Mode** — Persisted via localStorage
- **Notifications** — In-app + Email (daily digest, monthly report)
- **Activity Logs** — Full audit trail via Spatie Activity Log
- **API Ready** — Sanctum-protected API v1

---

## 🛠 Requirements

| Tool     | Version  |
|----------|----------|
| PHP      | ≥ 8.2    |
| Composer | ≥ 2.x    |
| Node.js  | ≥ 18.x   |
| MySQL    | ≥ 8.0    |

---

## 🚀 Quick Setup

### 1. Install PHP & Node Dependencies

```bash
composer install
npm install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meal_management
DB_USERNAME=root
DB_PASSWORD=

COMPANY_NAME="My Company"
COMPANY_EMAIL=info@mycompany.com
MEAL_DEFAULT_RATE=70
MEAL_CURRENCY=BDT
MEAL_CURRENCY_SYMBOL=৳
```

### 3. Create Database

In MySQL:
```sql
CREATE DATABASE meal_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Migrations & Seed

```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- Roles: `super_admin`, `manager`, `staff`
- Default users (see below)
- Default settings

### 5. Build Frontend Assets

```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Start the Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Default Login Credentials

| Role        | Email                    | Password    |
|-------------|--------------------------|-------------|
| Super Admin | admin@example.com        | Password1   |
| Manager     | manager@example.com      | Password1   |
| Staff       | alice@example.com        | Password1   |

> ⚠️ **Change all passwords immediately after first login in production!**

---

## 📁 Project Structure

```
app/
├── Console/Commands/          # Artisan commands
├── Exports/                   # Excel exports (Maatwebsite)
├── Http/
│   ├── Controllers/           # Web + API controllers
│   ├── Middleware/            # Auth, role, activity log
│   └── Requests/              # Form request validation
├── Models/                    # Eloquent models
├── Notifications/             # Email + DB notifications
├── Providers/                 # Service providers
├── Repositories/              # Data access layer
└── Services/                  # Business logic layer

database/
├── factories/                 # Model factories for testing
├── migrations/                # Database schema
└── seeders/                   # Default data

resources/
├── css/app.css                # Tailwind CSS + components
├── js/app.js                  # Alpine.js + Toast + helpers
└── views/
    ├── auth/                  # Login, register, password reset
    ├── bazar/                 # Bazar CRUD views
    ├── costs/                 # Meal cost views
    ├── dashboard/             # Dashboard with charts
    ├── layouts/               # App + Auth layouts
    ├── meals/                 # Meal entry CRUD views
    ├── profile/               # User profile
    ├── reports/               # Monthly & user-wise reports
    ├── settings/              # System settings
    └── users/                 # User management

routes/
├── api.php                    # API v1 routes (Sanctum)
├── console.php                # Scheduled commands
└── web.php                    # Web routes
```

---

## 🔧 Queue & Scheduler

For notifications and scheduled reports to work, run:

```bash
# Queue worker
php artisan queue:work

# Or with Horizon (recommended)
php artisan horizon
```

For scheduled tasks (monthly cost auto-calculation, daily digest):

```bash
# Add to crontab:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📤 API Usage

Base URL: `http://localhost:8000/api/v1`

### Authentication

```bash
POST /api/v1/auth/login
{
  "email": "admin@example.com",
  "password": "Password1"
}
# Returns: { "token": "...", "user": {...} }
```

Use `Authorization: Bearer {token}` for all subsequent requests.

### Endpoints

| Method | Endpoint                  | Description          |
|--------|---------------------------|----------------------|
| POST   | /auth/login               | Get token            |
| POST   | /auth/logout              | Revoke token         |
| GET    | /auth/me                  | Get current user     |
| GET    | /meals                    | List meals           |
| POST   | /meals                    | Create meal entry    |
| GET    | /meals/{id}               | Get meal             |
| PUT    | /meals/{id}               | Update meal          |
| DELETE | /meals/{id}               | Delete meal          |
| GET    | /meals/daily/summary      | Daily summary        |
| GET    | /dashboard/stats          | Dashboard stats      |
| GET    | /health                   | Health check         |

---

## 🧪 Testing Data

Generate test data with factories:

```bash
php artisan tinker

# Create 20 test users with staff role
\App\Models\User::factory(20)->create()->each(fn($u) => $u->assignRole('staff'));

# Create meal entries
\App\Models\MealEntry::factory(100)->create();
```

---

## 🔒 Security Notes

- All routes protected by authentication middleware
- Role-based authorization via `spatie/laravel-permission`
- API endpoints secured with Laravel Sanctum tokens
- Password policy: min 8 chars, mixed case, numbers
- Rate limiting on login (5 attempts) and API (60/min)
- Activity logging for all data mutations
- CSRF protection on all web forms
- XSS protection via Blade auto-escaping

---

## 📦 Key Packages

| Package                        | Purpose                    |
|-------------------------------|----------------------------|
| laravel/sanctum               | API authentication         |
| spatie/laravel-permission     | RBAC                       |
| spatie/laravel-activitylog    | Audit logs                 |
| barryvdh/laravel-dompdf       | PDF export                 |
| maatwebsite/excel             | Excel export               |
| intervention/image            | Image handling             |
| laravel/horizon               | Queue monitoring           |

---

## 📄 License

MIT License — Free for personal and commercial use.
