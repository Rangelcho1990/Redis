<?php

declare(strict_types=1);

namespace RedisService\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder(): TreeBuilder
	{
		$tree = new TreeBuilder('redis_service');
		$root = $tree->getRootNode();

		$root
			->children()
				->scalarNode('dsn')
					->info('Redis DSN, e.g. redis://127.0.0.1:6379/0')
					->defaultValue('redis://127.0.0.1:6379')
				->end()
				->enumNode('driver')
					->info('"ext-redis" (PHP extension)')
					->values(['ext-redis'])
					->defaultValue('ext-redis')
				->end()
			->end();

		return $tree;
	}
}
