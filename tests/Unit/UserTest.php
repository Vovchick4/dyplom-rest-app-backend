<?php

namespace Tests\Unit;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserTest
 *
 * Tests for @see App\Models\User.php
 *
 * @covers \App\Models\User
 * @group user
 * @group model-user
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if users table has expected columns
     *
     * @group model-user-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id',
                'name',
                'email',
                'password',
                'restaurant_id',
                'lastname',
                'image',
                'role',
                'fb_id',
                'google_id',
                'email_verified_at',
                'created_at'
            ]),
            1
        );
    }

    /**
     * Test has restaurant
     *
     * Test if user model has restaurant relationship
     *
     * @group model-user-has-restaurant
     *
     */
    public function testHasRestaurantRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $this->assertInstanceOf(Restaurant::class, $user->restaurant);
    }

}
