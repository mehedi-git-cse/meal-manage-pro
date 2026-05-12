<?php

namespace App\Repositories;

use App\Models\BazarEntry;
use Illuminate\Pagination\LengthAwarePaginator;

class BazarRepository
{
    public function __construct(private readonly BazarEntry $model) {}

    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'category', 'vendor'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where('item_name', 'like', '%' . $filters['search'] . '%');
            })
            ->when(isset($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(isset($filters['category_id']), fn($q) => $q->where('category_id', $filters['category_id']))
            ->when(isset($filters['year']), fn($q) => $q->whereYear('entry_date', $filters['year']))
            ->when(isset($filters['month']), fn($q) => $q->whereMonth('entry_date', $filters['month']))
            ->when(isset($filters['date_from']), fn($q) => $q->whereDate('entry_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($q) => $q->whereDate('entry_date', '<=', $filters['date_to']))
            ->orderBy('entry_date', 'desc')
            ->paginate($perPage);
    }

    public function getMonthlyTotal(int $year, int $month): float
    {
        return $this->model
            ->whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->sum('amount');
    }

    public function getUserMonthlyTotal(int $userId, int $year, int $month): float
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->sum('amount');
    }

    public function create(array $data): BazarEntry
    {
        return $this->model->create($data);
    }

    public function update(BazarEntry $entry, array $data): bool
    {
        return $entry->update($data);
    }

    public function delete(BazarEntry $entry): bool
    {
        return $entry->delete();
    }

    public function getCategoryWiseExpense(int $year, int $month): \Illuminate\Support\Collection
    {
        return $this->model
            ->with('category')
            ->whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get()
            ->map(fn($item) => (object)[
                'name'  => $item->category?->name ?? 'Uncategorized',
                'total' => $item->total,
            ]);
    }
}
