@extends('layouts.app')
@section('page-title', 'Users')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">User Management</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Users</span>
            </div>
        </div>
        @role('super_admin|manager')
        <a href="{{ route('users.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
        @endrole
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-input text-sm col-span-2 md:col-span-1" placeholder="Search name, email, ID...">

                <select name="status" class="form-select text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>

                <select name="role" class="form-select text-sm">
                    <option value="">All Roles</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">All Users ({{ $users->total() }})</h3>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Employee ID</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Meal Active</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full ring-2 ring-gray-100 dark:ring-gray-700" alt="">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm text-gray-500 dark:text-gray-400">{{ $user->employee_id ?? '—' }}</td>
                        <td class="text-sm text-gray-500 dark:text-gray-400">{{ $user->department ?? '—' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge text-xs" style="background-color: {{ $role->color ?? '#6b7280' }}20; color: {{ $role->color ?? '#6b7280' }}">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <form method="POST" action="{{ route('users.toggle-meal', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" title="Toggle meal status">
                                    @if($user->meal_active)
                                        <span class="badge-success text-xs cursor-pointer">Active</span>
                                    @else
                                        <span class="badge-danger text-xs cursor-pointer">Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <span class="{{ $user->status_badge }}">{{ ucfirst($user->status) }}</span>
                        </td>
                        <td class="text-xs text-gray-400">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary" title="View">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @role('super_admin|manager')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form id="del-user-{{ encryptId($user->id) }}" method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('del-user-{{ encryptId($user->id) }}')" class="btn btn-sm btn-danger" title="Delete">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="text-5xl mb-3">👥</div>
                            <p class="text-gray-500 dark:text-gray-400">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
