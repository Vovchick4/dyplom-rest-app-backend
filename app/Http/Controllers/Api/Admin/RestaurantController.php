<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\RestaurantStoreRequest;
use App\Http\Requests\Api\Admin\RestaurantUpdateRequest;
use App\Http\Resources\RestaurantCollection;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Get list of restaurants.
     *
     * @OA\Get(
     *   path="/api/admin/restaurants",
     *   description="Get list of restaurants. Authorization: accessToken;",
     *   tags={"Admin Restaurants"},
     *   security={{"passport":{}}},
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
     *     example="10",
     *     @OA\Schema(
     *     type="number",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of restaurants",
     *   ),
     * )
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int)request('per_page', 9);

        $restaurants = Restaurant::latest()->paginate($perPage);

        $resource = new RestaurantCollection($restaurants);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');

        return response()->json($data);
    }

    /**
     * Show restaurant item.
     *
     * @OA\Get(
     *   path="/api/admin/restaurants/{id}",
     *   description="Show restaurant item. Authorization: accessToken;",
     *   tags={"Admin Restaurants"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show restaurant where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show restaurant item.",
     *   ),
     * )
     * @param Request $request
     * @param Restaurant $restaurant
     *
     * @return JsonResponse
     */
    public function show(Request $request, Restaurant $restaurant): JsonResponse
    {
        $data = new RestaurantResource($restaurant);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }

    /**
     * Create new restaurant.
     *
     * @OA\Post(
     *   path="/api/admin/restaurants",
     *   description="Add restaurant. Uses to add new restaurant. Authorization: accessToken;",
     *   tags={"Admin Restaurants"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Create new restaurant",
     *   ),
     * )
     * @param RestaurantStoreRequest $request
     * @return JsonResponse
     */
    public function store(RestaurantStoreRequest $request): JsonResponse
    {
        $attributes = $request->all();

        // upload image
        $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
        $ext = $attributes['logo']->getClientOriginalExtension();
        $path = $attributes['logo']->storeAs($folder, uniqid() . '.' . $ext, 'images');
        $attributes['logo'] = $path;

        $restaurant = Restaurant::create($attributes);

        $data = new RestaurantResource($restaurant);

        return response()->json(['data' => $data, 'status' => 201, 'message' => __('messages.plate_created')], 201);
    }

    /**
     *
     * Update restaurant item.
     *
     * @OA\Post(
     *   path="/api/admin/restaurants/{id}",
     *   description="Update restaurant item; Authorization: accessToken;",
     *   tags={"Admin Restaurants"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Update restaurant where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="_method",
     *     in="path",
     *     required=true,
     *     description="Actual method",
     *     example="PATCH",
     *     @OA\Schema(
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update plate item.",
     *   ),
     * )
     * @param RestaurantUpdateRequest $request
     * @param Restaurant $restaurant
     *
     * @return JsonResponse
     */
    public function update(RestaurantUpdateRequest $request, Restaurant $restaurant): JsonResponse
    {
        $attributes = $request->all();

        if (isset($attributes['logo'])) {
            // delete old image
            $oldImage = $restaurant->image;
            Storage::disk('images')->delete($oldImage);
            // upload image
            $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
            $ext = $attributes['logo']->getClientOriginalExtension();
            $path = $attributes['logo']->storeAs($folder, uniqid() . '.' . $ext, 'images');
            $attributes['logo'] = $path;
        }

        $restaurant->update($attributes);

        $data = new RestaurantResource($restaurant);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.restaurant_updated')]);
    }

    public function editRest(RestaurantUpdateRequest $request, Restaurant $restaurant): JsonResponse
    {
        $attributes = $request->all();

        if (isset($attributes['logo'])) {
            // delete old image
            $oldImage = $restaurant->image;
            Storage::disk('images')->delete($oldImage);
            // upload image
            $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
            $ext = $attributes['logo']->getClientOriginalExtension();
            $path = $attributes['logo']->storeAs($folder, uniqid() . '.' . $ext, 'images');
            $attributes['logo'] = $path;
        }

        $restaurant->update($attributes);

        $data = new RestaurantResource($restaurant);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.restaurant_updated')]);
    }

    /**
     *
     * Delete restaurant item.
     *
     * @OA\Delete(
     *   path="/api/admin/restaurants/{id}",
     *   description="Delete restaurant item. Authorization: accessToken;",
     *   tags={"Admin Restaurants"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Delete restaurant where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Delete restaurant item.",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/StatusSchema",
     *       example={
     *          "status":"200",
     *          "message":"PLATE_DELETED"
     *       },
     *     ),
     *   ),
     * )
     * @param Request $request
     * @param Restaurant $restaurant
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, Restaurant $restaurant): JsonResponse
    {
        $restaurant->delete();

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.restaurant_deleted')]);
    }

    /**
     * Get list of restaurants.
     *
     * @OA\Get(
     *   path="/api/admin/restaurants/search/{searchText}",
     *   description="Get list of client orders",
     *   tags={"Admin Restaurants"},
     *   @OA\Parameter(
     *     name="searchText",
     *     in="path",
     *     required=true,
     *     description="List restaurants where searchText",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *     example="fish",
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of restaurants",
     *   ),
     * )
     *
     * @param Request $request
     * @param string $searchText
     * @return JsonResponse
     */
    public function search(Request $request, string $searchText): JsonResponse
    {
        $restaurants = Restaurant::select('restaurants.*')
            ->distinct('restaurants.id')
            ->where('name', 'like', "%$searchText%")
            ->get();

        $data = RestaurantResource::collection($restaurants);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }
}
