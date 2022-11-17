<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClientStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty(auth()->user()->verified_at)) {
            return $next($request);
        }

        return response()->json(['status' => 403, 'message' => __('validation.account_not_verified'), 'data' => []], 403);
    }
}
