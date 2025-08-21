<?php

declare(strict_types=1);

use RedisService\Core\Container\RedisContainer;

return [
    'core_class' => RedisContainer::class, // <-- CHANGE THIS to your real class

    /*
     * How to build the Core class:
     *  - 'client_config': new CoreClass($clientAdapter, $config)
     *  - 'config_only' : new CoreClass($config)
     *  - 'none'        : new CoreClass()
     */
    'constructor' => env('REDIS_CORE_CONSTRUCTOR', 'client_config'),

    // Use Laravel's Redis manager (recommended)
    'use_laravel_redis' => true,

    // Laravel Redis connection name to use
    'connection' => env('REDIS_CORE_CONNECTION', 'default'),

    // Arbitrary options passed as $config when constructor requires it
    'options' => [
        'prefix' => env('REDIS_CORE_PREFIX', 'rcl:'),
        'timeout' => env('REDIS_CORE_TIMEOUT', 1.0),
        // ...add anything your Core class expects inside $config
    ],

    /*
     * Optional: also alias the binding to your Core interface (if you have one)
     * e.g. \Core\Contracts\RedisClientInterface::class
     */
    'bind_to' => null,
];
