<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\UploadTrait;
use App\Mail\ActivateAccount;
use App\Mail\WelcomeMail;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use UploadTrait;

    // Define folder path
    const FOLDER_PATH = '/uploads/images/';

    /**
     *
     * Register user.
     *
     * @OA\Post(
     *   path="/api/admin/auth/register",
     *   description="Register user",
     *   tags={"Admin Register"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/AdminRegisterCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Register user",
     *     @OA\JsonContent(ref="#/components/schemas/AdminRegisterSchema"),
     *   ),
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // get all fields
        $data = $request->only(['email', 'password', 'address', 'name', 'lastname', 'phone', 'image', 'restaurant_name']);
        $restaurant = $this->createRestaurant($data);

        if (isset($restaurant->id)) {
            // create user
            $user = User::create([
                'email' => Str::lower($data['email']),
                'name' => $data['name'],
                'lastname' => $data['lastname'] ?? '',
                'phone' => $data['phone'] ?? '',
                'role' => $data['role'] ?? 'owner',
                'restaurant_id' => $restaurant->id,
                // 'image' => (isset($data['image'] )) ? $this->uploadImage($data['image'], $data['name']) : '',
                'password' => Hash::make($data['password']),
                'remember_token' => Str::random(30),
            ]);

            if ($user) {
                // send confirm email to user
                $link = route('admin.confirm.email', ['token' => $user->remember_token]);
                // TODO: uncomment in prod
                Mail::to($user->email)->send(new ActivateAccount($link));

                $token = $user->createToken(config('app.name'), ['user']);
                $token->token->save();

                return response()->json(
                    [
                        'status' => 200,
                        'token' => $token,
                        'data' => new UserResource($user),
                        'link' => $link, // TODO: remove in prod
                        'message' => __('messages.register_success')
                    ],
                    200
                );
            }
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
     * User registration confirm email.
     *
     * @OA\Get(
     *   path="/api/admin/auth/register/confirm/{token}",
     *   description="User registration confirm email",
     *   tags={"Admin Register"},
     *   @OA\Parameter(
     *     name="token",
     *     in="path",
     *     required=true,
     *     description="User registration confirm email where token",
     *     example="2564fer43rtfedfge44tf45t4tgefer5",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="User registration confirmation",
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
        $user = User::where('remember_token', $token)->firstOrFail();
        // send welcome email
        Mail::to($user['email'])->send(new WelcomeMail($user));
        // confirm email
        $user->confirmEmail();

        return response()->json(
            [
                'status' => 200,
                'data' => [],
                'message' => __('messages.validation_verified')
            ],
            200
        );
    }


    /**
     * @param $data
     * @return JsonResponse
     */
    public function createRestaurant($data)
    {
        try {
            // create restaurant
            $restaurant = Restaurant::create([
                'name' => $data['restaurant_name'],
                'address' => $data['address'] ?? '',
                'phone' => $data['phone'] ?? '',
                'logo' => (isset($data['image'])) ? $this->uploadImage($data['image'], $data['restaurant_name']) : '',
            ]);

            return $restaurant;
        } catch (\Exception $error) {

            return response()->json(
                [
                    'status' => 403,
                    'data' => [],
                    'message' => __('validation.error'),
                    'errors' => $error->getMessage()
                ],
                403
            );
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function uploadImage($image, $name): string
    {
        // Make a image name based on name and current timestamp
        $filename = Str::slug($name) . '_' . time();
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $filePath = self::FOLDER_PATH . $filename . '.' . $image->getClientOriginalExtension();
        // Upload image
        $this->uploadOne($image, self::FOLDER_PATH, 'images', $filename);

        return $filePath;
    }
}
