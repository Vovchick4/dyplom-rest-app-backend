<?php

namespace Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SocialControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Admin\Auth\SocialController.php
 *
 * @covers \App\Http\Controllers\Api\Admin\Auth\SocialController
 * @group social
 */
class SocialControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login by facebook
     *
     * @covers \App\Http\Controllers\Api\Admin\Auth\SocialController::redirectToProvider()
     * @group facebook-login
     *
     */
    public function testSocialiteFacebookLogin()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')
            ->andReturn(1234567890)
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10) . '@test.com')
            ->shouldReceive('getNickname')
            ->andReturn('Pseudo')
            ->shouldReceive('getName')
            ->andReturn('Arlette Laguiller')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('facebook')->andReturn($provider);

        $this->get(route('admin.social.redirect', ['provider' => 'facebook']));
            // TODO need to create api keys
            //->assertStatus(302)
            //->assertRedirect(route('admin.social.redirect', ['provider' => 'facebook']));
    }
}
