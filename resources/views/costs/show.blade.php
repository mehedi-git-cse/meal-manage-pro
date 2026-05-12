@extends('layouts.app')
@section('page-title', 'Cost Details — ' . $cost->month_year)

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $cost->month_year }} — Cost Details</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('costs.index') }}">Meal Costs</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>{{ $cost->month_year }}</span>
            </div>
        </div>
        <div class="flex gap-3">
            @if(!$cost->is_finalized)
            <form method="POST" action="{{ route('costs.finalize', $cost) }}" onsubmit="return confirm('Finalize this month?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-primary">Finalize Month</button>
            </form>
            @endif
            <a href="{{ route('costs.index') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Meals</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($cost->total_meals, 1) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Bazar</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($cost->total_bazar_cost, 0) }}
            </p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Cost per Meal</p>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($cost->cost_per_meal, 2) }}
            </p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
            <div class="mt-2">
                @if($cost->is_finalized)
                    <span class="badge-success">Finalized</span>
                    <p class="text-xs text-gray-400 mt-1">by {{ $cost->finalizedBy?->name }}</p>
                @else
                    <span class="badge-warning">Draft</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Per-User Summary Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Per-Member Summary</h3>
            <a href="{{ route('reports.monthly', ['year' => $cost->year, 'month' => $cost->month]) }}" class="btn-secondary text-sm">View Report</a>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Breakfast</th>
                        <th>Lunch</th>
                        <th>Dinner</th>
                        <th>Total Meals</th>
                        <th>Meal Cost</th>
                        <th>Bazar</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summaries as $summary)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="{{ $summary->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $summary->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $summary->user->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $summary->breakfast_meals }}</td>
                        <td>{{ $summary->lunch_meals }}</td>
                        <td>{{ $summary->dinner_meals }}</td>
                        <td class="font-semibold">{{ number_format($summary->total_meals, 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->total_cost, 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->bazar_contribution, 2) }}</td>
                        <td>
                            @php $balance = $summary->balance ?? 0; @endphp
                            <span class="{{ $balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                                {{ $balance >= 0 ? '+' : '' }}{{ config('meal.currency_symbol') }}{{ number_format(abs($balance), 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">No summaries calculated yet</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($summaries->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 font-semibold">
                        <td>Total</td>
                        <td>{{ $summaries->sum('breakfast_meals') }}</td>
                        <td>{{ $summaries->sum('lunch_meals') }}</td>
                        <td>{{ $summaries->sum('dinner_meals') }}</td>
                        <td>{{ number_format($summaries->sum('total_meals'), 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summaries->sum('total_cost'), 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summaries->sum('bazar_contribution'), 2) }}</td>
                        <td>—</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
