<?php

declare(strict_types=1);

namespace RedisService\Core;

use Redis;
use RedisService\Core\Connection\RedisConnectionInterface;

class ExtRedisConnection implements RedisConnectionInterface
{
	private Redis $client;
	private bool $connected = false;

	public function __construct( string $dsn = 'redis://127.0.0.1:6379') {
		$this->client = new Redis();

		$this->connectFromDsn($dsn);
	}

	private function connectFromDsn(string $dsn): void
	{
		$parts = parse_url($dsn) ?: [];
		$host  = $parts['host'] ?? '127.0.0.1';
		$port  = isset($parts['port']) ? (int)$parts['port'] : 6379;
		$pass  = $parts['pass'] ?? null;
		$db    = isset($parts['path']) ? (int)ltrim($parts['path'], '/') : 0;

		$this->client->connect($host, $port);
		if ($pass) { $this->client->auth($pass); }
		if ($db)   { $this->client->select($db); }

		$this->connected = true;
	}

	public function getClient(): Redis
	{
		return $this->client;
	}

	public function isConnected(): bool
	{
		return $this->connected;
	}
}
