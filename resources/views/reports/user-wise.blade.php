@extends('layouts.app')
@section('page-title', 'User-Wise Report')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">User-Wise Annual Report</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>User-Wise Report</span>
            </div>
        </div>
        @if(isset($report) && $report)
        <a href="{{ route('reports.user.pdf', ['user_id' => $userId, 'year' => $year]) }}"
           class="btn-danger" target="_blank">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
        @endif
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.user-wise') }}" class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <label class="form-label mb-0">User</label>
                    <select name="user_id" class="form-select w-48" required>
                        <option value="">Select User...</option>
                        @foreach($users as $user)
                            <option value="{{ encryptId($user->id) }}" {{ isset($userId) && $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <label class="form-label mb-0">Year</label>
                    <select name="year" class="form-select w-28">
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" {{ isset($year) && $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Generate</button>
            </form>
        </div>
    </div>

    @if(isset($report) && $report && isset($selectedUser))
    <!-- User Info -->
    <div class="card">
        <div class="card-body flex items-center gap-4">
            <img src="{{ $selectedUser->avatar_url }}" class="w-14 h-14 rounded-full ring-4 ring-gray-100 dark:ring-gray-700" alt="">
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedUser->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $selectedUser->email }} · {{ $selectedUser->employee_id }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Annual Total Meals</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(collect($report)->sum('total_meals'), 1) }}</p>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $year }} Monthly Breakdown</h3>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
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
                    @foreach($report as $row)
                    <tr>
                        <td class="font-medium text-gray-900 dark:text-white">{{ $row['month_name'] }}</td>
                        <td>{{ $row['breakfast_meals'] ?? 0 }}</td>
                        <td>{{ $row['lunch_meals'] ?? 0 }}</td>
                        <td>{{ $row['dinner_meals'] ?? 0 }}</td>
                        <td class="font-semibold">{{ number_format($row['total_meals'] ?? 0, 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($row['total_cost'] ?? 0, 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($row['bazar_contribution'] ?? 0, 2) }}</td>
                        <td>
                            @php $bal = $row['balance'] ?? 0; @endphp
                            <span class="{{ $bal >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                                {{ $bal >= 0 ? '+' : '' }}{{ config('meal.currency_symbol') }}{{ number_format(abs($bal), 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 font-semibold text-gray-900 dark:text-white">
                        <td>TOTAL</td>
                        <td>{{ collect($report)->sum('breakfast_meals') }}</td>
                        <td>{{ collect($report)->sum('lunch_meals') }}</td>
                        <td>{{ collect($report)->sum('dinner_meals') }}</td>
                        <td>{{ number_format(collect($report)->sum('total_meals'), 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format(collect($report)->sum('total_cost'), 2) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format(collect($report)->sum('bazar_contribution'), 2) }}</td>
                        <td>—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @elseif(isset($userId))
    <div class="card">
        <div class="card-body text-center py-12">
            <div class="text-5xl mb-3">📋</div>
            <p class="text-gray-500 dark:text-gray-400">No data available for the selected user and year</p>
        </div>
    </div>
    @endif
</div>
@endsection
