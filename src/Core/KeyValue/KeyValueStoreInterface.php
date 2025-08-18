<?php

declare(strict_types=1);

namespace RedisService\Core\KeyValue;

interface KeyValueStoreInterface
{
	public function get(string $key): string|bool;
	public function set(string $key, mixed $value, ?int $ttl = null): void;
	public function del(string ...$keys): int|bool;
}
