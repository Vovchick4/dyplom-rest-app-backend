<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Plate;
use App\Models\Category;
use App\Http\Resources\PlateResource;
use App\Http\Resources\PlateCollection;
use App\Http\Requests\Api\Admin\PlateIndexRequest;
use App\Http\Requests\Api\Admin\PlateStoreRequest;
use App\Http\Requests\Api\Admin\PlateUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PlateController extends Controller
{

    /**
     * Get list of plates.
     *
     * @OA\Get(
     *   path="/api/admin/plates",
     *   description="Get list of plates. Authorization: accessToken;",
     *   tags={"Admin Plates"},
     *   security={{"passport":{}}},
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
     *     name="active",
     *     in="query",
     *     required=false,
     *     description="Find plates by active status",
     *     example="1",
     *     @OA\Schema(
     *     type="string",
     *     enum={"0", "1"},
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
     *     @OA\JsonContent(ref="#/components/schemas/AdminPlateSchema"),
     *   ),
     * )
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PlateIndexRequest $request)
    {
        $user = $request->user();

        // get user restaurant_id
        $restaurantId = $user->restaurant_id;

        if (
            $user->role == self::SUPER_ADMIN
            && !empty($request->header('restaurant'))
        ) {
            $restaurantId = $request->header('restaurant');
        }

        $searchText = $request->header('searchText');

        $perPage = (int)request('per_page', 9);
        $plates = Plate::where('plates.restaurant_id', $restaurantId)
            ->when(request('active') !== null, function ($query) {
                $query->where('plates.active', request('active'));
            })
            ->when(request('category_id'), function ($query) {
                $query->where('plates.category_id', request('category_id'));
            })
            ->whereHas('translations', function ($query) use ($searchText) {
                $query->where('name', 'like', "%$searchText%");
            })
            ->paginate($perPage);

        $resource = new PlateCollection($plates);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');

        return response()->json($data);
    }

    /**
     * Create new plate.
     *
     * @OA\Post(
     *   path="/api/admin/plates",
     *   description="Add plate. Uses to add new plate. Authorization: accessToken;",
     *   tags={"Admin Plates"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/AdminPlateCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Create new plate",
     *     @OA\JsonContent(ref="#/components/schemas/AdminPlateSchema"),
     *   ),
     * )
     * @param PlateStoreRequest $request
     * @return JsonResponse
     */
    public function store(PlateStoreRequest $request): JsonResponse
    {
        $user = $request->user();

        // get user restaurant_id
        $restaurantId = $user->restaurant_id;

        if (
            $user->role == self::SUPER_ADMIN
            && !empty($request->header('restaurant'))
        ) {
            $restaurantId = $request->header('restaurant');
        }

        $attributes = $request->all();
        $attributes['restaurant_id'] = $restaurantId;

        // upload image
        $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
        $ext = $attributes['image']->getClientOriginalExtension();
        $path = $attributes['image']->storeAs($folder, uniqid() . '.' . $ext, 'images');
        $attributes['image'] = $path;

        $plate = Plate::create($attributes);

        $data = new PlateResource($plate);

        return response()->json(['data' => $data, 'status' => 201, 'message' => __('messages.plate_created')], 201);
    }

    /**
     * Show plate item.
     *
     * @OA\Get(
     *   path="/api/admin/plates/{id}",
     *   description="Show plate item. Authorization: accessToken;",
     *   tags={"Admin Plates"},
     *   security={{"passport":{}}},
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
     *     @OA\JsonContent(ref="#/components/schemas/AdminPlateSchema"),
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
     *
     * Update plate item.
     *
     * @OA\Post(
     *   path="/api/admin/plates/{id}",
     *   description="Update plate item; Authorization: accessToken;",
     *   tags={"Admin Plates"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Update plate where id",
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
     *       @OA\Schema(ref="#/components/schemas/AdminPlateCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update plate item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminPlateSchema"),
     *   ),
     * )
     * @param PlateUpdateRequest $request
     * @param Plate $plate
     *
     * @return JsonResponse
     */
    public function update(PlateUpdateRequest $request, Plate $plate): JsonResponse
    {
        $attributes = $request->all();

        if (isset($attributes['image'])) {
            // delete old image
            $oldImage = $plate->image;
            Storage::disk('images')->delete($oldImage);

            // upload image
            $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
            $ext = $attributes['image']->getClientOriginalExtension();
            $path = $attributes['image']->storeAs($folder, uniqid() . '.' . $ext, 'images');
            $attributes['image'] = $path;
        }

        $plate->update($attributes);

        $data = new PlateResource($plate);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.plate_updated')]);
    }

    /**
     *
     * Delete plate item.
     *
     * @OA\Delete(
     *   path="/api/admin/plates/{id}",
     *   description="Delete plate item. Authorization: accessToken;",
     *   tags={"Admin Plates"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Delete plate where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Delete plate item.",
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
     * @param Plate $plate
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, Plate $plate): JsonResponse
    {
        $plate->delete();

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.plate_deleted')]);
    }
}
