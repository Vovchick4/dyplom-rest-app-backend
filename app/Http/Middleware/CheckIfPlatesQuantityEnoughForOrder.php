<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Plate;

class CheckIfPlatesQuantityEnoughForOrder
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
        $plates = $request->plates;
        $errors = [];

        if (gettype($plates) === 'array') {
            $ids = array_keys($plates);
            $platesCollection = Plate::find($ids);

            foreach ($platesCollection as $plate) {
                if ($plate->quantity === 0)
                    $errors[$plate->id] = __('validation.plate_out_of_stock', ['name' => $plate->name]);
                elseif ($plate->quantity < $plates[$plate->id]['amount'])
                    $errors[$plate->id] = __('validation.plate_not_enough', ['quantity' => $plate->quantity, 'name' => $plate->name]);
            }
        }

        if (count($errors))
            return response()->json(
                [
                    'data' => null,
                    'status' => 422,
                    'message' => 'Unprocessable entity',
                    'errors' => $errors
                ],
                422
            );

        return $next($request);
    }
}
