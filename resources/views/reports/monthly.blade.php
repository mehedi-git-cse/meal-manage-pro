@extends('layouts.app')
@section('page-title', 'Monthly Report')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Monthly Report</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Monthly Report</span>
            </div>
        </div>
        @if(isset($mealCost))
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.exportMonthlyPdf', ['year' => $year, 'month' => $month]) }}"
               class="btn-danger" target="_blank">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF
            </a>
            <a href="{{ route('reports.exportMonthlyExcel', ['year' => $year, 'month' => $month]) }}"
               class="btn-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </a>
        </div>
        @endif
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.monthly') }}" class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <label class="form-label mb-0">Year</label>
                    <select name="year" class="form-select w-28">
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <label class="form-label mb-0">Month</label>
                    <select name="month" class="form-select w-36">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Generate</button>
            </form>
        </div>
    </div>

    @if(isset($mealCost))
    <!-- Summary -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Meals</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($mealCost->total_meals, 1) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Bazar Cost</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($mealCost->total_bazar_cost, 0) }}
            </p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Cost/Meal</p>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($mealCost->cost_per_meal, 2) }}
            </p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400">Members</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries->count() }}</p>
        </div>
    </div>

    <!-- Per-Member Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                Member Summary — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}
            </h3>
            @if($mealCost->is_finalized)
                <span class="badge-success">Finalized</span>
            @else
                <span class="badge-warning">Draft</span>
            @endif
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
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
                    @foreach($summaries as $i => $summary)
                    <tr>
                        <td class="text-gray-400">{{ $i + 1 }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="{{ $summary->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $summary->user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $summary->breakfast_meals }}</td>
                        <td>{{ $summary->lunch_meals }}</td>
                        <td>{{ $summary->dinner_meals }}</td>
                        <td class="font-semibold">{{ number_format($summary->total_meals, 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->total_cost, 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summary->bazar_contribution, 2) }}</td>
                        <td>
                            @php $bal = $summary->balance ?? 0; @endphp
                            <span class="{{ $bal >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                                {{ $bal >= 0 ? '+' : '' }}{{ config('meal.currency_symbol') }}{{ number_format(abs($bal), 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 font-semibold text-gray-900 dark:text-white">
                        <td colspan="2">TOTAL</td>
                        <td>{{ $summaries->sum('breakfast_meals') }}</td>
                        <td>{{ $summaries->sum('lunch_meals') }}</td>
                        <td>{{ $summaries->sum('dinner_meals') }}</td>
                        <td>{{ number_format($summaries->sum('total_meals'), 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summaries->sum('total_cost'), 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($summaries->sum('bazar_contribution'), 2) }}</td>
                        <td>—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @elseif(isset($year) && isset($month))
    <div class="card">
        <div class="card-body text-center py-12">
            <div class="text-5xl mb-3">📊</div>
            <p class="text-gray-500 dark:text-gray-400">No cost data for {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</p>
            @role('super_admin|manager')
            <a href="{{ route('costs.index') }}" class="btn-primary mt-3 inline-flex">Calculate First</a>
            @endrole
        </div>
    </div>
    @endif
</div>
@endsection
