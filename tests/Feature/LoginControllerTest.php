<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class LoginControllerTest
 * Tests for @see App\Http\Controllers\Api\Admin\Auth\LoginController.php
 *
 * @group login
 */
class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * * Test admin login.
     * @covers App/Http/Controllers/Api/Admin/Auth/LoginController.php
     *
     * @group admin-login
     */
    public function testLogin()
    {
        $restaurant = Restaurant::factory()->create();
        $uniqueUser = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $errorCases = [
            [
                // email required
                'password' => 'password',
            ],
            [
                // email is has type "email"
                'email' => $this->faker->userName(),
                'password' => 'password',
            ],
            [
                // email exists
                'email' => $this->faker->unique()->safeEmail,
                'password' => 'password',
            ],
            [
                // password required
                'email' => $uniqueUser->email,
            ],
            [
                // password string
                'email' => $uniqueUser->email,
                'password' => (int)$this->faker->numberBetween(100000, 9999999),
            ],
            [
                // password min:6
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(1, 5),
            ],
            [
                // password max:20
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(21, 255),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->json('POST', route('admin.login'), $errorCase);
            $this->responseValidationFailedTest($response);
        }

        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create()
            ->toArray();

        $user['password'] = 'password';

        $response = $this->json('POST', route('admin.login'), $user);

        $response->assertStatus(200);

        $data = $response->decodeResponseJson();

        $this->assertNotNull($data['token']['accessToken']);
        $this->assertIsString($data['token']['accessToken']);
    }

    /**
     * * Test if incorrect login
     * @covers App/Http/Controllers/Api/Admin/Auth/LoginController.php
     *
     * @group admin-incorrect-login
     */
    public function testIncorrectLogin()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create()
            ->toArray();


        $user['password'] = 'incorrect';

        $response = $this->json('POST', route('admin.login'), $user);
        $response->assertStatus(403);
    }
}
