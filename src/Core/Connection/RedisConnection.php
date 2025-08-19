<?php

declare(strict_types=1);

namespace RedisService\Core\Connection;

class RedisConnection implements RedisConnectionInterface
{
    private \Redis $client;
    private bool $connected = false;

    public function __construct(string $dsn)
    {
        $this->client = new \Redis();

        $parts = parse_url($dsn) ?: [];
        if (empty($parts['host']) || empty($parts['port'])) {
            throw new \RedisException('Redis DSN is empty');
        }

        $this->connected = $this->client->connect($parts['host'], $parts['port']);

        if (!empty($parts['pass'])) {
            $this->client->auth($parts['pass']);
        }

        $db = isset($parts['path']) ? (int) ltrim($parts['path'], '/') : 0;
        if ($db) {
            $this->client->select($db);
        }
        unset($parts, $db);
    }

    public function getClient(): \Redis
    {
        return $this->client;
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
}
