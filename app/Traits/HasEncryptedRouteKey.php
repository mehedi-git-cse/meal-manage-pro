<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasEncryptedRouteKey
{
    /**
     * Return encrypted ID as the route key so URLs never expose plain integers.
     */
    public function getRouteKey(): string
    {
        return encryptId($this->getKey());
    }

    /**
     * The DB column used for lookups is still the primary key.
     */
    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Decrypt the route segment before querying the database.
     */
    public function resolveRouteBinding($value, $field = null): ?static
    {
        return static::findOrFail(decryptId($value));
    }

    /**
     * Decrypt child route bindings too.
     */
    public function resolveChildRouteBinding($childType, $value, $field): ?Model
    {
        return parent::resolveChildRouteBinding($childType, decryptId($value), $field);
    }
}
