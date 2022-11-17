<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
    /**
     * Admin Login user.
     *
     * @OA\Post(
     *   path="/api/admin/auth/login",
     *   description="Admin Login user",
     *   tags={"Admin Login"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/AdminLoginSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Admin Login user",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         description="Admin Login data",
     *         type="object",
     *         ref="#/components/schemas/AdminLoginSchema"
     *      ),
     *      @OA\Property(
     *         property="token",
     *         description="Admin Login token",
     *         type="object",
     *         ref="#/components/schemas/TokenSchema"
     *      ),
     *     ),
     *   ),
     * )
     * @param LoginRequest $request
     *
     * @return JsonResponse
     *
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // get all fields
        $data = $request->only(['email', 'password', 'remember_me']);
        $rememberMe = $data['remember_me'] ?? false;
        // find user
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json(
                    [
                        'status' => 403,
                        'data' => [],
                        'message' => __('validation.error'),
                        'errors' => __('validation.password')
                    ],
                    403
                );
            }

            if ($rememberMe)
                Passport::personalAccessTokensExpireIn(Carbon::now()->addMonth());

            $token = $user->createToken(config('app.name'), ['user']);
            $token->token->save();

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'data' => new UserResource($user),
                    'message' => __('validation.success'),
                ],
                200
            );
        }

        return response()->json(
            [
                'status' => 422,
                'data' => [],
                'message' => __('validation.error'),
                'errors' => __('validation.user_not_exist')
            ],
            422
        );
    }

    /**
     *  Logout user
     *
     * @OA\Post(
     *   path="/api/admin/auth/logout",
     *   description="Logout user. Authorization: accessToken;",
     *   tags={"Admin Logout"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Logout user",
     *     @OA\JsonContent(type="object", example={
     *     "status": "200",
     *     "message": "OK"
     *     }),
     *   ),
     * )
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => __('message.logout'),
        ]);
    }

    /**
     *  Get user
     *
     * @OA\Get(
     *   path="/api/admin/auth/get-user",
     *   description="Get user if token hasn't expired",
     *   tags={"Admin Get"},
     *   security={{"passport":{}}},
     *   @OA\Response(
     *     response="200",
     *     description="Get user",
     *     @OA\JsonContent(type="object", example={
     *     "status": "200",
     *      "data": {
     *           "id": 23,
     *           "name": "Test",
     *           "lastname": "",
     *           "image": null,
     *           "email": "test@test.com",
     *           "phone": null,
     *           "role": "owner",
     *           "restaurant_id": 1,
     *           "restaurant_slug": "sequi"
     *       },
     *     "message": "OK"
     *     }),
     *   ),
     * )
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        $data = new UserResource($request->user());

        return response()->json([
            'status' => 200,
            'data' => $data,
            'message' => __('message.ok'),
        ]);
    }
}
