<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\PlateIndexRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Plate;
use App\Http\Resources\PlateResource;
use App\Http\Resources\PlateCollection;

class PlateController extends Controller
{
    /**
     * Get list of plates.
     *
     * @OA\Get(
     *   path="/api/client/restaurants/{restaurant_id}/plates",
     *   description="Get list of plates",
     *   tags={"Client Plates"},
     *   @OA\Parameter(
     *     name="restaurant_id",
     *     in="path",
     *     required=true,
     *     description="List plates where restaurant_id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="category_id",
     *     in="query",
     *     required=false,
     *     description="List plates where category_id",
     *     example="25",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     description="Current page number",
     *     example="1",
     *     @OA\Schema(
     *     type="number",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     required=false,
     *     description="Items per page",
     *     example="1",
     *     @OA\Schema(
     *     type="number",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of plates",
     *     @OA\JsonContent(ref="#/components/schemas/ClientPlateSchema"),
     *   ),
     * )
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PlateIndexRequest $request, int $restaurantId)
    {
        $perPage = (int) request('per_page', 9);
        $plates = Plate::where('plates.restaurant_id', $restaurantId)
            ->active()
            ->when(request('category_id'), function ($query) {
                $query->where('plates.category_id', request('category_id'));
            })
            ->paginate($perPage);

        $resource = new PlateCollection($plates);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');

        return response()->json($data);
    }

    /**
     * Show plate item.
     *
     * @OA\Get(
     *   path="/api/client/plates/{id}",
     *   description="Show plate item",
     *   tags={"Client Plates"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show plate where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show plate item.",
     *     @OA\JsonContent(ref="#/components/schemas/ClientPlateSchema"),
     *   ),
     * )
     * @param Request $request
     * @param Plate $plate
     *
     * @return JsonResponse
     */
    public function show(Request $request, Plate $plate): JsonResponse
    {
        $data = new PlateResource($plate);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }


    /**
     * Get list of plates.
     *
     * @OA\Get(
     *   path="/api/client/restaurants/{restaurant_id}/plates/search/{searchText}",
     *   description="Get list of client orders",
     *   tags={"Client Orders"},
     *   @OA\Parameter(
     *     name="restaurant_id",
     *     in="path",
     *     required=true,
     *     description="List plates where restaurant_id",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *     example="34",
     *   ),
     *   @OA\Parameter(
     *     name="searchText",
     *     in="path",
     *     required=true,
     *     description="List plates where searchText",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     example="fish",
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of plates",
     *     @OA\JsonContent(ref="#/components/schemas/ClientPlateSchema"),
     *   ),
     * )
     *
     * @param Request $request
     * @param int $restaurantId
     * @param string $searchText
     * @return JsonResponse
     */
    public function search(Request $request, int $restaurantId, string $searchText): JsonResponse
    {
        $plates = Plate::select('plates.*')
            ->distinct('plates.id')
            ->join('categories', 'categories.id', '=', 'plates.category_id')
            ->where('plates.restaurant_id', $restaurantId)
            ->active()
            ->whereHas('translations', function ($query) use ($searchText) {
                $query->where('name', 'like', "%$searchText%");
            })
            ->get();

        $data = PlateResource::collection($plates);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }
}
