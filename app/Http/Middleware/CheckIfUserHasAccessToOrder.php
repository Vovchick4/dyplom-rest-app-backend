<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserHasAccessToOrder
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
        $user = $request->user();
        $order = $request->order;

        if ($user->restaurant_id != $order->restaurant_id && Auth::user()->role != 'super-admin')
            return response()->json(['status' => 403, 'message' => 'Not allowed', 'data' => []], 403);

        return $next($request);
    }
}
