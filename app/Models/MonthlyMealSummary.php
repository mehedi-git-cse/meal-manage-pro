<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyMealSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_cost_id',
        'year',
        'month',
        'total_meals',
        'breakfast_count',
        'lunch_count',
        'dinner_count',
        'total_cost',
        'bazar_contribution',
        'balance',
    ];

    protected $casts = [
        'total_meals' => 'decimal:2',
        'breakfast_count' => 'decimal:2',
        'lunch_count' => 'decimal:2',
        'dinner_count' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'bazar_contribution' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mealCost(): BelongsTo
    {
        return $this->belongsTo(MealCost::class);
    }
}
