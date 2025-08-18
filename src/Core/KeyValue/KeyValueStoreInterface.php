<?php

declare(strict_types=1);

namespace RedisService\Core\KeyValue;

interface KeyValueStoreInterface
{
    public function get(string $key): string|bool;

    /**
     * @param array<string, int> $ttl
     */
    public function set(string $key, mixed $value, array $ttl = []): void;

    public function del(string ...$keys): int|bool;
}
