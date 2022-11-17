<?php

namespace App\Http\Controllers\Api\Client;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\OrderStoreRequest;
use App\Http\Requests\Api\Client\PayPalResponseRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Plate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderController extends Controller
{
    /**
     * Get list of client orders.
     *
     * @OA\Get(
     *   path="/api/client/orders",
     *   description="Get list of client orders. Authorization: accessToken;",
     *   tags={"Client Orders"},
     *   security={{"passport":{}}},
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
     *     @OA\JsonContent(ref="#/components/schemas/ClientOrderSchema"),
     *   ),
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = (int)request('per_page', 9);

        $orders = Order::where('client_id', $user->id)
            ->latest()
            ->paginate($perPage);

        $resource = new OrderCollection($orders);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');

        return response()->json($data);
    }


    /**
     * Create new client order.
     *
     * @OA\Post(
     *   path="/api/client/orders",
     *   description="Add client order. Uses to add new order. Authorization: accessToken;",
     *   tags={"Client Orders"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(ref="#/components/schemas/ClientOrderCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Create new client order",
     *     @OA\JsonContent(ref="#/components/schemas/ClientOrderSchema"),
     *   ),
     * )
     * @param OrderStoreRequest $request
     * @return JsonResponse
     *
     */
    public function store(OrderStoreRequest $request): JsonResponse
    {
        $user = $request->user();
        $paymentType = $request->input('payment_type');

        $attributes = $request->only([
            'name',
            'table',
            'person_quantity',
            'people_for_quantity',
            'is_takeaway',
            'is_online_payment',
            'restaurant_id'
        ]);
        $plates = $request->plates;

        $attributes['client_id'] = $user ? $user->id : null;

        $order = new Order;

        foreach ($attributes as $key => $value) {
            $order[$key] = $value;
        }

        DB::transaction(function () use ($order, $plates) {
            $order->save();
            $order->plates()->attach($plates);

            foreach ($plates as $id => $item) {
                Plate::find($id)
                    ->decrement('quantity', $item['amount']);
            }
        });

        $order = $order->fresh();

        $data = [
            'data' => new OrderResource($order),
            'link' => '',
            'status' => 201,
            'message' => 'Created'
        ];

        if ($paymentType === 'paypal') {
            $paypalOrder = $this->createPaypalOrder($order);
            $order->update(['payment_id' => $paypalOrder['id'], 'payment_response' => $paypalOrder]);

            if ($paypalOrder['status'] === 'CREATED') {
                foreach ($paypalOrder['links'] as $link) {
                    if ($link['rel'] === 'approve')
                        $data['link'] = $link['href'];
                }
            }
        }

        OrderCreated::dispatch($order);

        return response()->json(['data' => $data, 'status' => 201, 'message' => __('messages.order_created')], 201);
    }


    /**
     * Show order item.
     *
     * @OA\Get(
     *   path="/api/client/orders/{id}",
     *   description="Show order item. Authorization: accessToken;",
     *   tags={"Client Orders"},
     *   security={{"passport":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Show client order where id",
     *     example="25",
     *     @OA\Schema(
     *     type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Show client order item.",
     *     @OA\JsonContent(ref="#/components/schemas/ClientOrderSchema"),
     *   ),
     * )
     * @param Request $request
     * @param Order $order
     *
     * @return JsonResponse
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $data = new OrderResource($order);

        return response()->json(['data' => $data, 'status' => 200, 'message' => __('messages.ok')]);
    }

    private function createPaypalOrder(Order $order)
    {
        $provider = new PayPalClient([]);
        $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            'application_context' =>
            array(
                'return_url' => config('paypal.notify_url'),
                'cancel_url' => config('paypal.cancel_url')
            ),
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR", // TODO: maybe store currency in restaurant's settings
                        "value" => $order->price
                    ]
                ],
            ]
        ]);

        return $order;
    }

    public function paypalPaymentSuccess(PayPalResponseRequest $request)
    {
        $orderId = request('token');

        $provider = new PayPalClient([]);
        $provider->getAccessToken();

        $paypalOrder = $provider->capturePaymentOrder($orderId);

        if ($paypalOrder['status'] === 'COMPLETED') {
            $order = Order::where('payment_id', $orderId)->firstOrFail();
            $order->update(['payment_status' => 'paid', 'payment_response' => $paypalOrder]);
        }

        // TODO: redirect to the client order page (page with timer)
        return redirect(url('/'));
    }

    public function paypalPaymentCancel(PayPalResponseRequest $request)
    {
        $orderId = request('token');
        $order = Order::where('payment_id', $orderId)->firstOrFail();
        $order->update(['payment_status' => 'not_paid']);

        // TODO: redirect to the restaurant or redirect to the order page with button for another payment try?
        return redirect(url('/'));
    }
}
