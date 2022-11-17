<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Auth\RegisterRequest;
use App\Mail\ActivateAccount;
use App\Mail\WelcomeMail;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    /**
     *
     * Register client.
     *
     * @OA\Post(
     *   path="/api/client/auth/register",
     *   description="Register client",
     *   tags={"Client Register"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/ClientRegisterSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Register client",
     *     @OA\JsonContent(example={}),
     *   ),
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // get all fields
        $data = $request->only(['email', 'password', 'name', 'phone']);

        $client = Client::create([
            'email' => $data['email'] ? Str::lower($data['email']) : null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(30),
        ]);

        if ($client) {
            // send confirm email to user
            $link = route('client.confirm.email', ['token' => $client->remember_token]);
            // TODO: uncomment in prod
            Mail::to($client->email)->send(new ActivateAccount($link));

            $token = $client->createToken(config('app.name'), ['client']);
            $token->token->save();

            return response()->json(
                [
                    'status' => 200,
                    'data' => $client,
                    'token' => $token,
                    'link' => $link, // TODO: remove in prod
                    'message' => __('messages.register_success')
                ],
                200
            );
        }

        return response()->json(
            [
                'status' => 403,
                'data' => null,
                'message' => __('messages.register_error')
            ],
            403
        );
    }

    /**
     * Client registration confirm email.
     *
     * @OA\Get(
     *   path="/api/client/auth/register/confirm/{token}",
     *   description="Client registration confirm email",
     *   tags={"Client Register"},
     *   @OA\Parameter(
     *     name="token",
     *     in="path",
     *     required=true,
     *     description="Client registration confirm email where token",
     *     example="2564fer43r4564564tgerttfedfge44tf45t4tgefer5",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Client registration confirmation",
     *     @OA\JsonContent(example={}),
     *   ),
     * )
     *
     * @param Request $request
     * @param string $token
     * @return JsonResponse
     */
    public function confirmEmail(Request $request, string $token): JsonResponse
    {
        $client = Client::where('remember_token', $token)->firstOrFail();
        // send welcome email
        Mail::to($client['email'])->send(new WelcomeMail($client));
        // confirm email
        $client->confirmEmail();

        return response()->json(
            [
                'status' => 200,
                'data' => [],
                'message' => __('messages.validation_verified')
            ],
            200
        );
    }
}
