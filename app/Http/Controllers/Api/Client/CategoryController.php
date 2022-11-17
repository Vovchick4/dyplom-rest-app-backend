<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{

    /**
     * Get list of categories for client.
     *
     * @OA\Get(
     *   path="/api/client/restaurants/{restaurant_id}/categories",
     *   description="Get list of categories for client",
     *   tags={"Client Categories"},
     *   @OA\Parameter(
     *     name="restaurant_id",
     *     in="path",
     *     required=true,
     *     description="Show category for client where restaurant_id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="parent_id",
     *     in="query",
     *     required=false,
     *     description="List categories where parent_id",
     *     example="15",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of categories",
     *     @OA\JsonContent(ref="#/components/schemas/ClientCategorySchema"),
     *   ),
     * )
     *
     * @param Request $request
     * @param int $restaurantId
     * @return JsonResponse
     */
    public function index(Request $request, int $restaurantId): JsonResponse
    {
        $categories = Category::where('restaurant_id', $restaurantId)
            ->active()
            ->get();

        $data = CategoryResource::collection($categories);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }

    /**
     * Show category item for client.
     *
     * @OA\Get(
     *   path="/api/client/restaurants/{restaurant_id}/categories/{id}",
     *   description="Show category item for client",
     *   tags={"Client Categories"},
     *   @OA\Parameter(
     *     name="restaurant_id",
     *     in="path",
     *     required=true,
     *     description="Show category for client where restaurant_id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show category for client where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show category item for client.",
     *     @OA\JsonContent(ref="#/components/schemas/ClientCategorySchema"),
     *   ),
     * )
     * @param Request $request
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function show(Request $request, Category $category): JsonResponse
    {
        $data = new CategoryResource($category);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }
}
