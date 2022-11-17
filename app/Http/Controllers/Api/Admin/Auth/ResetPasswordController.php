<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Auth\ResetPasswordRequest;
use App\Mail\ResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Reset user password.
     *
     * @OA\Post(
     *   path="/api/admin/auth/password/reset",
     *   description="Reset user password",
     *   tags={"Admin Reset Password"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/AdminResetPasswordSchema"),
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

        $user = User::where('email', $data['email'])->first();

        if (isset($user)) {
            $newPassword = Str::random(8);
            $user->password = Hash::make($newPassword);
            $user->save();

            Mail::to($user->email)->send(new ResetMail($newPassword));

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
                'errors' => __('validation.client_not_exist')
            ],
            422
        );
    }
}
