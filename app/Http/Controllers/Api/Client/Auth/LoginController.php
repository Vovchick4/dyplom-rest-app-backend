<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Auth\LoginRequest;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
    /**
     * Client Login.
     *
     * @OA\Post(
     *   path="/api/client/auth/login",
     *   description="Client Login.",
     *   tags={"Client Login"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/ClientLoginSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Client Login",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         description="Client Login data",
     *         type="object",
     *         ref="#/components/schemas/ClientSchema"
     *      ),
     *      @OA\Property(
     *         property="token",
     *         description="Client Login token",
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
        $data = $request->only(['email_or_phone', 'password']);

        $client = Client::where('email', $data['email_or_phone'])
            ->orWhere('phone', $data['email_or_phone'])
            ->first();

        if ($client) {
            if (Hash::check($data['password'], $client->password)) {

                $token = $client->createToken(config('app.name'), ['client']);
                $token->token->save();

                return response()->json(
                    [
                        'status' => 200,
                        'data' => $client,
                        'token' => $token,
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
                    'errors' => __('validation.current_password')
                ],
                422
            );
        }

        return response()->json(
            [
                'status' => 422,
                'data' => [],
                'message' => __('validation.error'),
                'errors' => __('validation.client_not_exist')
            ],
            422
        );
    }

    /**
     *  Logout client
     *
     * @OA\Post(
     *   path="/api/client/auth/logout",
     *   description="Logout client. Authorization: accessToken;",
     *   tags={"Client Logout"},
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
     *     description="Logout client",
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
            'status' => 200, 'data' => [], 'message' => __('message.logout'),
        ]);
    }

    /**
     *  Get user
     *
     * @OA\Get(
     *   path="/api/client/auth/get-user",
     *   description="Get user if token hasn't expired",
     *   tags={"Client Get"},
     *   security={{"passport":{}}},
     *   @OA\Response(
     *     response="200",
     *     description="Get client",
     *     @OA\JsonContent(type="object", example={
     *     "status": "200",
     *     "data": {
     *         "id": 21,
     *         "name": "Gene Spinka",
     *         "email": "monica.torphy@example.com",
     *         "phone": "+62080668754006",
     *         "fb_id": null,
     *         "google_id": null,
     *         "verified_at": null,
     *         "payment_method": "apple",
     *         "remember_token": null,
     *         "created_at": "2021-08-16T07:45:05.000000Z",
     *         "updated_at": "2021-08-16T07:45:05.000000Z"
     *      },
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
        return response()->json([
            'status' => 200,
            'data' => $request->user(),
            'message' => 'OK',
        ]);
    }
}
