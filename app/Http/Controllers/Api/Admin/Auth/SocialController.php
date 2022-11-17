<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_GOOGLE = 'google';

    const PROVIDERS = [
        self::PROVIDER_FACEBOOK => 'fb_id',
        self::PROVIDER_GOOGLE => 'google_id',
    ];

    /**
     * Redirect the user to the social provider
     *
     * @OA\Get(
     *   path="/api/admin/auth/login/{provider}/redirect",
     *   description="Redirect user to provider",
     *   tags={"Admin Login"},
     *   @OA\Parameter(
     *     name="provider",
     *     in="path",
     *     required=true,
     *     description="Redirect user to provider",
     *     example="google",
     *     @OA\Schema(
     *     type="string",
     *     enum={"facebook", "google"},
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Redirect user to provider.",
     *     @OA\JsonContent(
     *      @OA\Property(
     *         property="data",
     *         description="Admin Redirect user to provider response data",
     *         type="object",
     *         example={"some data from provider": "[]"},
     *      ),
     *     ),
     *   ),
     * )
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Login user with provider.
     *
     * @OA\Get(
     *   path="/api/admin/auth/login/{provider}",
     *   description="Login user with provider",
     *   tags={"Admin Login"},
     *   @OA\Parameter(
     *     name="provider",
     *     in="path",
     *     required=true,
     *     description="Login user with provider",
     *     example="google",
     *     @OA\Schema(
     *     type="string",
     *     enum={"facebook", "google"},
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Login user with provider.",
     *     @OA\JsonContent(
     *     @OA\Property(
     *         property="data",
     *         description="Admin Login with provider data",
     *         type="object",
     *         ref="#/components/schemas/AdminUserSchema"
     *      ),
     *      @OA\Property(
     *         property="token",
     *         description="Admin Login with provider token",
     *         type="object",
     *         ref="#/components/schemas/TokenSchema"
     *      ),
     *    ),
     *   ),
     * )
     * @param $provider
     * @return JsonResponse
     */
    public function login(string $provider): JsonResponse
    {
        try {
            // get the provider's user. (In the provider server)
            $providerUser = Socialite::driver($provider)->user();
            // search for a user in our server with the specified provider id
            $user = User::where(self::PROVIDERS[$provider], $providerUser->id)->first();
            // if there is no record with these data, create a new user
            if ($user == null) {
                $user = User::create([
                    'name' => $providerUser->name,
                    'email' => Str::lower($providerUser->email),
                    self::PROVIDERS[$provider] => $providerUser->id,
                    'password' => Hash::make(Str::random(8))
                ]);
            }
            // create a token for the user, so they can login
            $token = $user->createToken(config('app.name'))->accessToken;
            // return the token for usage
            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'data' => $user,
                    'message' => __('validation.success'),
                ],
                200
            );
        } catch (Exception $exception) {
            return response()->json(
                [
                    'status' => 403,
                    'data' => [],
                    'message' => __('validation.error'),
                    'errors' => $exception->getMessage()
                ],
                403
            );
        }
    }
}
