<?php

namespace Feature;

use App\Models\Client;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Class \App\Http\Controllers\Api\Client\ClientControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Client\ClientController.php
 *
 * @covers \App\Http\Controllers\Api\Client\ClientController
 * @group client
 */
class ClientControllerTest extends TestCase
{
    /**
     * Test client show
     *
     * @covers \App\Http\Controllers\Api\Client\ClientController::show()
     * @group client-show
     *
     */
    public function testRetrieveClientSuccessfully()
    {
        $client = Client::factory()
            ->state(function (array $attributes) {
                return [
                    'verified_at' => Carbon::now(),
                ];
            })
            ->create();

        $this->actingAs($client, 'client');

        $Client = Client::factory()->create([
            "name" => "Susan",
            "email" => "test@test.com",
            "phone" => "+336688767687678",
        ]);

        $this->json('GET', route('client.clients.show', $Client), [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status']);
    }

    /**
     * Test client update
     *
     * @covers \App\Http\Controllers\Api\Client\ClientController::update()
     * @group client-update
     *
     */
    public function testClientUpdatedSuccessfully()
    {
        $client = Client::factory()
            ->state(function (array $attributes) {
                return [
                    'verified_at' => Carbon::now(),
                ];
            })
            ->create();

        $this->actingAs($client, 'client');

        $updateClient = Client::factory()->create([
            "name" => "Susan",
            "email" => "test@test.com",
            "phone" => "+336688767687678"
        ]);

        $this->json('PATCH', route('client.clients.update', $updateClient), ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status']);
    }
}
