<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\Connections\Connection;

class RedisService
{
    private Connection $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    public function setImgPath(string $slug, string $path)
    {
        return $this->redis->set($slug, $path, 'EX', 600);
    }

    public function getImgPath(string $slug)
    {
        return $this->redis->get($slug);
    }
}
