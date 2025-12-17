<?php

namespace App\Http\Middleware;

use App\Core\Util;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransformApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Transform keys of successful JSON responses to camelCase
        if ($response->isSuccessful() && $response instanceof JsonResponse) {
            $original = $response->getData(true);
            if ($original) {
                $original = Util::convertKeysToCamelCase($original);
            }
            return response()->json(
                array_merge($original ?? [], [
                    'status' => $response->getStatusCode(),
                ]),
                $response->getStatusCode()
            );
            // return response()->json([
            //     'data' => $original,
            //     'status' => $response->getStatusCode(),
            // ], $response->getStatusCode());
        }

        return $response;
    }
}
