<?php

declare(strict_types=1);

namespace RedisService\Core\Connection;

use RedisService\Core\ExtRedisConnection;
use InvalidArgumentException;

final class RedisConnectionFactory
{
	public static function create(string $dsn, string $driver = 'ext-redis'): RedisConnectionInterface
	{
		return match ($driver) {
			'ext-redis' => new ExtRedisConnection($dsn),
			default     => throw new InvalidArgumentException("Unsupported driver: {$driver}"),
		};
	}
}