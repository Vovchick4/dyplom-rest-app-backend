<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\UploadTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UploadTrait;

    /**
     * Get list of users.
     *
     * @OA\Get(
     *   path="/api/admin/users",
     *   description="Get list of users. Authorization: accessToken;",
     *   tags={"Admin Users"},
     *   security={{"passport":{}}},
     *   @OA\Response(
     *     response="200",
     *     description="Get list of users",
     *     @OA\JsonContent(ref="#/components/schemas/AdminUserSchema"),
     *   ),
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // get user
        $user = $request->user();

        // get user restaurant_id
        $restaurantId = $user->restaurant_id;

        if (
            $user->role == self::SUPER_ADMIN
            && !empty($request->header('restaurant'))
        ) {
            $restaurantId = $request->header('restaurant');
        }

        $users = User::where('restaurant_id', $restaurantId)->get();

        return response()->json([
            'data' => UserResource::collection($users),
            'status' => 200,
            'message' => __('validation.success')
        ]);
    }

    /**
     * Show user item.
     *
     * @OA\Get(
     *   path="/api/admin/users/{id}",
     *   description="Show user item. Authorization: accessToken;",
     *   tags={"Admin Users"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show user where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show user item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminUserSchema"),
     *   ),
     * )
     * @param Request $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $data = new UserResource($user);

        return response()->json([
            'data' => $data,
            'status' => 200,
            'message' => __('validation.success')
        ]);
    }

    /**
     *
     * Update user item.
     *
     * @OA\Post(
     *   path="/api/admin/users/update",
     *   description="Update user item. Authorization: accessToken;",
     *   tags={"Admin Users"},
     *   security={{"passport":{}}},
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
     *       @OA\Schema(ref="#/components/schemas/AdminUserCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update user item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminUserSchema"),
     *   ),
     * )
     * @param UserUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->only(['email', 'password', 'address', 'name', 'lastname', 'phone', 'image']);
        // Check if a image has been uploaded
        if (isset($data['image'])) {
            // delete old image
            $oldImage = $user->image;
            Storage::disk('images')->delete($oldImage);

            $data['image'] = (new RegisterController())->uploadImage($data['image'], $data['name']);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        $data = new UserResource($user);

        return response()->json([
            'data' => $data,
            'status' => 200,
            'message' => __('messages.user_updated_successfully')
        ]);
    }

    /**
     *
     * Delete user.
     *
     * @OA\Delete(
     *   path="/api/admin/users/{id}",
     *   description="Delete user",
     *   tags={"Admin Users"},
     *   security={
     *     {"passport": {}},
     *   },
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Delete user where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Delete user.",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/StatusSchema",
     *       example={
     *          "status":"200",
     *          "message":"USER_DELETED"
     *       },
     *     ),
     *   ),
     * )
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        Storage::disk('images')->delete($user->image);
        $user->delete();

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.user_deleted_successfully')]);
    }
}
