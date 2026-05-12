<?php

use Illuminate\Support\Facades\Schedule;
use App\Services\MealService;

Schedule::call(function () {
    // Auto-calculate previous month cost on the 1st of each month
    $prevMonth = now()->subMonth();
    app(MealService::class)->calculateMonthlyCost($prevMonth->year, $prevMonth->month);
})->monthlyOn(1, '00:05');

// Daily digest notification
Schedule::command('meals:send-daily-digest')->dailyAt('08:00');

// Clean old activity logs
Schedule::command('activitylog:clean --days=90')->monthly();
