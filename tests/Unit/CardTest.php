<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Class CardTest
 *
 * Tests for @see App\Models\Card.php
 *
 * @covers \App\Models\Card
 * @group card
 * @group model-card
 */
class CardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if cards table has expected columns
     *
     * @group model-user-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('cards', [
                'id',
                'client_id',
                'name',
                'number',
                'cvv',
                'expiration_date',
                'default',
                'created_at'
            ]),
            1
        );
    }

    /**
     * Test has client
     *
     * Test if card model has client relationship
     *
     * @group model-card-has-client
     *
     */
    public function testHasClientRelationship()
    {
        $client = Client::factory()
            ->create();

        $card = Card::factory()
            ->state(function (array $attributes) use ($client) {
                return [
                    'client_id' => $client->id,
                ];
            })
            ->create();

        $this->assertInstanceOf(Client::class, $card->client);
    }
}
