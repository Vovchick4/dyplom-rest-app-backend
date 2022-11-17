<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class RegisterController
 * Tests for @see App\Http\Controllers\Api\Admin\Auth\RegisterController.php
 *
 * @group register
 */
class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * * Test admin register.
     * @covers App/Http/Controllers/Api/Admin/Auth/RegisterController.php
     *
     * @group admin-register
     */
    public function testRegister()
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
                'password' => $this->faker->password(6, 20),
                'restaurant_name' => $this->faker->word,
            ],
            [
                // email is has type "email"
                'email' => $this->faker->userName(),
                'password' => $this->faker->password(6, 20),
                'restaurant_name' => $this->faker->word,
            ],
            [
                // email unique
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(6, 20),
                'restaurant_name' => $this->faker->word,
            ],
            [
                // password required
                'email' => $this->faker->unique()->safeEmail,
                'restaurant_name' => $this->faker->word,
            ],
            [
                // restaurant_name required
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(6, 20),
            ],
            [
                // password string
                'email' => $this->faker->unique()->safeEmail,
                'password' => (int)$this->faker->numberBetween(100000, 9999999),
                'restaurant_name' => $this->faker->word,
            ],
            [
                // password min:6
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(1, 5),
                'restaurant_name' => $this->faker->word,
            ],
            [
                // password max:20
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(21, 255),
                'restaurant_name' => $this->faker->word,
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->json('POST', route('admin.register'), $errorCase);
            $this->responseValidationFailedTest($response);
        }

        $user = User::factory()
            ->make()
            ->toArray();

        $user['password'] = 'password';
        $user['restaurant_name'] = $this->faker->word;

        $response = $this->json('POST', route('admin.register'), $user);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }
}
