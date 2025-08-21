<?php

declare(strict_types=1);

namespace ExampleLib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method string|bool get(string $key)
 * @method void        set(string $key, string $value, array<string, int> $ttl = [])
 * @method int|bool    del(array<string> $keys)
 * @method bool        isConnected()
 */
class RedisCore extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'redis.core';
    }
}
