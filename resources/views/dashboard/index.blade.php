@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- ─── Welcome Banner ─────────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-blue-200 mt-1">{{ now()->format('l, F j, Y') }} — Here's your meal overview</p>
            </div>
            <div class="hidden md:flex items-center gap-4">
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ number_format($myTodayMeals) }}</div>
                    <div class="text-blue-200 text-sm">My Today's Meals</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ─── Stats Cards ─────────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Today's Meals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">My Today's Meals</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($myTodayMeals, 1) }}</p>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <span class="text-blue-500 font-medium">B:{{ $myDailyBreakdown['breakfast'] }}</span>
                        <span class="text-green-500 font-medium">L:{{ $myDailyBreakdown['lunch'] }}</span>
                        <span class="text-purple-500 font-medium">D:{{ $myDailyBreakdown['dinner'] }}</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">🍽</div>
            </div>
        </div>

        <!-- Month Meals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">My Month Meals</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($myMonthMeals, 1) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">📊</div>
            </div>
        </div>

        <!-- Month Bazar Cost -->
        {{-- <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Month Bazar Cost</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ config('meal.currency_symbol') }}{{ number_format($stats['month_bazar'], 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">🛒</div>
            </div>
        </div> --}}

        <!-- Active Members -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Active Members</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $stats['active_users'] }}</p>
                    <p class="text-xs text-emerald-500 mt-1 font-medium">● Meal Active</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">👥</div>
            </div>
        </div>

        <!-- My Deposit -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">My Deposit (Month)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ config('meal.currency_symbol') }}{{ number_format($myMonthDeposit, 0) }}</p>
                    <p class="text-xs mt-1">
                        <a href="{{ route('deposits.index') }}" class="text-emerald-500 hover:underline font-medium">View all →</a>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">💰</div>
            </div>
        </div>

        <!-- My Meal Cost -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">My Meal Cost (Month)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ config('meal.currency_symbol') }}{{ number_format($myMonthMealCost, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">🍱</div>
            </div>
        </div>

        <!-- Remaining Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Remaining Balance</p>
                    <p class="text-2xl font-bold mt-0.5 {{ $myBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                        {{ config('meal.currency_symbol') }}{{ number_format(abs($myBalance), 0) }}
                    </p>
                    <p class="text-xs mt-1 {{ $myBalance >= 0 ? 'text-emerald-500' : 'text-red-400' }} font-medium">
                        {{ $myBalance >= 0 ? '● Surplus' : '● Due' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg {{ $myBalance >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400' }} flex items-center justify-center text-lg group-hover:scale-110 transition-transform">⚖️</div>
            </div>
        </div>
    </div>

    <!-- ─── Charts Row ───────────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Monthly Trend Chart -->
        <div class="card lg:col-span-2">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Monthly Meal Trend</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $year }} overview</p>
                </div>
                <select id="trendYear" onchange="window.location.href='?year='+this.value" class="form-select text-sm w-28">
                    @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <div id="mealTrendChart"></div>
            </div>
        </div>

        <!-- Meal Type Breakdown -->
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900 dark:text-white">My Today's Breakdown</h3>
            </div>
            <div class="card-body">
                <div id="mealTypeChart"></div>
                <div class="space-y-3 mt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> Breakfast</span>
                        <span class="font-semibold">{{ $myDailyBreakdown['breakfast'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Lunch</span>
                        <span class="font-semibold">{{ $myDailyBreakdown['lunch'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span> Dinner</span>
                        <span class="font-semibold">{{ $myDailyBreakdown['dinner'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Tables Row ───────────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Recent Meals -->
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recent Meal Entries</h3>
                <a href="{{ route('meals.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMeals as $meal)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <img src="{{ $meal->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                    <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $meal->user->name }}</span>
                                </div>
                            </td>
                            <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $meal->meal_date->format('d M') }}</td>
                            <td>
                                <span class="badge {{ $meal->meal_type_badge_color }} text-xs">{{ $meal->meal_type_label }}</span>
                            </td>
                            <td class="font-semibold text-gray-900 dark:text-white">{{ $meal->quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400">
                                <div class="text-4xl mb-2">🍽</div>
                                <div class="text-sm">No meal entries yet</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Consumers & Monthly Stats -->
        <div class="space-y-5">
            <!-- Top Consumers -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Top Consumers This Month</h3>
                </div>
                <div class="card-body space-y-3">
                    @forelse($topConsumers as $index => $consumer)
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-bold text-gray-300 w-6">{{ $index + 1 }}</span>
                        <img src="{{ $consumer->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $consumer->user->name }}</div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                <div class="bg-blue-600 h-1.5 rounded-full"
                                     style="width: {{ $topConsumers->max('total_meals') > 0 ? ($consumer->total_meals / $topConsumers->max('total_meals') * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($consumer->total_meals, 1) }}</span>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 text-sm py-4">No data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Bazar -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Recent Bazar Entries</h3>
                    <a href="{{ route('bazar.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <div class="card-body space-y-3">
                    @forelse($recentBazar as $entry)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg {{ $entry->category?->color ? '' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center text-sm">
                                {{ $entry->category?->icon ?? '🛒' }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry->item_name }}</div>
                                <div class="text-xs text-gray-400">{{ $entry->user->name }} • {{ $entry->entry_date->format('d M') }}</div>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ config('meal.currency_symbol') }}{{ number_format($entry->amount, 0) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 text-sm py-4">No bazar entries</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Monthly Expense Summary Table ─────────────────────────────── -->
    @if($monthlyStats->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Monthly Cost Summary</h3>
            <a href="{{ route('costs.index') }}" class="text-sm text-blue-600 hover:underline">Manage</a>
        </div>
        <div class="table-wrapper rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Meals</th>
                        <th>Bazar Cost</th>
                        <th>Cost/Meal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyStats as $stat)
                    <tr>
                        <td class="font-medium text-gray-900 dark:text-white">{{ $stat->month_year }}</td>
                        <td>{{ number_format($stat->total_meals, 1) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($stat->total_bazar_cost, 0) }}</td>
                        <td>{{ config('meal.currency_symbol') }}{{ number_format($stat->cost_per_meal, 2) }}</td>
                        <td>
                            @if($stat->is_finalized)
                                <span class="badge-success">Finalized</span>
                            @else
                                <span class="badge-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Monthly Trend Chart
const trendData = @json($stats['monthly_trend']);

const trendChart = new ApexCharts(document.getElementById('mealTrendChart'), {
    chart: {
        type: 'area',
        height: 250,
        toolbar: { show: false },
        sparkline: { enabled: false },
        animations: { enabled: true, speed: 400 },
    },
    series: [{
        name: 'Meals',
        data: trendData.map(d => d.count),
    }],
    xaxis: {
        categories: trendData.map(d => d.month),
        labels: { style: { fontSize: '12px' } },
    },
    colors: ['#3b82f6'],
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
    },
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: val => val + ' meals' } },
    grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
});
trendChart.render();

// Meal Type Donut Chart
const breakdown = {
    breakfast: {{ $myDailyBreakdown['breakfast'] }},
    lunch: {{ $myDailyBreakdown['lunch'] }},
    dinner: {{ $myDailyBreakdown['dinner'] }},
};

const donutChart = new ApexCharts(document.getElementById('mealTypeChart'), {
    chart: { type: 'donut', height: 180, toolbar: { show: false } },
    series: [breakdown.breakfast, breakdown.lunch, breakdown.dinner],
    labels: ['Breakfast', 'Lunch', 'Dinner'],
    colors: ['#fbbf24', '#3b82f6', '#8b5cf6'],
    legend: { show: false },
    plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'Total', formatter: () => breakdown.breakfast + breakdown.lunch + breakdown.dinner } } } } },
    dataLabels: { enabled: false },
});
donutChart.render();
</script>
@endpush
