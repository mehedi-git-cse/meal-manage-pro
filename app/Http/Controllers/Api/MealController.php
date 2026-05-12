<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMealEntryRequest;
use App\Models\MealEntry;
use App\Repositories\MealRepository;
use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function __construct(
        private readonly MealRepository $mealRepository,
        private readonly MealService $mealService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['user_id', 'meal_type', 'status', 'date_from', 'date_to', 'year', 'month']);
        $meals = $this->mealRepository->getAllPaginated($filters, $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $meals,
        ]);
    }

    public function store(StoreMealEntryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['approved_by'] = auth()->id();

        $result = $this->mealService->createMealEntry($data);

        $statusCode = $result['success'] ? 201 : 422;

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $statusCode);
    }

    public function show(MealEntry $meal): JsonResponse
    {
        $meal->load('user', 'approvedBy');

        return response()->json([
            'success' => true,
            'data' => $meal,
        ]);
    }

    public function update(StoreMealEntryRequest $request, MealEntry $meal): JsonResponse
    {
        $this->mealRepository->update($meal, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Meal entry updated.',
            'data' => $meal->fresh('user'),
        ]);
    }

    public function destroy(MealEntry $meal): JsonResponse
    {
        $this->mealRepository->delete($meal);

        return response()->json([
            'success' => true,
            'message' => 'Meal entry deleted.',
        ]);
    }

    public function daily(Request $request): JsonResponse
    {
        $date = $request->get('date', today()->toDateString());
        $meals = $this->mealRepository->getDailyMeals($date);

        return response()->json([
            'success' => true,
            'date' => $date,
            'summary' => [
                'breakfast' => $meals->where('meal_type', 'breakfast')->sum('quantity'),
                'lunch' => $meals->where('meal_type', 'lunch')->sum('quantity'),
                'dinner' => $meals->where('meal_type', 'dinner')->sum('quantity'),
                'total' => $meals->sum('quantity'),
            ],
            'data' => $meals,
        ]);
    }

    public function stats(): JsonResponse
    {
        $stats = $this->mealService->getDashboardStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
