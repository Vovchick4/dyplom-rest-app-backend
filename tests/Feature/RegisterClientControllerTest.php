<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class RegisterController
 * Tests for @see App\Http\Controllers\Api\Client\Auth\RegisterController.php
 *
 * @group register
 */
class RegisterClientControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * * Test client register.
     * @covers App/Http/Controllers/Api/Client/Auth/RegisterController.php
     *
     * @group client-register
     */
    public function testRegister()
    {
        $uniqueClient = Client::factory()
            ->create();

        $errorCases = [
            [
                // email required
                'password' => $this->faker->password(6, 20),
            ],
            [
                // email is has type "email"
                'email' => $this->faker->name(),
                'password' => $this->faker->password(6, 20),
            ],
            [
                // email unique
                'email' => $uniqueClient->email,
                'password' => $this->faker->password(6, 20),
            ],
            [
                // password required
                'email' => $this->faker->unique()->safeEmail,
            ],
            [
                // password string
                'email' => $this->faker->unique()->safeEmail,
                'password' => (int)$this->faker->numberBetween(100000, 9999999),
            ],
            [
                // password min:6
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(1, 5),
            ],
            [
                // password max:20
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(21, 255),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->json('POST', route('client.register'), $errorCase);
            $this->responseValidationFailedTest($response);
        }

        $client = Client::factory()
            ->make()
            ->toArray();

        $client['password'] = 'password';

        $response = $this->json('POST', route('client.register'), $client);
        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', ['email' => $client['email']]);
    }
}
