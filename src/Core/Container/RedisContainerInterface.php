<?php

declare(strict_types=1);

namespace RedisService\Core\Container;

interface RedisContainerInterface
{
    public function get(string $key): string|bool;

    /**
     * @param array<string, int> $ttl
     */
    public function set(string $key, mixed $value, array $ttl = []): void;

    /**
     * @param array<string> $keys
     */
    public function del(array $keys): int|bool;
}
