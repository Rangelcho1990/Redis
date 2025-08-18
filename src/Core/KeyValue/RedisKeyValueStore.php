<?php

declare(strict_types=1);

namespace RedisService\Core\KeyValue;

use RedisException;
use RedisService\Core\Connection\RedisConnectionInterface;
use Redis;

final readonly class RedisKeyValueStore implements KeyValueStoreInterface
{
	private Redis $redisClient;
	private bool $isConnected;

	public function __construct(RedisConnectionInterface $connection)
	{
		$this->redisClient = $connection->getClient();
		$this->isConnected = $connection->isConnected();
	}

	public function get(string $key): string|bool
	{
		if ($this->isConnected === false) {
			// log error;
			return false;
		}

		try {
			return $this->redisClient->get($key);
		} catch (RedisException $e) {
			// log error;
			return false;
		}
	}

	public function set(string $key, mixed $value, ?int $ttl = null): void
	{
		if ($this->isConnected === false) {
			// log error;
			return;
		}

		try {
			$this->redisClient->set($key, json_encode($value), $ttl);
		} catch (RedisException $e) {
			// log error;
			return;
		}
	}

	public function del(string ...$keys): int|bool
	{
		if ($this->isConnected === false) {
			// log error;
			return false;
		}

		try {
			return $this->redisClient->del($keys);
		} catch (RedisException $e) {
			// log error;
			return false;
		}
	}
}
