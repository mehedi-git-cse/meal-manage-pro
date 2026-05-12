<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasEncryptedRouteKey;

class BazarVendor extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'contact_person',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bazarEntries(): HasMany
    {
        return $this->hasMany(BazarEntry::class, 'vendor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function totalSpent(): float
    {
        return $this->bazarEntries()->sum('amount');
    }
}
