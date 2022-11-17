<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Auth\ResetPasswordRequest;
use App\Mail\ResetMail;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Reset client password.
     *
     * @OA\Post(
     *   path="/api/client/auth/password/reset",
     *   description="Reset client password",
     *   tags={"Client Reset Password"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/ClientResetPasswordSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Reset password link has been sent",
     *     @OA\JsonContent(example={}),
     *   ),
     * )
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     *
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        // get all fields
        $data = $request->only(['email']);

        $client = Client::where('email', $data['email'])->first();
        // if isset client
        if (isset($client)) {
            $newPassword = Str::random(8);
            $client->password = Hash::make($newPassword);
            $client->save();

            Mail::to($client->email)->send(new ResetMail($newPassword));

            return response()->json(
                [
                    'status' => 200,
                    'data' => [],
                    'message' => __('messages.restore_password')
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
}
