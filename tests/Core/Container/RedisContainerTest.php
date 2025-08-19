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

    public function testCheckRedisConnectionFail(): void
    {
        $redisConnection = new RedisConnection('redis://127.0.0.1:63');
        $redisContainer = new RedisContainer($redisConnection);

//		$this->assertFalse($redisContainer->isConnected(), 'RedisException: Connection refused');
//      $this->expectException(\RedisException::class)
//		$this->expectExceptionMessage('RedisException: Redis DSN is empty');
    }
}
