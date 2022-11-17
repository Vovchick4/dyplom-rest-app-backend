<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

/**
 * Class AdminOrderControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Admin\OrderController.php
 *
 * @covers \App\Http\Controllers\Api\Admin\OrderController
 * @group order
 * @group admin-order
 */
class AdminOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private $restaurant;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $restaurant = Restaurant::factory()
            ->create();

        $this->restaurant = $restaurant;

        $this->user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();
    }

    /**
     * Test user can get orders
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::index()
     * @group admin-order-index
     * @group admin-order-index-valid
     *
     */
    public function testUserCanGetOrders()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        Order::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'is_takeaway' => 0
                ];
            })
            ->create();

        Order::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'is_takeaway' => 1
                ];
            })
            ->create();

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->paginate(9);

        $onSiteOrders = Order::where('restaurant_id', $restaurant->id)
            ->where('is_takeaway', 0)
            ->paginate(9);

        $takeawayOrders = Order::where('restaurant_id', $restaurant->id)
            ->where('is_takeaway', 1)
            ->paginate(9);

        $route = route('admin.orders.index');

        $testCases = [
            // all orders
            [
                'orders' => $orders,
                'route' => $route
            ],
            // on-site orders
            [
                'orders' => $onSiteOrders,
                'route' => "$route?is_takeaway=0"
            ],
            // takeaway orders
            [
                'orders' => $takeawayOrders,
                'route' => "$route?is_takeaway=1"
            ],
        ];

        foreach ($testCases as $case) {
            $resource = new OrderCollection($case['orders']);
            $data = $resource->response()->getData(true);
            $data['status'] = 200;
            $data['message'] = 'OK';

            $this->actingAs($user, 'user')
                ->getJson($case['route'])
                ->assertStatus(200)
                ->assertExactJson($data);
        }
    }

    /**
     * Test user can't get orders with invalid query
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::index()
     * @group admin-order-index
     * @group admin-order-index-invalid
     *
     */
    public function testUserCantGetOrdersWithInvalidQuery()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        Order::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $route = route('admin.orders.index');

        $testCases = [
            // is_takeaway can only be 0 or 1
            [
                'route' => "$route?is_takeaway=2"
            ],
            // is_online_payment can only be 0 or 1
            [
                'route' => "$route?is_online_payment=2"
            ],
            // status is not valid value ('new', 'viewed', 'in_process', 'completed', 'canceled')
            [
                'route' => "$route?status={$this->faker->word}"
            ],
            // payment_status is not valid value ('pending', 'paid', 'not_paid')
            [
                'route' => "$route?payment_status={$this->faker->word}"
            ]
        ];

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->getJson($case['route']);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can get order from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::show()
     * @group admin-order-show
     * @group admin-order-show-access
     *
     */
    public function testUserCanGetOrderFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $route = route('admin.orders.show', $order->id);

        $resource = new OrderResource($order);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test user can't get order from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::show()
     * @group admin-order-show
     * @group admin-order-show-no-access
     *
     */
    public function testUserCantGetOrderFromAnotherRestaurant()
    {
        $user = $this->user;
        $anotherRestaurant = Restaurant::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $route = route('admin.orders.show', $order->id);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(403);
    }

    /**
     * Test user can update order from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::update()
     * @group admin-order-update
     * @group admin-order-update-access
     *
     */
    public function testUserCanUpdateOrderFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'status' => 'new',
                    'payment_status' => 'pending',
                ];
            })
            ->create();

        $route = route('admin.orders.update', $order->id);

        //  update with new status and payment_status
        $attributes = [
            'status' => 'viewed',
            'payment_status' => 'paid',
        ];

        $response = $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(200);

        $order = $order->fresh();

        $this->assertDatabaseHas('orders', $attributes);

        $resource = new OrderResource($order);

        $response->assertExactJson([
            'data' => $resource->response()->getData(true),
            'status' => 200,
            'message' => 'Updated'
        ]);
    }

    /**
     * Test user can't update order from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::show()
     * @group admin-order-show
     * @group admin-order-show-no-access
     *
     */
    public function testUserCantUpdateOrderFromAnotherRestaurant()
    {
        $user = $this->user;
        $anotherRestaurant = Restaurant::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id,
                    'status' => 'new',
                    'payment_status' => 'pending',
                ];
            })
            ->create();

        $attributes = [
            'status' => 'viewed',
            'payment_status' => 'paid',
        ];

        $route = route('admin.orders.update', $order->id);

        $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(403);
    }

    /**
     * Test user cant update order with invalid data
     *
     * @covers \App\Http\Controllers\Api\Admin\OrderController::update()
     * @group admin-order-update
     * @group admin-order-update-invalid
     *
     */
    public function testUserCantUpdateOrderWithInvalidData()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'status' => 'new',
                    'payment_status' => 'pending',
                ];
            })
            ->create();

        $testCases = [
            // is_takeaway can only be 0 or 1
            [
                'is_takeaway' => 2
            ],
            // is_online_payment can only be 0 or 1
            [
                'is_online_payment' => 2
            ],
            // person_quantity is not a number
            [
                'person_quantity' => $this->faker->word,
            ],
            // people_for_quantity is not a number
            [
                'people_for_quantity' => $this->faker->word,
            ],
            // status is not valid value ('new', 'viewed', 'in_process', 'completed', 'canceled')
            [
                'status' => $this->faker->word,
            ],
            // payment_status is not valid value ('pending', 'paid', 'not_paid')
            [
                'payment_status' => $this->faker->word,
            ]
        ];

        $route = route('admin.orders.update', $order->id);

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->putJson($route, $case);

            $this->responseValidationFailedTest($response);
        }
    }
}
