<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasEncryptedRouteKey;

class MealCost extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'year',
        'month',
        'total_bazar_cost',
        'total_meals',
        'cost_per_meal',
        'meal_rate',
        'notes',
        'is_finalized',
        'finalized_by',
        'finalized_at',
    ];

    protected $casts = [
        'total_bazar_cost' => 'decimal:2',
        'total_meals' => 'decimal:2',
        'cost_per_meal' => 'decimal:2',
        'meal_rate' => 'decimal:2',
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(MonthlyMealSummary::class);
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getMonthYearAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    public function scopeByYear($query, int $year)
    {
        return $query->where('year', $year);
    }
}
