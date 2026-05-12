<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function index(Request $request)
    {
        $users = $this->userRepository->getAllPaginated($request->only(['search', 'status', 'role']));
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['password'] = Hash::make($data['password']);
        $role = $data['role'];
        unset($data['role'], $data['password_confirmation']);

        $user = $this->userRepository->create($data);
        $user->assignRole($role);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'mealEntries', 'bazarEntries');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $role = $data['role'];
        unset($data['role'], $data['password_confirmation']);

        $this->userRepository->update($user, $data);
        $user->syncRoles([$role]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $this->userRepository->delete($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleMealStatus(User $user)
    {
        $user->update(['meal_active' => !$user->meal_active]);
        $status = $user->meal_active ? 'activated' : 'deactivated';

        return back()->with('success', "Meal status {$status} for {$user->name}.");
    }
}
