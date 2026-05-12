@extends('layouts.app')
@section('page-title', 'Deposits')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Deposits</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Deposits</span>
            </div>
        </div>
        <a href="{{ route('deposits.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Deposit
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">My Deposit (This Month)</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($myMonthTotal, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
        </div>
        @if($isAdmin)
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Deposits (This Month)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($monthTotal, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('deposits.index') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select w-28">
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select w-36">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                @if($isAdmin && $users->count())
                <div>
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select w-44">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ encryptId($u->id) }}" {{ request('user_id') == encryptId($u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary">Filter</button>
                    <a href="{{ route('deposits.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($deposits->isEmpty())
                <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium">No deposits found for this period.</p>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if($isAdmin)<th>User</th>@endif
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Recorded By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $i => $deposit)
                        <tr>
                            <td class="text-gray-400">{{ $deposits->firstItem() + $i }}</td>
                            @if($isAdmin)
                            <td>
                                <div class="flex items-center gap-2">
                                    <img src="{{ $deposit->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                    <span class="font-medium text-sm">{{ $deposit->user->name }}</span>
                                </div>
                            </td>
                            @endif
                            <td>{{ $deposit->deposit_date->format('d M Y') }}</td>
                            <td>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                                    {{ config('meal.currency_symbol') }}{{ number_format($deposit->amount, 2) }}
                                </span>
                            </td>
                            <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $deposit->note ?? '—' }}</td>
                            <td class="text-sm text-gray-500">{{ $deposit->recorder?->name ?? '—' }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('deposits.edit', encryptId($deposit->id)) }}"
                                       class="btn-sm btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('deposits.destroy', encryptId($deposit->id)) }}"
                                          onsubmit="return confirm('Delete this deposit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $deposits->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
