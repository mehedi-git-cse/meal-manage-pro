@extends('layouts.app')
@section('page-title', 'Bazar Entries')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Bazar Management</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Bazar</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('bazar.categories') }}" class="btn-secondary">Categories</a>
            <a href="{{ route('bazar.vendors') }}" class="btn-secondary">Vendors</a>
            <a href="{{ route('bazar.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Entry
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">This Month Total</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($monthlyTotal, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">My Contribution</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                {{ config('meal.currency_symbol') }}{{ number_format($myMonthlyTotal, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Entries This Month</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $entries->total() }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('bazar.index') }}" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-input text-sm col-span-2 md:col-span-1" placeholder="Search items...">

                <select name="user_id" class="form-select text-sm">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ encryptId($user->id) }}" {{ request('user_id') == encryptId($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>

                <select name="category_id" class="form-select text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ encryptId($cat->id) }}" {{ request('category_id') == encryptId($cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <input type="month" name="month" value="{{ request('month') }}" class="form-input text-sm">

                <div class="flex gap-2 col-span-2 md:col-span-1">
                    <button type="submit" class="btn-primary flex-1">Filter</button>
                    <a href="{{ route('bazar.index') }}" class="btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Expense Chart -->
    @if($categoryExpenses->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Expense by Category (This Month)</h3>
        </div>
        <div class="card-body">
            <div id="categoryChart"></div>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">All Entries ({{ $entries->total() }})</h3>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Verified</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td class="text-sm text-gray-700 dark:text-gray-300">{{ $entry->entry_date->format('d M, Y') }}</td>
                        <td>
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ $entry->item_name }}</div>
                            @if($entry->description)
                            <div class="text-xs text-gray-400 truncate max-w-[200px]">{{ $entry->description }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="{{ $entry->user->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entry->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($entry->category)
                                <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs">
                                    {{ $entry->category->icon ?? '' }} {{ $entry->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="font-semibold text-gray-900 dark:text-white">
                            {{ config('meal.currency_symbol') }}{{ number_format($entry->amount, 2) }}
                        </td>
                        <td>
                            @if($entry->is_verified)
                                <span class="badge-success">Verified</span>
                            @else
                                <span class="badge-warning">Unverified</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('bazar.show', $entry) }}" class="btn btn-sm btn-secondary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @role('super_admin|manager')
                                @if(!$entry->is_verified)
                                <form method="POST" action="{{ route('bazar.verify', $entry) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 dark:border-emerald-800" title="Verify">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                                @endrole
                                <a href="{{ route('bazar.edit', $entry) }}" class="btn btn-sm btn-secondary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="del-bazar-{{ encryptId($entry->id) }}" method="POST" action="{{ route('bazar.destroy', $entry) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('del-bazar-{{ encryptId($entry->id) }}')" class="btn btn-sm btn-danger">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="text-5xl mb-3">🛒</div>
                            <p class="text-gray-500 dark:text-gray-400">No bazar entries found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($entries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $entries->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($categoryExpenses->count() > 0)
new ApexCharts(document.getElementById('categoryChart'), {
    chart: { type: 'bar', height: 200, toolbar: { show: false } },
    series: [{ name: 'Amount', data: @json($categoryExpenses->pluck('total').toArray()) }],
    xaxis: { categories: @json($categoryExpenses->pluck('name')->toArray()) },
    colors: ['#3b82f6'],
    dataLabels: { enabled: false },
    grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
    tooltip: { y: { formatter: val => '{{ config('meal.currency_symbol') }}' + val.toLocaleString() } },
}).render();
@endif
</script>
@endpush
