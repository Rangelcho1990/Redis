<?php

declare(strict_types=1);

namespace RedisService\Tests\Core\Container;

use PHPUnit\Framework\TestCase;
use RedisService\Core\Connection\RedisConnection;
use RedisService\Core\Connection\RedisConnectionInterface;
use RedisService\Core\Container\RedisContainer;

final class RedisContainerTest extends TestCase
{
    private RedisConnectionInterface $redisConnection;

    public function setUp(): void
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('ext-redis not installed');
        }

        $this->redisConnection = $this->getMockBuilder(RedisConnection::class)
            ->setConstructorArgs(['redis://127.0.0.1:6379'])
            ->onlyMethods(['getClient'])
            ->getMock();
        $this->redisConnection->method('getClient')->willReturn(new \Redis());
    }

    public function testGetReturnsValueFromClientSuccess(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $this->assertSame('bar', $redisContainer->get('foo'));
    }

    public function testGetReturnsValueFromClientFail(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $this->assertNotSame('redis_container', $redisContainer->get('redis_container'));
    }

    public function testCheckRedisConnectionTrue(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $this->assertTrue($redisContainer->isConnected(), 'Redis connection success!');
    }

    public function testSetValueToClientSuccess(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $key = $value = 'testSetValue';

        $redisContainer->set($key, $value);
        $redisValue = $redisContainer->get($key);
        if (is_string($redisValue)) {
            $redisValue = json_decode($redisValue, true);
        }

        $this->assertSame($value, $redisValue);
    }

    public function testDeleteValueFromClientSuccess(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $key = $value = 'testSetValue';

        $redisContainer->set($key, $value);
        $redisContainer->del([$key]);

        $this->assertFalse($redisContainer->get($key));
    }

    public function testDeleteValuesFromClientSuccess(): void
    {
        $redisContainer = new RedisContainer($this->redisConnection);
        $key = $value = 'testSetValue';
        $key2 = $value2 = 'redis_container';

        $redisContainer->set($key, $value);
        $redisContainer->set($key2, $value2);

        $redisContainer->del([$key, $key2]);

        $this->assertFalse($redisContainer->get($key));
        $this->assertFalse($redisContainer->get($key2));
    }
}
