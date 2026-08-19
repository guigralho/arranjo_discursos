<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    /**
     * @var array<string, mixed>
     */
    protected static array $memo = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::$memo[$key] ??= static::query()->find($key)?->value ?? $default;
    }

    /**
     * Clear the in-memory cache; needed when a value changes within the same process (tests).
     */
    public static function flush(): void
    {
        static::$memo = [];
    }
}
