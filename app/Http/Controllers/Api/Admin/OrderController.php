<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\OrderIndexRequest;
use App\Http\Requests\Api\Admin\OrderUpdateRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * Get list of orders.
     *
     * @OA\Get(
     *   path="/api/admin/orders",
     *   description="Get list of orders; Authorization: accessToken;",
     *   tags={"Admin Orders"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="client_id",
     *     in="query",
     *     required=false,
     *     description="List orders where client_id",
     *     example="25",
     *     @OA\Schema(
     *     type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     required=false,
     *     description="List orders where status",
     *     example="in_process",
     *     @OA\Schema(
     *     type="string",
     *     enum={"new", "viewed", "in_process", "completed", "canceled"},
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="payment_status",
     *     in="query",
     *     required=false,
     *     description="List orders where payment_status",
     *     example="pending",
     *     @OA\Schema(
     *     type="string",
     *     enum={"pending", "paid", "not_paid"},
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="is_takeaway",
     *     in="query",
     *     required=false,
     *     description="List orders where is_takeaway",
     *     example="1",
     *     @OA\Schema(
     *     type="string",
     *     enum={"0", "1"},
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="is_online_payment",
     *     in="query",
     *     required=false,
     *     description="List orders where is_online_payment",
     *     example="1",
     *     @OA\Schema(
     *     type="string",
     *     enum={"0", "1"},
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     description="Current page number",
     *     example="1",
     *     @OA\Schema(
     *     type="number",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     required=false,
     *     description="Items per page",
     *     example="1",
     *     @OA\Schema(
     *     type="number",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Get list of orders",
     *     @OA\JsonContent(ref="#/components/schemas/AdminOrderSchema"),
     *   ),
     * )
     *
     * @param OrderIndexRequest $request
     * @return JsonResponse
     */
    public function index(OrderIndexRequest $request): JsonResponse
    {
        $user = $request->user();

        // get user restaurant_id
        $restaurantId = $user->restaurant_id;

        if (
            $user->role == self::SUPER_ADMIN
            && !empty($request->header('restaurant'))
        ) {
            $restaurantId = $request->header('restaurant');
        }

        $perPage = (int) request('per_page', 9);
        $orders = Order::where('restaurant_id', $restaurantId)
            ->when(request('is_takeaway') !== null, function ($query) {
                $query->where('orders.is_takeaway', request('is_takeaway'));
            })
            ->when(request('is_online_payment') !== null, function ($query) {
                $query->where('orders.is_online_payment', request('is_online_payment'));
            })
            ->when(request('client_id'), function ($query) {
                $query->where('orders.client_id', request('client_id'));
            })
            ->when(request('status'), function ($query) {
                $query->where('orders.status', request('status'));
            })
            ->when(request('payment_status'), function ($query) {
                $query->where('orders.payment_status', request('payment_status'));
            })
            ->latest()
            ->paginate($perPage);

        $resource = new OrderCollection($orders);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');

        return response()->json($data);
    }

    /**
     * Show order item.
     *
     * @OA\Get(
     *   path="/api/admin/orders/{id}",
     *   description="Show order item; Authorization: accessToken;",
     *   tags={"Admin Orders"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show order where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show order item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminOrderSchema"),
     *   ),
     * )
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        $data = new OrderResource($order);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }

    /**
     *
     * Update order item.
     *
     * @OA\Post(
     *   path="/api/admin/orders/{id}",
     *   description="Update order item. Authorization: accessToken;",
     *   tags={"Admin Orders"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Update order where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
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
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/AdminOrderUpdateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Update order item.",
     *     @OA\JsonContent(ref="#/components/schemas/AdminOrderSchema"),
     *   ),
     * )
     * @param OrderUpdateRequest $request
     * @param Order $order
     *
     * @return JsonResponse
     */
    public function update(OrderUpdateRequest $request, Order $order): JsonResponse
    {
        $attributes = $request->only(['status', 'payment_status', 'table', 'name', 'person_quantity', 'people_for_quantity', 'is_takeaway', 'is_online_payment']);

        $order->update($attributes);

        $data = new OrderResource($order);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.order_updated')]);
    }

    /**
     *
     * Delete order item.
     *
     * @OA\Delete(
     *   path="/api/admin/orders/{id}",
     *   description="Delete order item",
     *   tags={"Admin Orders"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Delete order where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Delete order item.",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/StatusSchema",
     *       example={
     *          "status":"200",
     *          "message":"ORDER_DELETED"
     *       },
     *     ),
     *   ),
     * )
     * @param Request $request
     * @param Order $order
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['data' => null, 'status' => 200, 'message' => __('messages.order_deleted')]);
    }
}
