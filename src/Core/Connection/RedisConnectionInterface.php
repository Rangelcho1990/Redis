<?php

declare(strict_types=1);

namespace RedisService\Core\Connection;

interface RedisConnectionInterface
{
    /**
     * Return the native client instance (either \Redis or \Predis\Client).
     */
    public function getClient(): \Redis;

    /**
     * True if the underlying client is connected/ready.
     */
    public function isConnected(): bool;
}
