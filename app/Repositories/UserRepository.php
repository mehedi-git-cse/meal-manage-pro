<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(private readonly User $model) {}

    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('roles')
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where(function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['search'] . '%')
                       ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                       ->orWhere('employee_id', 'like', '%' . $filters['search'] . '%');
                });
            })
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['role']), fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', $filters['role'])))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return $this->model->with('roles')->find($id);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getActiveMealUsers()
    {
        return $this->model->mealActive()->orderBy('name')->get();
    }
}
