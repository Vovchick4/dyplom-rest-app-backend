<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Show restaurant item.
     *
     * @OA\Get(
     *   path="/api/client/restaurants/{slug}",
     *   description="Show restaurant item",
     *   tags={"Restaurants"},
     *   @OA\Parameter(
     *     name="slug",
     *     in="path",
     *     required=true,
     *     description="Show restaurant where slug",
     *     example="sequi",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show restaurant item.",
     *     @OA\JsonContent(ref="#/components/schemas/RestaurantSchema"),
     *   ),
     * )
     * @param Restaurant $restaurant
     *
     * @return JsonResponse
     */
    public function show(Request $request, Restaurant $restaurant)
    {
        $data = new RestaurantResource($restaurant);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }
}
