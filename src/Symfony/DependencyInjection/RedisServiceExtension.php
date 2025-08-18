<?php

declare(strict_types=1);

namespace RedisService\Symfony\DependencyInjection;

use RedisService\Core\Connection\RedisConnectionInterface;
use RedisService\Core\ExtRedisConnection;
use RedisService\Core\KeyValue\KeyValueStoreInterface;
use RedisService\Core\KeyValue\RedisKeyValueStore;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class RedisServiceExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container): void
	{
		$config = $this->processConfiguration(new Configuration(), $configs);
		$dsn    = $config['dsn']    ?? 'redis://127.0.0.1:6379';

		$connClass = ExtRedisConnection::class;

		// Connection service
		$connDef = (new Definition($connClass)) ->setArguments([$dsn]);
		$container->setDefinition($connClass, $connDef);

		// Alias the interface to the chosen implementation
		$container->setAlias(RedisConnectionInterface::class, $connClass)->setPublic(true);

		// KeyValue store service
		$storeDef = (new Definition(RedisKeyValueStore::class))
			->setArguments([new Reference(RedisConnectionInterface::class)]);
		$container->setDefinition(RedisKeyValueStore::class, $storeDef);

		// Alias the store interface to the concrete implementation
		$container->setAlias(KeyValueStoreInterface::class, RedisKeyValueStore::class)->setPublic(true);
	}
}
