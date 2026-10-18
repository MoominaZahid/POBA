<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class PublicCache
{
    // Cached public data; refreshed automatically after any successful write request
    public static function remember(string $key, Closure $callback, int $ttl = 3600)
    {
        $version = Cache::get('public_cache_v', 1);
        return Cache::remember("public:{$version}:{$key}", $ttl, $callback);
    }

    public static function flush(): void
    {
        Cache::forever('public_cache_v', (int) Cache::get('public_cache_v', 1) + 1);
    }
}
