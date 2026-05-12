<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasEncryptedRouteKey;

class MealEntry extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'meal_date',
        'meal_type',
        'meal_rate',
        'quantity',
        'note',
        'is_guest',
        'guest_name',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'meal_date' => 'date',
        'quantity' => 'decimal:2',
        'is_guest' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->setDescriptionForEvent(fn(string $eventName) => "Meal entry {$eventName}");
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('meal_date', $year)->whereMonth('meal_date', $month);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('meal_date', $date);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('meal_type', $type);
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    public function getMealTypeLabelAttribute(): string
    {
        return match($this->meal_type) {
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch',
            'dinner' => 'Dinner',
            'custom' => 'Custom',
            default => ucfirst($this->meal_type),
        };
    }

    public function getMealTypeBadgeColorAttribute(): string
    {
        return match($this->meal_type) {
            'breakfast' => 'bg-yellow-100 text-yellow-800',
            'lunch' => 'bg-blue-100 text-blue-800',
            'dinner' => 'bg-purple-100 text-purple-800',
            'custom' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
