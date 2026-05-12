<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealEntryRequest;
use App\Models\MealEntry;
use App\Models\User;
use App\Repositories\MealRepository;
use App\Services\MealService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MealEntryController extends Controller
{
    public function __construct(
        private readonly MealRepository $mealRepository,
        private readonly MealService $mealService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['meal_type', 'status', 'date_from', 'date_to', 'year', 'month']);

        // Always scope to the logged-in user — no other user's data shown
        $filters['user_id'] = auth()->id();

        $meals = $this->mealRepository->getAllPaginated($filters);
        $users = User::mealActive()->orderBy('name')->get();

        return view('meals.index', compact('meals', 'users', 'filters'));
    }

    public function create()
    {
        $user = auth()->user();
        $users = ($user->hasAnyRole(['admin', 'manager']))
            ? User::mealActive()->orderBy('name')->get()
            : User::where('id', $user->id)->get();

        $today = today()->format('Y-m-d');

        return view('meals.create', compact('users', 'today'));
    }

    public function store(StoreMealEntryRequest $request)
    {
        $data = $request->validated();
        $data['approved_by'] = auth()->id();
        $data['status'] = 'approved';

        $result = $this->mealService->createMealEntry($data);

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('meals.index')
            ->with('success', 'Meal entry added successfully.');
    }

    public function show(MealEntry $meal_entry)
    {
        $meal_entry->load('user', 'approvedBy');
        return view('meals.show', compact('meal_entry'));
    }

    public function edit(MealEntry $meal_entry)
    {
        $mealEntry = $meal_entry;
        $users = User::mealActive()->orderBy('name')->get();
        return view('meals.edit', compact('mealEntry', 'users'));
    }

    public function update(StoreMealEntryRequest $request, MealEntry $meal_entry)
    {
        $this->mealRepository->update($meal_entry, $request->validated());

        return redirect()->route('meals.index')
            ->with('success', 'Meal entry updated successfully.');
    }

    public function destroy(MealEntry $meal_entry)
    {
        $this->mealRepository->delete($meal_entry);

        return redirect()->route('meals.index')
            ->with('success', 'Meal entry deleted successfully.');
    }

    /**
     * Bulk meal entry for all active users.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'meal_date' => ['required', 'date', 'before_or_equal:today'],
            'meal_type' => ['required', 'in:breakfast,lunch,dinner,custom'],
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
            'quantity' => ['required', 'numeric', 'min:0.5', 'max:3'],
        ]);

        $count = $this->mealService->bulkCreateMealEntries(
            $request->user_ids,
            $request->meal_date,
            $request->meal_type,
            $request->quantity
        );

        return redirect()->route('meals.index')
            ->with('success', "{$count} meal entries created successfully.");
    }

    /**
     * Get meals for a specific date (AJAX).
     */
    public function getDailyMeals(Request $request): JsonResponse
    {
        $request->validate(['date' => 'required|date']);
        $meals = $this->mealRepository->getDailyMeals($request->date);

        return response()->json($meals);
    }
}
