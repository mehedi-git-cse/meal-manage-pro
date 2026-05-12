<?php

namespace App\Repositories;

use App\Models\MealEntry;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MealRepository
{
    public function __construct(private readonly MealEntry $model) {}

    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'approvedBy'])
            ->when(isset($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(isset($filters['meal_type']), fn($q) => $q->where('meal_type', $filters['meal_type']))
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['date_from']), fn($q) => $q->whereDate('meal_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($q) => $q->whereDate('meal_date', '<=', $filters['date_to']))
            ->when(isset($filters['year']), fn($q) => $q->whereYear('meal_date', $filters['year']))
            ->when(isset($filters['month']), fn($q) => $q->whereMonth('meal_date', $filters['month']))
            ->orderBy('meal_date', 'desc')
            ->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function create(array $data): MealEntry
    {
        return $this->model->create($data);
    }

    public function update(MealEntry $meal, array $data): bool
    {
        return $meal->update($data);
    }

    public function delete(MealEntry $meal): bool
    {
        return $meal->delete();
    }

    public function findById(int $id): ?MealEntry
    {
        return $this->model->with('user')->find($id);
    }

    public function getDailyMeals(string $date): Collection
    {
        return $this->model->with('user')
            ->whereDate('meal_date', $date)
            ->where('status', 'approved')
            ->orderBy('meal_type')
            ->get();
    }

    public function getMonthlySummary(int $year, int $month): array
    {
        $entries = $this->model
            ->with('user')
            ->whereYear('meal_date', $year)
            ->whereMonth('meal_date', $month)
            ->where('status', 'approved')
            ->get();

        return [
            'total_meals' => $entries->sum('quantity'),
            'breakfast' => $entries->where('meal_type', 'breakfast')->sum('quantity'),
            'lunch' => $entries->where('meal_type', 'lunch')->sum('quantity'),
            'dinner' => $entries->where('meal_type', 'dinner')->sum('quantity'),
            'unique_users' => $entries->pluck('user_id')->unique()->count(),
        ];
    }

    public function getUserMonthlySummary(int $userId, int $year, int $month): array
    {
        $entries = $this->model
            ->where('user_id', $userId)
            ->whereYear('meal_date', $year)
            ->whereMonth('meal_date', $month)
            ->where('status', 'approved')
            ->get();

        return [
            'total' => $entries->sum('quantity'),
            'breakfast' => $entries->where('meal_type', 'breakfast')->sum('quantity'),
            'lunch' => $entries->where('meal_type', 'lunch')->sum('quantity'),
            'dinner' => $entries->where('meal_type', 'dinner')->sum('quantity'),
        ];
    }

    public function bulkCreate(array $entries): void
    {
        $this->model->insert($entries);
    }

    /**
     * Check if a user already has a meal entry for a specific date and type.
     */
    public function existsForDateAndType(int $userId, string $date, string $type): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereDate('meal_date', $date)
            ->where('meal_type', $type)
            ->exists();
    }
}
