<?php

declare(strict_types=1);

namespace RedisService\Tests\Symfony\DependencyInjection;

use PHPUnit\Framework\TestCase;
use RedisService\Core\Connection\RedisConnection;
use RedisService\Core\Connection\RedisConnectionInterface;
use RedisService\Core\Container\RedisContainer;
use RedisService\Core\Container\RedisContainerInterface;
use RedisService\Symfony\DependencyInjection\RedisServiceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RedisServiceExtensionTest extends TestCase
{
    /**
     * @param array<string, string> $config
     */
    private function buildContainer(array $config = []): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $extension = new RedisServiceExtension();
        $extension->load([$config], $container);
        $container->compile();

        return $container;
    }

    public function testRedisServiceExtensionLoad(): void
    {
        $container = $this->buildContainer([
            'dsn' => 'redis://127.0.0.1:6379',
            'driver' => 'ext-redis',
        ]);

        $this->assertTrue($container->has(RedisConnectionInterface::class));

        $connection = $container->get(RedisConnectionInterface::class);
        $this->assertInstanceOf(RedisConnection::class, $connection);

        // RedisContainerInterface store should be bound to the interface
        $this->assertTrue($container->has(RedisContainerInterface::class));
        $redisContainer = $container->get(RedisContainerInterface::class);
        $this->assertInstanceOf(RedisContainer::class, $redisContainer);
    }

    public function testRedisConnectionInstance(): void
    {
        $container = $this->buildContainer();

        $connection = $container->get(RedisConnectionInterface::class);
        $this->assertInstanceOf(
            RedisConnection::class,
            $connection,
            'By default the driver should be ext-redis'
        );
    }
}
