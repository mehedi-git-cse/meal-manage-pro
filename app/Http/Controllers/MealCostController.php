<?php

namespace App\Http\Controllers;

use App\Models\MealCost;
use App\Services\MealService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class MealCostController extends Controller
{
    public function __construct(
        private readonly MealService $mealService,
        private readonly ReportService $reportService
    ) {}

    public function index()
    {
        $costs = MealCost::with('finalizedBy')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(12);

        return view('costs.index', compact('costs'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'year' => ['required', 'integer', 'min:2020', 'max:' . now()->year],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $mealCost = $this->mealService->calculateMonthlyCost($request->year, $request->month);

        return redirect()->route('costs.index')
            ->with('success', "Meal cost calculated for " . date('F Y', mktime(0,0,0,$request->month,1,$request->year)));
    }

    public function show(MealCost $cost)
    {
        $cost->load('summaries.user', 'finalizedBy');
        return view('costs.show', compact('cost'));
    }

    public function finalize(MealCost $cost)
    {
        if ($cost->is_finalized) {
            return back()->with('error', 'This cost is already finalized.');
        }

        $cost->update([
            'is_finalized' => true,
            'finalized_by' => auth()->id(),
            'finalized_at' => now(),
        ]);

        return back()->with('success', 'Monthly cost finalized successfully.');
    }

    public function updateRate(Request $request, MealCost $cost)
    {
        $request->validate([
            'meal_rate' => ['required', 'numeric', 'min:1'],
        ]);

        $cost->update(['meal_rate' => $request->meal_rate]);

        return back()->with('success', 'Meal rate updated.');
    }
}
