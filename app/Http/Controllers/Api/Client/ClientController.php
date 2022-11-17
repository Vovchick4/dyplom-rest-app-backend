<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Show client item.
     *
     * @OA\Get(
     *   path="/api/client/clients/{id}",
     *   description="Show client item. Authorization: accessToken;",
     *   tags={"Clients"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show client where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show client item.",
     *     @OA\JsonContent(ref="#/components/schemas/ClientSchema"),
     *   ),
     * )
     * @param Client $client
     *
     * @return JsonResponse
     */
    public function show(Request $request, Client $client): JsonResponse
    {
        return response()->json(['data' => $client->toArray(), 'status' => 200, 'message' => __('validation.success')]);
    }

    /**
     *
     * Update client item.
     *
     * @OA\Post(
     *   path="/api/client/clients/update",
     *   description="Update client item. Authorization: accessToken;",
     *   tags={"Clients"},
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
     *       @OA\Schema(ref="#/components/schemas/ClientUpdateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update client item.",
     *     @OA\JsonContent(ref="#/components/schemas/ClientSchema"),
     *   ),
     * )
     * @param ClientUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(ClientUpdateRequest $request): JsonResponse
    {
        $client = $request->user();

        $data = $request->only(['name', 'email', 'phone', 'password', 'payment_method']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        // TODO: update avatar
        $client->update($data);

        return response()->json(['data' => $client->toArray(), 'status' => 200, 'message' => __('messages.client_updated_successfully')]);
    }
}
