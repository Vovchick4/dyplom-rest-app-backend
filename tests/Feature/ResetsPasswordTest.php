<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ResetPasswordController
 * Tests for @see App\Http\Controllers\Api\Admin\Auth\ResetPasswordController.php
 *
 * @group reset-password
 */
class ResetsPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * * Displays the reset password request form.
     * @covers App/Http/Controllers/Api/Admin/Auth/ResetPasswordController.php
     *
     * @group admin-displays-reset-password
     */
    public function testDisplaysPasswordResetRequestForm()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $response = $this->post(route('admin.reset.password'), [
            'email' => $user->email,
        ]);
        $response->assertStatus(200);
    }

    /**
     * * Allows a user to reset their password.
     * @covers App/Http/Controllers/Api/Admin/Auth/ResetPasswordController.php
     *
     * @group admin-changes-password
     */
    public function testChangesAUsersPassword()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $token = Password::createToken($user);

        $response = $this->post('password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
