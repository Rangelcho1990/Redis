<?php

declare(strict_types=1);

namespace RedisService\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RedisServiceBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
