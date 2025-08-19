<?php

declare(strict_types=1);

namespace RedisService\Symfony\DependencyInjection;

use RedisService\Core\Connection\RedisConnection;
use RedisService\Core\Connection\RedisConnectionInterface;
use RedisService\Core\Container\RedisContainer;
use RedisService\Core\Container\RedisContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class RedisServiceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new RedisConfiguration(), $configs);
        $dsn = $config['dsn'] ?? 'redis://127.0.0.1:6379';

        $connClass = RedisConnection::class;

        // Connection service
        $connDef = (new Definition($connClass))->setArguments([$dsn]);
        $container->setDefinition($connClass, $connDef);

        // Alias the interface to the chosen implementation
        $container->setAlias(RedisConnectionInterface::class, $connClass)->setPublic(true);

        // KeyValue store service
        $storeDef = (new Definition(RedisContainer::class))
            ->setArguments([new Reference(RedisConnectionInterface::class)]);
        $container->setDefinition(RedisContainer::class, $storeDef);

        // Alias the store interface to the concrete implementation
        $container->setAlias(RedisContainerInterface::class, RedisContainer::class)->setPublic(true);
    }
}
