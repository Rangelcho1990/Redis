<?php

declare(strict_types=1);

namespace RedisService\Core\Connection;

final class RedisConnectionFactory
{
    public static function create(string $dsn, string $driver = 'ext-redis'): RedisConnectionInterface
    {
        return match ($driver) {
            'ext-redis' => new RedisConnection($dsn),
            default => throw new \InvalidArgumentException("Unsupported driver: {$driver}"),
        };
    }
}
