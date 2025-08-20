<?php

declare(strict_types=1);

namespace RedisService\Tests\Core\Connection;

use PHPUnit\Framework\TestCase;
use RedisService\Core\Connection\RedisConnection;
use RedisService\Core\Connection\RedisConnectionFactory;

class RedisConnectionFactoryTest extends TestCase
{
    public function testRedisConnectionWithDefaultDriver(): void
    {
        $this->assertInstanceOf(RedisConnection::class, RedisConnectionFactory::create(
            'redis://127.0.0.1:6379',
        ));
    }

    public function testRedisConnectionWithPredisDriver(): void
    {
        $driver = 'predis';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported driver: "'.$driver.'" ');

        RedisConnectionFactory::create(
            'redis://127.0.0.1:6379',
            $driver
        );
        unset($driver);
    }
}
