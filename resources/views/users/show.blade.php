@extends('layouts.app')
@section('page-title', $user->name)

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">User Profile</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('users.index') }}">Users</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>{{ $user->name }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @role('super_admin|manager')
            <a href="{{ route('users.edit', $user) }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endrole
            <a href="{{ route('users.index') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Profile Card -->
        <div class="card text-center lg:col-span-1">
            <div class="card-body">
                <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full mx-auto ring-4 ring-blue-100 dark:ring-blue-900" alt="">
                <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->designation ?? $user->department }}</p>

                <div class="mt-3 flex justify-center flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="badge text-xs px-3" style="background-color: {{ $role->color ?? '#6b7280' }}20; color: {{ $role->color ?? '#6b7280' }}">
                            {{ $role->display_name ?? $role->name }}
                        </span>
                    @endforeach
                    <span class="{{ $user->status_badge }}">{{ ucfirst($user->status) }}</span>
                </div>

                <div class="mt-5 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-gray-600 dark:text-gray-300 truncate">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-gray-600 dark:text-gray-300">{{ $user->phone }}</span>
                    </div>
                    @endif
                    @if($user->employee_id)
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        <span class="text-gray-600 dark:text-gray-300">{{ $user->employee_id }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-gray-600 dark:text-gray-300">
                            {{ $user->last_login_at ? 'Last seen ' . $user->last_login_at->diffForHumans() : 'Never logged in' }}
                        </span>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->mealEntries->count()) }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">Total Entries</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ config('meal.currency_symbol') }}{{ number_format($user->bazarEntries->sum('amount'), 0) }}
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">Bazar Total</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Meals -->
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recent Meal Entries</h3>
                <a href="{{ route('meals.index', ['user_id' => encryptId($user->id)]) }}" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>
            <div class="table-wrapper rounded-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMeals as $meal)
                        <tr>
                            <td class="text-sm text-gray-700 dark:text-gray-300">{{ $meal->meal_date->format('d M, Y') }}</td>
                            <td><span class="badge {{ $meal->meal_type_badge_color }}">{{ $meal->meal_type_label }}</span></td>
                            <td class="font-semibold">{{ $meal->quantity }}</td>
                            <td>
                                @if($meal->status === 'approved')<span class="badge-success">Approved</span>
                                @elseif($meal->status === 'pending')<span class="badge-warning">Pending</span>
                                @else<span class="badge-danger">Rejected</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-gray-400">No meal entries found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
