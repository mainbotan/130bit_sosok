<?php

namespace App\Core;

use Predis\Client;

class RedisCache {
    private Client $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => 'redis', // подключение по имени контейнера
            'port'   => 6379,
        ]);
    }

    public function set(string $key, string $value, int $ttl = 3600): void
    {
        $this->redis->setex($key, $ttl, $value);
    }

    public function get(string $key): ?string
    {
        return $this->redis->get($key);
    }

    public function delete(string $key): void
    {
        $this->redis->del([$key]);
    }

    public function remember(string $key, int $ttl, callable $callback): string
    {
        $cached = $this->get($key);
        if ($cached) {
            return $cached;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }
}
