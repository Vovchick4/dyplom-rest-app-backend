<?php

namespace Feature;

use App\Models\Restaurant;
use App\Models\User;
use Tests\TestCase;

/**
 * Class UserControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Admin\UserController.php
 *
 * @covers \App\Http\Controllers\Api\Admin\UserController
 * @group user
 */
class UserControllerTest extends TestCase
{
    /**
     * Test user show
     *
     * @covers \App\Http\Controllers\Api\Admin\UserController::show()
     * @group user-show
     *
     */
    public function testRetrieveUserSuccessfully()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $this->actingAs($user, 'user');

        $User = User::factory()->create([
            "name" => "Susan",
            "lastname" => "Wojcicki",
            "role" => "owner",
            "email" => "test@test.com",
            "phone" => "+336688767687678",
            'restaurant_id' => $restaurant->id
        ]);

        $this->json('GET', route('admin.users.show', $User), [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status']);
    }

    /**
     * Test user update
     *
     * @covers \App\Http\Controllers\Api\Admin\UserController::update()
     * @group user-update
     *
     */
    public function testUserUpdatedSuccessfully()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $this->actingAs($user, 'user');

        $updateUser = User::factory()->create([
            "name" => "Susan",
            "lastname" => "Wojcicki",
            "role" => "owner",
            "email" => "test@test.com",
            "phone" => "+336688767687678",
            'restaurant_id' => $restaurant->id
        ]);

        $this->json('PATCH', route('admin.users.update', $updateUser), ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status']);
    }

    /**
     * Test user delete
     *
     * @covers \App\Http\Controllers\Api\Admin\UserController::destroy()
     * @group user-delete
     *
     */
    public function testDeleteUserSuccessfully()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $this->actingAs($user, 'user');

        $this->json('DELETE', route('admin.users.destroy', $user), [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
