<?php

namespace App\Services;

use App\Models\MealEntry;
use App\Models\MealCost;
use App\Models\MonthlyMealSummary;
use App\Repositories\MealRepository;
use App\Repositories\BazarRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MealService
{
    public function __construct(
        private readonly MealRepository $mealRepository,
        private readonly BazarRepository $bazarRepository
    ) {}

    /**
     * Create a new meal entry with duplicate check.
     */
    public function createMealEntry(array $data): array
    {
        // Check duplicate
        if ($this->mealRepository->existsForDateAndType(
            $data['user_id'],
            $data['meal_date'],
            $data['meal_type']
        )) {
            return ['success' => false, 'message' => 'Meal entry already exists for this date and type.'];
        }

        $entry = $this->mealRepository->create($data);

        return ['success' => true, 'data' => $entry, 'message' => 'Meal entry created successfully.'];
    }

    /**
     * Bulk create meal entries for multiple users.
     */
    public function bulkCreateMealEntries(array $userIds, string $date, string $mealType, float $quantity = 1.0): int
    {
        $created = 0;
        $timestamp = now()->toDateTimeString();

        DB::beginTransaction();
        try {
            foreach ($userIds as $userId) {
                if (!$this->mealRepository->existsForDateAndType($userId, $date, $mealType)) {
                    $this->mealRepository->create([
                        'user_id' => $userId,
                        'meal_date' => $date,
                        'meal_type' => $mealType,
                        'quantity' => $quantity,
                        'status' => 'approved',
                    ]);
                    $created++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk meal creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $created;
    }

    /**
     * Calculate and finalize the monthly meal cost.
     * Distributes bazar cost proportionally by meal count.
     */
    public function calculateMonthlyCost(int $year, int $month): MealCost
    {
        DB::beginTransaction();
        try {
            $totalBazar = $this->bazarRepository->getMonthlyTotal($year, $month);
            $summary = $this->mealRepository->getMonthlySummary($year, $month);
            $totalMeals = $summary['total_meals'];

            $costPerMeal = $totalMeals > 0 ? round($totalBazar / $totalMeals, 4) : 0;

            // Update or create meal cost record
            $mealCost = MealCost::updateOrCreate(
                ['year' => $year, 'month' => $month],
                [
                    'total_bazar_cost' => $totalBazar,
                    'total_meals' => $totalMeals,
                    'cost_per_meal' => $costPerMeal,
                    'meal_rate' => $costPerMeal,
                ]
            );

            // Calculate per-user summaries
            $this->calculateUserSummaries($mealCost, $year, $month, $costPerMeal);

            DB::commit();
            return $mealCost;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Monthly cost calculation failed', ['error' => $e->getMessage(), 'year' => $year, 'month' => $month]);
            throw $e;
        }
    }

    /**
     * Calculate user-wise monthly summaries.
     */
    private function calculateUserSummaries(MealCost $mealCost, int $year, int $month, float $costPerMeal): void
    {
        // Get all user meal entries for the month
        $entries = MealEntry::with('user')
            ->whereYear('meal_date', $year)
            ->whereMonth('meal_date', $month)
            ->where('status', 'approved')
            ->get()
            ->groupBy('user_id');

        foreach ($entries as $userId => $userEntries) {
            $totalMeals = $userEntries->sum('quantity');
            $bazarContrib = $this->bazarRepository->getUserMonthlyTotal($userId, $year, $month);
            $totalCost = round($totalMeals * $costPerMeal, 2);
            $balance = round($bazarContrib - $totalCost, 2);

            MonthlyMealSummary::updateOrCreate(
                ['user_id' => $userId, 'year' => $year, 'month' => $month],
                [
                    'meal_cost_id' => $mealCost->id,
                    'total_meals' => $totalMeals,
                    'breakfast_count' => $userEntries->where('meal_type', 'breakfast')->sum('quantity'),
                    'lunch_count' => $userEntries->where('meal_type', 'lunch')->sum('quantity'),
                    'dinner_count' => $userEntries->where('meal_type', 'dinner')->sum('quantity'),
                    'total_cost' => $totalCost,
                    'bazar_contribution' => $bazarContrib,
                    'balance' => $balance,
                ]
            );
        }
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        return [
            'today_meals' => MealEntry::whereDate('meal_date', today())->where('status', 'approved')->sum('quantity'),
            'month_meals' => MealEntry::whereYear('meal_date', $currentYear)->whereMonth('meal_date', $currentMonth)->where('status', 'approved')->sum('quantity'),
            'month_bazar' => $this->bazarRepository->getMonthlyTotal($currentYear, $currentMonth),
            'active_users' => \App\Models\User::mealActive()->count(),
            'daily_breakdown' => $this->getDailyBreakdown($now),
            'monthly_trend' => $this->getMonthlyTrend($currentYear),
        ];
    }

    /**
     * Get meal breakdown for a specific day.
     */
    private function getDailyBreakdown(Carbon $date): array
    {
        $entries = MealEntry::whereDate('meal_date', $date)->where('status', 'approved')->get();

        return [
            'breakfast' => $entries->where('meal_type', 'breakfast')->sum('quantity'),
            'lunch' => $entries->where('meal_type', 'lunch')->sum('quantity'),
            'dinner' => $entries->where('meal_type', 'dinner')->sum('quantity'),
        ];
    }

    /**
     * Get monthly meal trend for charts (last 12 months).
     */
    private function getMonthlyTrend(int $year): array
    {
        $trend = [];
        for ($m = 1; $m <= 12; $m++) {
            $count = MealEntry::whereYear('meal_date', $year)->whereMonth('meal_date', $m)->where('status', 'approved')->sum('quantity');
            $trend[] = ['month' => date('M', mktime(0, 0, 0, $m, 1)), 'count' => (float) $count];
        }
        return $trend;
    }
}
