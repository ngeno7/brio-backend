<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!$request->bearerToken()) {

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if(!Client::where('token', $request->bearerToken())->first()) {

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
