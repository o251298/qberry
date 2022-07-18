<?php

namespace App\Services\NoSQL;

use Illuminate\Support\Facades\Redis;

class RedisStore implements NoSQLStoreInterface
{

    public function save(string $key, string $val): void
    {
        Redis::set($key, $val);
    }

    public function get(string $key): string
    {
        return Redis::get($key);
    }

    public function exists(string $key): bool
    {
        return Redis::exists($key);
    }
}
