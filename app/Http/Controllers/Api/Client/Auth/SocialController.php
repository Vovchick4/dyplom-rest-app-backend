<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
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
     * Redirect the client to the social provider
     *
     * @OA\Get(
     *   path="/api/client/auth/login/{provider}/redirect",
     *   description="Redirect client to provider",
     *   tags={"Client Login"},
     *   @OA\Parameter(
     *     name="provider",
     *     in="path",
     *     required=true,
     *     description="Redirect client to provider",
     *     example="google",
     *     @OA\Schema(
     *     type="string",
     *     enum={"facebook", "google"},
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Redirect client to provider.",
     *     @OA\JsonContent(
     *      @OA\Property(
     *         property="data",
     *         description="Redirect client to provider response data",
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
     * Login client with provider.
     *
     * @OA\Get(
     *   path="/api/client/auth/login/{provider}",
     *   description="Login client with provider",
     *   tags={"Client Login"},
     *   @OA\Parameter(
     *     name="provider",
     *     in="path",
     *     required=true,
     *     description="Login client with provider",
     *     example="google",
     *     @OA\Schema(
     *     type="string",
     *     enum={"facebook", "google"},
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Login client with provider.",
     *     @OA\JsonContent(
     *     @OA\Property(
     *         property="data",
     *         description="Client Login with provider data",
     *         type="object",
     *         ref="#/components/schemas/ClientSchema"
     *      ),
     *      @OA\Property(
     *         property="token",
     *         description="Client Login with provider token",
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
            // get the provider's client. (In the provider server)
            $providerClient = Socialite::driver($provider)->user();
            // search for a client in our server with the specified provider id
            $client = Client::where(self::PROVIDERS[$provider], $providerClient->id)->first();
            // if there is no record with these data, create a new client
            if ($client == null) {
                $client = Client::create([
                    'name' => $providerClient->name,
                    'email' => Str::lower($providerClient->email),
                    self::PROVIDERS[$provider] => $providerClient->id,
                    'password' => Hash::make(Str::random(8))
                ]);
            }
            // create a token for the client, so they can login
            $token = $client->createToken(config('app.name'))->accessToken;
            // return the token for usage
            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'data' => $client,
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
