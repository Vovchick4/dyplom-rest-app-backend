<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Api\Admin\CategoryStoreRequest;
use App\Http\Requests\Api\Admin\CategoryUpdateRequest;
use App\Http\Requests\Api\Admin\CategoryIndexRequest;
use App\Http\Resources\PlateResource;
use App\Models\Plate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    /**
     * Get list of categories.
     *
     * @OA\Get(
     *   path="/api/admin/categories",
     *   description="Get list of categories. Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="parent_id",
     *     in="query",
     *     required=false,
     *     description="List categories where parent_id",
     *     example="25",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="active",
     *     in="query",
     *     required=false,
     *     description="List categories where active",
     *     example="1",
     *     @OA\Schema(
     *     type="string",
     *     enum={"0", "1"},
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of categories",
     *     @OA\JsonContent(ref="#/components/schemas/AdminCategorySchema"),
     *   ),
     * )
     *
     * @param CategoryIndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(CategoryIndexRequest $request): JsonResponse
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

        $categories = Category::where('restaurant_id', $restaurantId)
            ->when(request('active') !== null, function ($query) {
                $query->where('categories.active', request('active'));
            })
            ->when(request('parent_id'), function ($query) {
                $query->where('categories.parent_id', request('parent_id'));
            })
            ->get();

        $data = CategoryResource::collection($categories);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('message.ok'),]);
    }

    /**
     * Create new category.
     *
     * @OA\Post(
     *   path="/api/admin/categories",
     *   description="Add category. Uses to add new catogory. Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/AdminCategoryCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Create new category",
     *     @OA\JsonContent(ref="#/components/schemas/AdminCategorySchema"),
     *   ),
     * )
     * @param CategoryStoreRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(CategoryStoreRequest $request): JsonResponse
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

        $category = Category::create($attributes);

        $data = new CategoryResource($category);

        return response()->json(['data' => $data, 'status' => 201, 'message' => __('message.category_created')], 201);
    }

    /**
     * Show category item.
     *
     * @OA\Get(
     *   path="/api/admin/categories/{id}",
     *   description="Show category item; Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show category where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show category item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminCategorySchema"),
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

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('message.ok')]);
    }

    /**
     *
     * Update category item.
     *
     * @OA\Post(
     *   path="/api/admin/categories/{id}",
     *   description="Update category item; Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Update category where id",
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
     *       @OA\Schema(ref="#/components/schemas/AdminCategoryCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update category item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminCategorySchema"),
     *   ),
     * )
     * @param CategoryUpdateRequest $request
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        $attributes = $request->all();

        if (isset($attributes['image'])) {
            // delete old image
            $oldImage = $category->image;
            Storage::disk('images')->delete($oldImage);

            // upload image
            $folder = rand(1, 100) . '/' . rand(1, 100) . '/' . rand(1, 100);
            $ext = $attributes['image']->getClientOriginalExtension();
            $path = $attributes['image']->storeAs($folder, uniqid() . '.' . $ext, 'images');
            $attributes['image'] = $path;
        }

        $category->update($attributes);

        $data = new CategoryResource($category);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.category_updated')]);
    }

    /**
     *
     * Delete category item.
     *
     * @OA\Delete(
     *   path="/api/admin/categories/{id}",
     *   description="Delete category item; Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Delete category where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Delete category item.",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/StatusSchema",
     *       example={
     *          "status":"200",
     *          "message":"CATEGORY_DELETED"
     *       },
     *     ),
     *   ),
     * )
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        Storage::disk('images')->delete($category->image);

        $category->delete();

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.category_deleted')]);
    }

    /**
     * Show plates where category_id or where plates category_id = NULL.
     *
     * @OA\Get(
     *   path="/api/admin/categories/{category}/plates-list",
     *   description="Show plates where category_id or where plates category_id = NULL; Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="category",
     *     in="path",
     *     required=true,
     *     description="Show plates where category_id or where plates category_id = NULL",
     *     example="25",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show plates where category_id or where plates category_id = NULL",
     *     @OA\JsonContent(ref="#/components/schemas/AdminPlateSchema"),
     *   ),
     * )
     * @param Request $request
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function platesList(Request $request, Category $category): JsonResponse
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

        $plates = Plate::where('restaurant_id', $restaurantId)->get();

        $data = PlateResource::collection($plates);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }

    /**
     * Assign plates to category.
     *
     * @OA\Post(
     *   path="/api/admin/categories/{category}/plates-sync",
     *   description="Assign plates to category; Authorization: accessToken;",
     *   tags={"Admin Categories"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="category",
     *     in="path",
     *     required=true,
     *     description="Assign plates to this category.",
     *     example="25",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Admin Category plates IDs",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *          @OA\Property(
     *           property="plate_ids",
     *           description="Admin Category plates IDs",
     *           type="array",
     *           example={"34", "45", "7878"},
     *           @OA\Items(),
     *          ),
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Assign plates to this category.",
     *     @OA\JsonContent(
     *     type="object",
     *     example={}
     *     ),
     *   ),
     *  )
     *
     * @param Request $request
     * @param Category $category
     *
     * @return JsonResponse
     *
     */
    public function platesSync(Request $request, Category $category): JsonResponse
    {
        $plateIds = request('plate_ids');

        $category->plates()->sync($plateIds);
//        DB::transaction(function () use ($category, $plateIds) {
//            $category->plates()->update(['category_id' => null]);
//
//            Plate::whereIn('id', $plateIds)->update(['category_id' => $category->id]);
//        });

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.ok')]);
    }
}
