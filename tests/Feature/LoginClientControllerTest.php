<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class LoginControllerTest
 * Tests for @see App\Http\Controllers\Api\Client\Auth\LoginController.php
 *
 * @group login
 */
class LoginClientControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * * Test client login.
     * @covers App/Http/Controllers/Api/Client/Auth/LoginController.php
     *
     * @group client-login
     */
    public function testLogin()
    {
        $uniqueClient = Client::factory()
            ->create();

        $errorCases = [
            [
                // email required
                'password' => 'password',
            ],
            [
                // email exists
                'email' => $this->faker->unique()->safeEmail,
                'password' => 'password',
            ],
            [
                // password required
                'email' => $uniqueClient->email,
            ],
            [
                // password string
                'email' => $uniqueClient->email,
                'password' => (int)$this->faker->numberBetween(100000, 9999999),
            ],
            [
                // password min:6
                'email' => $uniqueClient->email,
                'password' => $this->faker->password(1, 5),
            ],
            [
                // password max:20
                'email' => $uniqueClient->email,
                'password' => $this->faker->password(21, 255),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->json('POST', route('client.login'), $errorCase);
            $this->responseValidationFailedTest($response);
        }

        $client = Client::factory()
            ->create()
            ->toArray();

        $client['email_or_phone'] = $client['email'];
        $client['password'] = 'password';

        $response = $this->json('POST', route('client.login'), $client);

        $response->assertStatus(200);

        $data = $response->decodeResponseJson();

        $this->assertNotNull($data['token']['accessToken']);
        $this->assertIsString($data['token']['accessToken']);
    }

    /**
     * * Test if incorrect login
     * @covers App/Http/Controllers/Api/Client/Auth/LoginController.php
     *
     * @group client-incorrect-login
     */
    public function testIncorrectLogin()
    {
        $client = Client::factory()
            ->create()
            ->toArray();

        $client['email_or_phone'] = $client['email'];
        $client['password'] = 'incorrect';

        $response = $this->json('POST', route('client.login'), $client);
        $response->assertStatus(422);
    }
}
