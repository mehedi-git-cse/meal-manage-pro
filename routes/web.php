<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MealEntryController;
use App\Http\Controllers\MealCostController;
use App\Http\Controllers\BazarController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

use App\Models\MealEntry;
use App\Models\BazarEntry;
use App\Models\User;

// ─── Root ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    $todayMeals = MealEntry::with('user')
        ->whereDate('meal_date', today())
        ->where('status', 'approved')
        ->orderBy('meal_type')
        ->get()
        ->groupBy(fn($m) => $m->meal_type);

    $todayTotalQty   = $todayMeals->flatten()->sum('quantity');
    $activeMembers   = User::where('meal_active', true)->where('status', 'active')->count();
    $monthBazarTotal = BazarEntry::whereYear('entry_date', now()->year)
                          ->whereMonth('entry_date', now()->month)
                          ->sum('amount');
    $totalMeals = MealEntry::where('status', 'approved')->sum('quantity');

    // Monthly trend bars (last 12 months)
    $trendData = collect(range(11, 0))->map(function ($i) {
        $date = now()->subMonths($i);
        return (float) MealEntry::where('status', 'approved')
            ->whereYear('meal_date', $date->year)
            ->whereMonth('meal_date', $date->month)
            ->sum('quantity');
    });
    $trendMax = $trendData->max() ?: 1;

    return view('welcome', compact(
        'todayMeals', 'todayTotalQty', 'activeMembers',
        'monthBazarTotal', 'totalMeals', 'trendData', 'trendMax'
    ));
});

// ─── Guest Routes ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // Meals
    Route::resource('meals', MealEntryController::class)->parameters(['meals' => 'meal_entry']);
    Route::post('/meals/bulk', [MealEntryController::class, 'bulkStore'])->name('meals.bulk');
    Route::get('/meals/daily/list', [MealEntryController::class, 'getDailyMeals'])->name('meals.daily');

    // Bazar
    Route::resource('bazar', BazarController::class);
    Route::post('/bazar/{bazar}/verify', [BazarController::class, 'verify'])->name('bazar.verify');
    Route::prefix('bazar')->name('bazar.')->group(function () {
        Route::get('/categories/manage', [BazarController::class, 'categories'])->name('categories');
        Route::post('/categories/store', [BazarController::class, 'storeCategory'])->name('categories.store');
        Route::get('/vendors/manage', [BazarController::class, 'vendors'])->name('vendors');
        Route::post('/vendors/store', [BazarController::class, 'storeVendor'])->name('vendors.store');
    });

    // Deposits
    Route::resource('deposits', DepositController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/user-wise', [ReportController::class, 'userWise'])->name('user-wise');
        Route::get('/monthly/pdf', [ReportController::class, 'exportMonthlyPdf'])->name('monthly.pdf');
        Route::get('/monthly/excel', [ReportController::class, 'exportMonthlyExcel'])->name('monthly.excel');
        Route::get('/user/pdf', [ReportController::class, 'exportUserPdf'])->name('user.pdf');
    });

    // ─── Manager / Admin Routes ──────────────────────────────────────────────
    Route::middleware(['role:manager,super_admin'])->group(function () {
        // Meal Costs
        Route::resource('costs', MealCostController::class)->only(['index', 'show']);
        Route::post('/costs/calculate', [MealCostController::class, 'calculate'])->name('costs.calculate');
        Route::post('/costs/{cost}/finalize', [MealCostController::class, 'finalize'])->name('costs.finalize');
        Route::put('/costs/{cost}/rate', [MealCostController::class, 'updateRate'])->name('costs.rate');
    });

    // ─── Super Admin Routes ──────────────────────────────────────────────────
    Route::middleware(['role:super_admin'])->group(function () {
        // Users
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-meal', [UserController::class, 'toggleMealStatus'])->name('users.toggle-meal');

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        });
    });
});
