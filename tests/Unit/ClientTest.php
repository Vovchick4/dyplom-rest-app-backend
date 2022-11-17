<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Client;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Class ClientTest
 *
 * Tests for @see App\Models\Client.php
 *
 * @covers \App\Models\Client
 * @group client
 * @group model-client
 */
class ClientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if clients table has expected columns
     *
     * @group model-client-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('clients', [
                'id',
                'name',
                'email',
                'phone',
                'password',
                'payment_method',
                'fb_id',
                'google_id',
                'verified_at',
                'created_at'
            ]),
            1
        );
    }

    /**
     * Test has orders
     *
     * Test if client model has orders relationship
     *
     * @group model-client-has-orders
     *
     */
    public function testHasOrdersRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $client = Client::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant, $client) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'client_id' => $client->id
                ];
            })
            ->create();

        $this->assertEquals(1, $client->orders->count());
        $this->assertTrue($client->orders->contains($order));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $client->orders);
    }

    /**
     * Test has cards
     *
     * Test if client model has cards relationship
     *
     * @group model-client-has-cards
     *
     */
    public function testHasCardsRelationship()
    {
        $client = Client::factory()
            ->create();

        $card = Card::factory()
            ->state(function (array $attributes) use ($client) {
                return [
                    'client_id' => $client->id
                ];
            })
            ->create();

        $this->assertEquals(1, $client->cards->count());
        $this->assertTrue($client->cards->contains($card));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $client->cards);
    }
}
