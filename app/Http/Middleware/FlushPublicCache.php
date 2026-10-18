<?php

namespace App\Http\Middleware;

use App\Support\PublicCache;
use Closure;
use Illuminate\Http\Request;

class FlushPublicCache
{
    // After any successful POST/PUT/PATCH/DELETE, clear the public cache
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $request->isMethodSafe() && $response->getStatusCode() < 400) {
            PublicCache::flush();
        }

        return $response;
    }
}
