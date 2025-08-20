<?php

declare(strict_types=1);

namespace RedisService\Core\Container;

use RedisService\Core\Connection\RedisConnectionInterface;

final class RedisContainer implements RedisContainerInterface
{
    private \Redis $redisClient;
    private bool $isConnected;

    public function __construct(RedisConnectionInterface $connection)
    {
        $this->redisClient = $connection->getClient();
        $this->isConnected = $connection->isConnected();
    }

    public function get(string $key): string|bool
    {
        if (false === $this->isConnected) {
            // log error;
            return false;
        }

        try {
            $data = $this->redisClient->get($key);
            if (false === is_string($data)) {
                // log error;
                return false;
            }

            return $data;
        } catch (\RedisException $e) {
            // log error;
            return false;
        }
    }

    /**
     * @param array<string, int> $ttl
     */
    public function set(string $key, mixed $value, array $ttl = []): void
    {
        if (false === $this->isConnected) {
            // log error;
            return;
        }

        try {
            $this->redisClient->set($key, json_encode($value), $ttl);
        } catch (\RedisException $e) {
            // log error;
            return;
        }
    }

    /**
     * @param array<string> $keys
     */
    public function del(array $keys): int|bool
    {
        if (false === $this->isConnected) {
            // log error;
            return false;
        }

        try {
            return $this->redisClient->del($keys);
        } catch (\RedisException $e) {
            // log error;
            return false;
        }
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }
}
