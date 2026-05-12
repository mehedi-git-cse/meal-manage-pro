<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasEncryptedRouteKey;

class BazarEntry extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasEncryptedRouteKey;

    protected $fillable = [
        'entry_date',
        'user_id',
        'category_id',
        'vendor_id',
        'item_name',
        'amount',
        'unit',
        'quantity',
        'unit_price',
        'receipt_number',
        'receipt_image',
        'description',
        'is_verified',
        'verified_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->setDescriptionForEvent(fn(string $eventName) => "Bazar entry {$eventName}");
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BazarCategory::class, 'category_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(BazarVendor::class, 'vendor_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('entry_date', $year)->whereMonth('entry_date', $month);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
