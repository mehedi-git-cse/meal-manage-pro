<?php

namespace App\Services;

use App\Models\MealCost;
use App\Models\MonthlyMealSummary;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Generate monthly report data for all users.
     */
    public function generateMonthlyReport(int $year, int $month): array
    {
        $mealCost = MealCost::where('year', $year)->where('month', $month)->first();

        $summaries = MonthlyMealSummary::with('user')
            ->where('year', $year)
            ->where('month', $month)
            ->orderBy('total_meals', 'desc')
            ->get();

        return [
            'year' => $year,
            'month' => $month,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
            'meal_cost' => $mealCost,
            'summaries' => $summaries,
            'total_meals' => $summaries->sum('total_meals'),
            'total_cost' => $summaries->sum('total_cost'),
            'total_bazar' => $summaries->sum('bazar_contribution'),
            'net_balance' => $summaries->sum('balance'),
        ];
    }

    /**
     * Generate user-specific annual report.
     */
    public function generateUserAnnualReport(int $userId, int $year): array
    {
        $user = User::findOrFail($userId);

        $summaries = MonthlyMealSummary::with('mealCost')
            ->where('user_id', $userId)
            ->where('year', $year)
            ->orderBy('month')
            ->get();

        return [
            'user' => $user,
            'year' => $year,
            'summaries' => $summaries,
            'total_meals' => $summaries->sum('total_meals'),
            'total_cost' => $summaries->sum('total_cost'),
            'total_bazar' => $summaries->sum('bazar_contribution'),
            'net_balance' => $summaries->sum('balance'),
        ];
    }

    /**
     * Get chart data for expense analysis.
     */
    public function getExpenseChartData(int $year): array
    {
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $cost = MealCost::where('year', $year)->where('month', $m)->first();
            $data[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'bazar' => $cost?->total_bazar_cost ?? 0,
                'meals' => $cost?->total_meals ?? 0,
                'rate' => $cost?->cost_per_meal ?? 0,
            ];
        }
        return $data;
    }
}
