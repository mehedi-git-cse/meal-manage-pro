<?php

namespace App\Http\Controllers;

use App\Services\MealService;
use App\Services\ReportService;
use App\Models\User;
use App\Models\MealEntry;
use App\Models\BazarEntry;
use App\Models\MealCost;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private readonly MealService $mealService,
        private readonly ReportService $reportService
    ) {}

    public function index(Request $request)
    {
        $now = Carbon::now();
        $year = $request->get('year', $now->year);
        $month = $request->get('month', $now->month);

        // Core stats
        $stats = $this->mealService->getDashboardStats();

        // Chart data
        $expenseChart = $this->reportService->getExpenseChartData($year);

        // Recent entries
        $recentMeals = MealEntry::with('user')
            ->latest('meal_date')
            ->take(10)
            ->get();

        $recentBazar = BazarEntry::with(['user', 'category'])
            ->latest('entry_date')
            ->take(5)
            ->get();

        // Top consumers this month
        $topConsumers = MealEntry::with('user')
            ->whereYear('meal_date', $now->year)
            ->whereMonth('meal_date', $now->month)
            ->where('status', 'approved')
            ->selectRaw('user_id, SUM(quantity) as total_meals')
            ->groupBy('user_id')
            ->orderByDesc('total_meals')
            ->take(5)
            ->get();

        // Monthly cost summary
        $monthlyStats = MealCost::orderByDesc('year')->orderByDesc('month')->take(6)->get();

        // My deposit this month
        $userId = auth()->id();
        $myMonthDeposit = Deposit::where('user_id', $userId)
            ->whereYear('deposit_date', $now->year)
            ->whereMonth('deposit_date', $now->month)
            ->sum('amount');

        // My total meal cost this month (quantity × meal_rate)
        $myMonthMealCost = MealEntry::where('user_id', $userId)
            ->whereYear('meal_date', $now->year)
            ->whereMonth('meal_date', $now->month)
            ->where('status', 'approved')
            ->get()
            ->sum(fn($m) => $m->quantity * $m->meal_rate);

        $myBalance = $myMonthDeposit - $myMonthMealCost;

        // My personal stats (logged-in user)
        $myTodayMeals = MealEntry::where('user_id', $userId)
            ->whereDate('meal_date', today())
            ->where('status', 'approved')
            ->sum('quantity');

        $myMonthMeals = MealEntry::where('user_id', $userId)
            ->whereYear('meal_date', $now->year)
            ->whereMonth('meal_date', $now->month)
            ->where('status', 'approved')
            ->sum('quantity');

        $myDailyEntries = MealEntry::where('user_id', $userId)
            ->whereDate('meal_date', today())
            ->where('status', 'approved')
            ->get();

        $myDailyBreakdown = [
            'breakfast' => $myDailyEntries->where('meal_type', 'breakfast')->sum('quantity'),
            'lunch'     => $myDailyEntries->where('meal_type', 'lunch')->sum('quantity'),
            'dinner'    => $myDailyEntries->where('meal_type', 'dinner')->sum('quantity'),
        ];

        // Today's meals grouped by type (all users)
        $todayMeals = MealEntry::with('user')
            ->whereDate('meal_date', today())
            ->where('status', 'approved')
            ->orderBy('meal_type')
            ->get()
            ->groupBy(fn($m) => $m->meal_type);

        return view('dashboard.index', compact(
            'stats',
            'expenseChart',
            'recentMeals',
            'recentBazar',
            'topConsumers',
            'monthlyStats',
            'myMonthDeposit',
            'myMonthMealCost',
            'myBalance',
            'myTodayMeals',
            'myMonthMeals',
            'myDailyBreakdown',
            'todayMeals',
            'year',
            'month',
            'now'
        ));
    }
}
