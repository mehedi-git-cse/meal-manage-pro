<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasEncryptedRouteKey;

class Deposit extends Model
{
    use HasFactory, SoftDeletes, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'amount',
        'deposit_date',
        'note',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'deposit_date' => 'date',
            'amount'       => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('deposit_date', now()->year)
                     ->whereMonth('deposit_date', now()->month);
    }

    public function scopeInMonth($query, int $year, int $month)
    {
        return $query->whereYear('deposit_date', $year)
                     ->whereMonth('deposit_date', $month);
    }
}
