<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Traits\HasEncryptedRouteKey;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, CausesActivity, HasEncryptedRouteKey;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'employee_id',
        'department',
        'designation',
        'password',
        'avatar',
        'status',
        'meal_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'meal_active' => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function mealEntries(): HasMany
    {
        return $this->hasMany(MealEntry::class);
    }

    public function bazarEntries(): HasMany
    {
        return $this->hasMany(BazarEntry::class);
    }

    public function monthlySummaries(): HasMany
    {
        return $this->hasMany(MonthlyMealSummary::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMealActive($query)
    {
        return $query->where('meal_active', true)->where('status', 'active');
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff&bold=true';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge-success">Active</span>',
            'inactive' => '<span class="badge-warning">Inactive</span>',
            'suspended' => '<span class="badge-danger">Suspended</span>',
            default => '<span class="badge-secondary">Unknown</span>',
        };
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function totalMealsThisMonth(): float
    {
        return $this->mealEntries()
            ->whereYear('meal_date', now()->year)
            ->whereMonth('meal_date', now()->month)
            ->sum('quantity');
    }

    public function totalBazarThisMonth(): float
    {
        return $this->bazarEntries()
            ->whereYear('entry_date', now()->year)
            ->whereMonth('entry_date', now()->month)
            ->sum('amount');
    }
}
