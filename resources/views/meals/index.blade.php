@extends('layouts.app')
@section('page-title', 'Meal Entries')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- ─── Page Header ────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Meal Entries</h2>
            <div class="breadcrumb mt-1">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Meal Entries</span>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @role('admin|manager')
            <button onclick="document.getElementById('bulkModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Bulk Entry
            </button>
            @endrole
            <a href="{{ route('meals.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Entry
            </a>
        </div>
    </div>

    <!-- ─── Summary Cards ───────────────────────────────────────────────── -->
    @php
        $totalQty   = $meals->sum('quantity');
        $totalCost  = $meals->sum(fn($m) => $m->quantity * $m->meal_rate);
        $approvedCount = $meals->where('status','approved')->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Showing Entries</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $meals->total() }}</p>
            <p class="text-xs text-blue-500 mt-1 font-medium">● Total records</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Quantity</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalQty, 1) }}</p>
            <p class="text-xs text-emerald-500 mt-1 font-medium">● Meals on this page</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Cost</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ config('meal.currency_symbol') }}{{ number_format($totalCost, 0) }}</p>
            <p class="text-xs text-amber-500 mt-1 font-medium">● This page</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Approved</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $approvedCount }}</p>
            <p class="text-xs text-green-500 mt-1 font-medium">● On this page</p>
        </div>
    </div>

    <!-- ─── Filters ─────────────────────────────────────────────────────── -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Filter Entries</span>
            @if(request()->hasAny(['meal_type','status','date_from','date_to']))
                <a href="{{ route('meals.index') }}" class="ml-auto text-xs text-red-500 hover:text-red-600 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear filters
                </a>
            @endif
        </div>
        <form method="GET" action="{{ route('meals.index') }}" class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">Meal Type</label>
                    <select name="meal_type" class="form-select text-sm w-full">
                        <option value="">All Types</option>
                        @foreach(config('meal.meal_types') as $key => $label)
                            <option value="{{ $key }}" {{ request('meal_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">Status</label>
                    <select name="status" class="form-select text-sm w-full">
                        <option value="">All Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-sm w-full">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-sm w-full">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ─── Table ───────────────────────────────────────────────────────── -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">All Entries</span>
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold px-2 py-0.5 rounded-full">{{ $meals->total() }}</span>
            </div>
            <p class="text-xs text-gray-400">Showing {{ $meals->firstItem() }}–{{ $meals->lastItem() }} of {{ $meals->total() }}</p>
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block w-full overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-44">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($meals as $meal)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors group">
                        <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $meal->id }}</td>
                        <td class="px-4 py-3 w-44">
                            <div class="flex items-center gap-2 min-w-0">
                                <img src="{{ $meal->user->avatar_url }}" class="w-8 h-8 rounded-full ring-2 ring-white dark:ring-gray-700 shadow-sm flex-shrink-0" alt="">
                                <div class="min-w-0 overflow-hidden">
                                    <div class="font-semibold text-gray-900 dark:text-white text-sm leading-tight truncate">{{ $meal->user->name }}</div>
                                    @if($meal->user->employee_id)
                                    <div class="text-xs text-gray-400 font-mono">{{ $meal->user->employee_id }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $meal->meal_date->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $meal->meal_date->format('D') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $meal->meal_type_badge_color }} text-xs">{{ $meal->meal_type_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-bold text-sm">
                                {{ $meal->quantity }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-600 dark:text-gray-400 font-mono">
                            {{ config('meal.currency_symbol') }}{{ number_format($meal->meal_rate, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                {{ config('meal.currency_symbol') }}{{ number_format($meal->quantity * $meal->meal_rate, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($meal->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Approved
                                </span>
                            @elseif($meal->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('meals.edit', $meal) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors text-xs font-medium"
                                   title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form id="del-{{ encryptId($meal->id) }}" method="POST" action="{{ route('meals.destroy', $meal) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('del-{{ encryptId($meal->id) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors text-xs font-medium"
                                            title="Delete">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-16 text-center">
                            <div class="text-5xl mb-3">🍽</div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No meal entries found</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters or add a new entry</p>
                            <a href="{{ route('meals.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add First Entry
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($meals->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-t-2 border-gray-200 dark:border-gray-600">
                        <td colspan="4" class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Page Total</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-bold text-sm">
                                {{ number_format($totalQty, 1) }}
                            </span>
                        </td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            {{ config('meal.currency_symbol') }}{{ number_format($totalCost, 2) }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Mobile cards -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($meals as $meal)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <img src="{{ $meal->user->avatar_url }}" class="w-10 h-10 rounded-full ring-2 ring-white dark:ring-gray-700 shadow-sm flex-shrink-0" alt="">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $meal->user->name }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $meal->meal_date->format('d M Y, D') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        @if($meal->status === 'approved')
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        @elseif($meal->status === 'pending')
                            <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                        @endif
                        <span class="badge {{ $meal->meal_type_badge_color }} text-xs">{{ $meal->meal_type_label }}</span>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex items-center gap-4 text-sm">
                        <div>
                            <span class="text-xs text-gray-400">Qty</span>
                            <span class="ml-1 font-bold text-gray-900 dark:text-white">{{ $meal->quantity }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400">Rate</span>
                            <span class="ml-1 text-gray-600 dark:text-gray-400 font-mono text-xs">{{ config('meal.currency_symbol') }}{{ number_format($meal->meal_rate, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400">Total</span>
                            <span class="ml-1 font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ config('meal.currency_symbol') }}{{ number_format($meal->quantity * $meal->meal_rate, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('meals.edit', $meal) }}"
                           class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form id="mdel-{{ encryptId($meal->id) }}" method="POST" action="{{ route('meals.destroy', $meal) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('mdel-{{ encryptId($meal->id) }}')"
                                    class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="text-5xl mb-3">🍽</div>
                <p class="text-gray-500 font-medium">No meal entries found</p>
                <a href="{{ route('meals.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg">Add Entry</a>
            </div>
            @endforelse
        </div>

        @if($meals->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-4">
            <p class="text-xs text-gray-400 hidden sm:block">
                Showing <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $meals->firstItem() }}</span>
                to <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $meals->lastItem() }}</span>
                of <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $meals->total() }}</span> results
            </p>
            <div>{{ $meals->withQueryString()->links() }}</div>
        </div>
        @endif
    </div>

</div>

@role('admin|manager')
<!-- ─── Bulk Entry Modal ──────────────────────────────────────────────── -->
<div id="bulkModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-lg shadow-2xl" x-data>
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">📋</div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Bulk Meal Entry</h3>
            </div>
            <button onclick="document.getElementById('bulkModal').classList.add('hidden')"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('meals.bulk') }}" class="p-6 space-y-4" data-loading>
            @csrf
            <div class="form-group">
                <label class="form-label">Meal Date</label>
                <input type="date" name="meal_date" class="form-input" value="{{ today()->format('Y-m-d') }}" max="{{ today()->format('Y-m-d') }}" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Meal Type</label>
                    <select name="meal_type" class="form-select" required>
                        @foreach(config('meal.meal_types') as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-input" value="1" step="0.5" min="0.5" max="3" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Select Members</label>
                <div class="border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                        <label class="flex items-center gap-2 text-sm font-medium text-blue-600 cursor-pointer">
                            <input type="checkbox" id="selectAll" class="rounded"> Select All Members
                        </label>
                    </div>
                    <div class="p-2 max-h-48 overflow-y-auto space-y-1">
                        @foreach($users as $user)
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 px-2 py-1.5 rounded-lg user-checkbox-label transition-colors">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-check rounded">
                            <img src="{{ $user->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $user->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Entries
                </button>
                <button type="button" onclick="document.getElementById('bulkModal').classList.add('hidden')"
                        class="px-4 py-2.5 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endrole

@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.user-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
