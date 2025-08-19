<?php

declare(strict_types=1);

namespace RedisService\Tests\Symfony;

use PHPUnit\Framework\TestCase;
use RedisService\Symfony\DependencyInjection\RedisServiceExtension;
use RedisService\Symfony\RedisServiceBundle;

final class RedisServiceBundleTest extends TestCase
{
    public function testBundleProvidesExtension(): void
    {
        $bundle = new RedisServiceBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(
            RedisServiceExtension::class,
            $extension,
            'Bundle should return its RedisServiceExtension'
        );
    }
}
