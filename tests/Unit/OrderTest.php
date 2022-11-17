<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;

/**
 * Class OrderTest
 *
 * Tests for @see App\Models\Order.php
 *
 * @covers \App\Models\Order
 * @group order
 * @group model-order
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if orders table has expected columns
     *
     * @group model-order-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders', [
                'id',
                'restaurant_id',
                'client_id',
                'status',
                'payment_status',
                'table',
                'name',
                'price',
                'person_quantity',
                'people_for_quantity',
                'is_takeaway',
                'is_online_payment',
                'created_at',
                'code',
                'payment_id',
                'payment_method',
                'payment_response',
            ]),
            1
        );
    }

    /**
     * Test database pivot table columns
     *
     * Test if orders table has expected columns
     *
     * @group model-order-database-pivot-columns
     *
     */
    public function testDatabasePivotTableHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('order_plate', [
                'order_id',
                'plate_id',
                'amount',
                'comment',
            ]),
            1
        );
    }

    /**
     * Test has restaurant
     *
     * Test if order model has restaurant relationship
     *
     * @group model-order-has-restaurant
     *
     */
    public function testHasRestaurantRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $this->assertInstanceOf(Restaurant::class, $order->restaurant);
    }

    /**
     * Test has client
     *
     * Test if order model has client relationship
     *
     * @group model-order-has-client
     *
     */
    public function testHasClientRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $client = Client::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant, $client) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'client_id' => $client->id,
                ];
            })
            ->create();

        $this->assertInstanceOf(Client::class, $order->client);
    }

    /**
     * Test has plates
     *
     * Test if order model has plates relationship
     *
     * @group model-order-has-plates
     *
     */
    public function testHasPlatesRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                ];
            })
            ->create();

        $order->plates()->attach($plate->id, ['price' => $plate->price]);

        $this->assertEquals(1, $order->plates->count());
        $this->assertTrue($order->plates->contains($plate));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $order->plates);
    }
}
