<?php

namespace App\Http\Middleware;

use App\Core\Util;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransformApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->query->replace(Util::convertKeysToSnakeCase($request->query()));
        if ($request->post()) {
            $request->replace(Util::convertKeysToSnakeCase($request->post()));
        }
        return $next($request);
    }
}
