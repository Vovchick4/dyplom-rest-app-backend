<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfCategoryIsActive
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
        if (!$request->category || !$request->category->active)
            return response()->json(['status' => 404, 'message' => 'Not found', 'data' => []], 404);;

        return $next($request);
    }
}
