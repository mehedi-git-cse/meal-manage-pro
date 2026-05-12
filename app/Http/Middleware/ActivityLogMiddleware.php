<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogMiddleware
{
    private array $excludedRoutes = [
        'debugbar.*',
        'horizon.*',
        'livewire.*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log mutating requests for authenticated users
        if (
            $request->user()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
            && !$this->isExcluded($request)
        ) {
            Log::channel('daily')->info('User Action', [
                'user_id' => $request->user()->id,
                'user_name' => $request->user()->name,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'status' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }

    private function isExcluded(Request $request): bool
    {
        foreach ($this->excludedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }
        return false;
    }
}
