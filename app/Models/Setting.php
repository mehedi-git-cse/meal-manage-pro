<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (!$setting) return $default;

            return match($setting->type) {
                'boolean' => (bool) $setting->value,
                'integer' => (int) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        $setting = static::where('key', $key)->first();

        $storeValue = is_array($value) ? json_encode($value) : (string) $value;

        if ($setting) {
            $setting->update(['value' => $storeValue]);
        } else {
            static::create(['key' => $key, 'value' => $storeValue]);
        }

        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings in a group as key=>value array.
     */
    public static function getGroup(string $group): array
    {
        return Cache::rememberForever("settings_group_{$group}", function () use ($group) {
            return static::where('group', $group)
                ->get()
                ->mapWithKeys(fn($s) => [$s->key => $s->value])
                ->toArray();
        });
    }
}
