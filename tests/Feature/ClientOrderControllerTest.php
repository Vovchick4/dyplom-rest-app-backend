<?php

namespace Tests\Feature;

use App\Events\OrderCreated;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Client;
use Illuminate\Support\Facades\Event;

/**
 * Class ClientOrderControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Client\OrderController.php
 *
 * @covers \App\Http\Controllers\Api\Client\OrderController
 * @group order
 * @group client-order
 */
class ClientOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthorized client can create order
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::store()
     * @group client-order-store
     * @group client-order-store-unauthorize
     *
     */
    public function testUnauthorizedClientCanCreateOrder()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $firstPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => 10
                ];
            })
            ->create();

        $secondPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => 10
                ];
            })
            ->create();

        $firstPlateAmount = 3;
        $secondPlateAmount = 1;
        $orderPrice = $firstPlateAmount * $firstPlate->price + $secondPlateAmount * $secondPlate->price;

        $attributes = [
            'restaurant_id' => $restaurant->id,
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => 4,
            'people_for_quantity' => 1,
            'is_takeaway' => 0,
            'is_online_payment' => 0,
            'plates' => [
                $firstPlate->id => [
                    'amount' => $firstPlateAmount,
                    'price' => $firstPlate->price
                ],
                $secondPlate->id => [
                    'amount' => $secondPlateAmount,
                    'price' => $secondPlate->price
                ]
            ]
        ];

        $route = route('client.orders.store');

        $this->postJson($route, $attributes)
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data', 'status']);

        $order = Order::first();

        unset($attributes['plates']);
        $this->assertDatabaseHas('orders', $attributes);
        $this->assertEquals($orderPrice, $order->price);
        $this->assertTrue($order->plates->contains($firstPlate));
        $this->assertTrue($order->plates->contains($secondPlate));
    }

    /**
     * Test authorized client can create order
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::store()
     * @group client-order-store
     * @group client-order-store-authorize
     *
     */
    public function testAuthorizedClientCanCreateOrder()
    {
        $client = Client::factory()
            ->create();

        $restaurant = Restaurant::factory()
            ->create();

        $firstPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => 10
                ];
            })
            ->create();

        $secondPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => 10
                ];
            })
            ->create();

        $firstPlateAmount = 3;
        $secondPlateAmount = 1;
        $orderPrice = $firstPlateAmount * $firstPlate->price + $secondPlateAmount * $secondPlate->price;

        $attributes = [
            'restaurant_id' => $restaurant->id,
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => 4,
            'people_for_quantity' => 1,
            'is_takeaway' => 0,
            'is_online_payment' => 0,
            'plates' => [
                $firstPlate->id => [
                    'amount' => $firstPlateAmount,
                    'price' => $firstPlate->price,
                    'comment' => Str::random(10)
                ],
                $secondPlate->id => [
                    'amount' => $secondPlateAmount,
                    'price' => $secondPlate->price,
                    'comment' => Str::random(10)
                ]
            ]
        ];

        $route = route('client.orders.store');

        $this->actingAs($client)
            ->postJson($route, $attributes)
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data', 'status']);

        $order = Order::first();

        unset($attributes['plates']);
        $attributes['client_id'] = $client->id;
        $this->assertDatabaseHas('orders', $attributes);
        $this->assertEquals($orderPrice, $order->price);
        $this->assertTrue($order->plates->contains($firstPlate));
        $this->assertTrue($order->plates->contains($secondPlate));
    }

    /**
     * Test client can't create order with invalid data
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::store()
     * @group client-order-store
     * @group client-order-store-invalid
     *
     */
    public function testClientCantCreateOrderWithInvalidData()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $testCases = [
            // is_takeaway can only be 0 or 1
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 2,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // is_online_payment can only be 0 or 1
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 2,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // person_quantity is not a number
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => $this->faker->word,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // people_for_quantity is not a number
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => $this->faker->word,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // restaurant_id is not a number
            [
                'restaurant_id' => $this->faker->word,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // restaurant_id is not an existing restaurant id
            [
                'restaurant_id' => 0,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // table is required and not empty
            [
                'restaurant_id' => $restaurant->id,
                'table' => '',
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => [
                    $plate->id => [
                        'amount' => 1,
                        'price' => $plate->price
                    ]
                ]
            ],
            // plates is not an array
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => $this->faker->word
            ],
            // plates is required and not empty
            [
                'restaurant_id' => $restaurant->id,
                'table' => (string) rand(1, 50),
                'name' => $this->faker->name(),
                'person_quantity' => 4,
                'people_for_quantity' => 1,
                'is_takeaway' => 0,
                'is_online_payment' => 0,
                'plates' => []
            ],
        ];

        $route = route('client.orders.store');

        foreach ($testCases as $case) {
            $response = $this->postJson($route, $case);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test plate quantity decreases after order
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::store()
     * @group client-order-store
     * @group client-order-store-quantity-decrease
     *
     */
    public function testPlateQuantityDecreasesAfterOrder()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $firstPlateQuantity = 10;
        $secondPlateQuantity = 5;

        $firstPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $firstPlateQuantity) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => $firstPlateQuantity
                ];
            })
            ->create();

        $secondPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $secondPlateQuantity) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => $secondPlateQuantity
                ];
            })
            ->create();

        $firstPlateAmount = 3;
        $secondPlateAmount = 1;

        $attributes = [
            'restaurant_id' => $restaurant->id,
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => 4,
            'people_for_quantity' => 1,
            'is_takeaway' => 0,
            'is_online_payment' => 0,
            'plates' => [
                $firstPlate->id => [
                    'amount' => $firstPlateAmount,
                    'price' => $firstPlate->price
                ],
                $secondPlate->id => [
                    'amount' => $secondPlateAmount,
                    'price' => $secondPlate->price
                ]
            ]
        ];

        $route = route('client.orders.store');

        $this->postJson($route, $attributes)
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data', 'status']);

        $firstPlateNewQuantity = $firstPlateQuantity - $firstPlateAmount;
        $secondPlateNewQuantity = $secondPlateQuantity - $secondPlateAmount;

        $firstPlate = $firstPlate->fresh();
        $secondPlate = $secondPlate->fresh();

        $this->assertEquals($firstPlateNewQuantity, $firstPlate->quantity);
        $this->assertEquals($secondPlateNewQuantity, $secondPlate->quantity);
    }

    /**
     * Test client can't order more plates than in stock
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::store()
     * @group client-order-store
     * @group client-order-store-quantity-out-of-stock
     *
     */
    public function testClientCantOrderMorePlatesThanInStock()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $firstPlateQuantity = 1;
        $secondPlateQuantity = 2;

        $firstPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $firstPlateQuantity) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => $firstPlateQuantity
                ];
            })
            ->create();

        $secondPlate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $secondPlateQuantity) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => $secondPlateQuantity
                ];
            })
            ->create();

        $firstPlateAmount = 3;
        $secondPlateAmount = 4;

        $attributes = [
            'restaurant_id' => $restaurant->id,
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => 4,
            'people_for_quantity' => 1,
            'is_takeaway' => 0,
            'is_online_payment' => 0,
            'plates' => [
                $firstPlate->id => [
                    'amount' => $firstPlateAmount,
                    'price' => $firstPlate->price
                ],
                $secondPlate->id => [
                    'amount' => $secondPlateAmount,
                    'price' => $secondPlate->price
                ]
            ]
        ];

        $route = route('client.orders.store');

        $this->postJson($route, $attributes)
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'data', 'status', 'errors']);
    }

    /**
     * Test client can get order
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::show()
     * @group client-order-show
     * @group client-order-show-existent
     *
     */
    public function testClientCanGetOrder()
    {
        $restaurant = Restaurant::factory()
            ->create();

        Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $order = Order::first();

        $route = route('client.orders.show', $order->id);

        $resource = new OrderResource($order);

        $this->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test client can't get non-existent order
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::show()
     * @group client-order-show
     * @group client-order-show-non-existent
     *
     */
    public function testClientCantGetNonexistentOrder()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $route = route('client.orders.show', 0);

        $this->getJson($route)
            ->assertStatus(404);
    }

    /**
     * Test authorized client can get orders
     *
     * @covers \App\Http\Controllers\Api\Client\OrderController::index()
     * @group client-order-index
     *
     */
    public function testAuthorizedClientCanGetOrders()
    {
        $client = Client::factory()
            ->state(function (array $attributes) {
                return [
                    'verified_at' => Carbon::now(),
                ];
            })
            ->create();

        $anotherClient = Client::factory()
            ->create();

        $restaurant = Restaurant::factory()
            ->create();

        Order::factory()
            ->state(function (array $attributes) use ($restaurant, $client) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'client_id' => $client->id,
                ];
            })
            ->count(3)
            ->create();

        Order::factory()
            ->state(function (array $attributes) use ($restaurant, $anotherClient) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'client_id' => $anotherClient->id,
                ];
            })
            ->count(2)
            ->create();

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->where('client_id', $client->id)
            ->paginate(9);

        $resource = new OrderCollection($orders);
        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = 'OK';

        $route = route('client.orders.index');

        $this->actingAs($client, 'client')
            ->getJson($route)
            ->assertStatus(200)
            ->assertExactJson($data);
    }


    /**
     * Test OrderCreated event dispatches on message creating
     *
     * @covers \App\Events\OrderCreated
     * @group client-order-created-event
     *
     */
    public function testMessageSentEventDispatches()
    {
        Event::fake([
            OrderCreated::class
        ]);

        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'quantity' => 10
                ];
            })
            ->create();

        $plateAmount = 3;

        $attributes = [
            'restaurant_id' => $restaurant->id,
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => 4,
            'people_for_quantity' => 1,
            'is_takeaway' => 0,
            'is_online_payment' => 0,
            'plates' => [
                $plate->id => [
                    'amount' => $plateAmount,
                    'price' => $plate->price
                ]
            ]
        ];

        $route = route('client.orders.store');

        $this->postJson($route, $attributes);

        Event::assertDispatched(OrderCreated::class);
    }
}
