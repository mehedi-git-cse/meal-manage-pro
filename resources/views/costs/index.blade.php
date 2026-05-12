@extends('layouts.app')
@section('page-title', 'Meal Costs')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Meal Cost Management</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Meal Costs</span>
            </div>
        </div>
        <!-- Calculate Cost -->
        <button onclick="document.getElementById('calcModal').classList.remove('hidden')" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 13h.01M13 13h.01M17 13h.01M13 17h.01M17 17h.01M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
            Calculate Month
        </button>
    </div>

    <!-- Rate Info Card -->
    <div class="card bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
        <div class="card-body flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Current Meal Rate</p>
                <p class="text-3xl font-bold text-blue-800 dark:text-blue-200 mt-1">
                    {{ config('meal.currency_symbol') }}{{ number_format(config('meal.default_meal_rate'), 2) }}
                    <span class="text-base font-normal text-blue-600 dark:text-blue-400">per meal</span>
                </p>
            </div>
            <button onclick="document.getElementById('rateModal').classList.remove('hidden')" class="btn-secondary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Update Rate
            </button>
        </div>
    </div>

    <!-- Costs Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Monthly Cost Records</h3>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Meals</th>
                        <th>Bazar Cost</th>
                        <th>Cost/Meal</th>
                        <th>Meal Rate</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($costs as $cost)
                    <tr>
                        <td class="font-medium text-gray-900 dark:text-white">{{ $cost->month_year }}</td>
                        <td>{{ number_format($cost->total_meals, 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($cost->total_bazar_cost, 2) }}</td>
                        <td class="font-semibold text-blue-600 dark:text-blue-400">
                            {{ config('meal.currency_symbol') }}{{ number_format($cost->cost_per_meal, 2) }}
                        </td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($cost->meal_rate, 2) }}</td>
                        <td>
                            @if($cost->is_finalized)
                                <span class="badge-success">Finalized</span>
                            @else
                                <span class="badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('costs.show', $cost) }}" class="btn btn-sm btn-secondary">View</a>
                                @if(!$cost->is_finalized)
                                <form method="POST" action="{{ route('costs.finalize', $cost) }}" class="inline" onsubmit="return confirm('Finalize this month? This cannot be undone.')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary">Finalize</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="text-5xl mb-3">💰</div>
                            <p class="text-gray-500 dark:text-gray-400">No cost records yet</p>
                            <button onclick="document.getElementById('calcModal').classList.remove('hidden')" class="btn-primary mt-3">Calculate First Month</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($costs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $costs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Calculate Modal -->
<div id="calcModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-sm shadow-2xl animate-bounce-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Calculate Monthly Cost</h3>
            <button onclick="document.getElementById('calcModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('costs.calculate') }}" class="p-6 space-y-4" data-loading>
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select">
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <p class="text-xs text-gray-400">This will calculate total bazar cost and meals for the selected month and compute per-meal cost.</p>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">Calculate</button>
                <button type="button" onclick="document.getElementById('calcModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Rate Modal -->
<div id="rateModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-sm shadow-2xl animate-bounce-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Update Meal Rate</h3>
            <button onclick="document.getElementById('rateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('costs.updateRate') }}" class="p-6 space-y-4" data-loading>
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">New Default Rate ({{ config('meal.currency_symbol') }})</label>
                <input type="number" name="meal_rate" value="{{ config('meal.default_meal_rate') }}"
                       class="form-input" step="0.5" min="1" required>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">Update Rate</button>
                <button type="button" onclick="document.getElementById('rateModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
