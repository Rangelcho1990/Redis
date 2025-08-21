<?php

declare(strict_types=1);

namespace ExampleLib;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/redis-core.php', 'redis-core');

        // Resolve & bind your Core using Laravel's Redis connection (no adapter).
        $this->app->singleton('redis.core', static function (Container $app) {
            /** @var ConfigRepository $config */
            $config = $app->make(ConfigRepository::class);

            $coreClass = self::readCoreClass($config);
            $connection = self::readString($config->get('redis-core.connection'), 'default');
            $constructor = self::readString($config->get('redis-core.constructor'), 'client_config');
            $options = self::readArray($config->get('redis-core.options'));

            /** @var RedisFactory $redisFactory */
            $redisFactory = $app->make(RedisFactory::class);

            $conn = $redisFactory->connection($connection);
            $client = self::extractNativeClient($conn);

            return match ($constructor) {
                'client_config' => new $coreClass($client, $options),
                'client_only' => new $coreClass($client),
                'config_only' => new $coreClass($options),
                'none' => new $coreClass(),
                default => throw new \RuntimeException("Unsupported redis-core.constructor '{$constructor}'"),
            };
        });

        // Alias so controllers can type-hint your Core class (same class Symfony uses).
        /** @var ConfigRepository $config */
        $config = $this->app->make(ConfigRepository::class);
        $fqcn = self::optionalCoreClass($config);
        if (null !== $fqcn) {
            $this->app->alias('redis.core', $fqcn);
        }

        // Optional interface alias
        $bindTo = $config->get('redis-core.bind_to');
        if (is_string($bindTo) && interface_exists($bindTo)) {
            $this->app->alias('redis.core', $bindTo);
        }
    }

    public function boot(): void
    {
        // Publish only in console to avoid extra work in web requests.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Config/redis-core.php' => $this->app->configPath('redis-core.php'),
            ], 'redis-core-config');
        }
    }

    /**
     * Read a required Core class FQCN from config and validate it.
     *
     * @return class-string
     *
     * @throws \RuntimeException if invalid
     */
    private static function readCoreClass(ConfigRepository $config): string
    {
        $val = $config->get('redis-core.core_class');
        if (!is_string($val) || '' === $val || !class_exists($val)) {
            throw new \RuntimeException('redis-core.core_class must be set to a valid Core class from src/Core');
        }

        /* @var class-string $val */
        return $val;
    }

    /**
     * Read an optional Core class FQCN; return null if invalid/missing.
     *
     * @return class-string|null
     */
    private static function optionalCoreClass(ConfigRepository $config): ?string
    {
        $val = $config->get('redis-core.core_class');
        if (is_string($val) && '' !== $val && class_exists($val)) {
            /* @var class-string $val */
            return $val;
        }

        return null;
    }

    /**
     * Safe string read with fallback.
     */
    private static function readString(mixed $value, string $default): string
    {
        return is_string($value) && '' !== $value ? $value : $default;
    }

    /**
     * Safe array read with fallback.
     *
     * @return array<string, mixed>
     */
    private static function readArray(mixed $value): array
    {
        /** @var array<string, mixed> $out */
        $out = is_array($value) ? $value : [];

        return $out;
    }

    /**
     * Try to extract the native Redis client (\Redis or \Predis\Client) from a Laravel connection.
     * Falls back to returning the connection itself if no public zero-arg client() exists.
     */
    private static function extractNativeClient(mixed $conn): mixed
    {
        if (!is_object($conn)) {
            return $conn;
        }

        try {
            $ref = new \ReflectionObject($conn);
            if ($ref->hasMethod('client')) {
                $m = $ref->getMethod('client');
                if ($m->isPublic() && 0 === $m->getNumberOfRequiredParameters()) {
                    return $m->invoke($conn);
                }
            }
        } catch (\Throwable) {
            // Ignore and fall back to the connection instance
        }

        return $conn;
    }
}
