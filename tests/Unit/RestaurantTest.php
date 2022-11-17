<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Plate;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * Class RestaurantTest
 *
 * Tests for @see App\Models\Restaurant.php
 *
 * @covers \App\Models\Restaurant
 * @group restaurant
 * @group model-restaurant
 */
class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if restaurants table has expected columns
     *
     * @group model-restaurant-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('restaurants', [
                'id',
                'name',
                'address',
                'phone',
                'logo',
                'slug',
                'created_at'
            ]),
            1
        );
    }

    /**
     * Test has plates
     *
     * Test if restaurant model has plates relationship
     *
     * @group model-restaurant-has-plates
     *
     */
    public function testHasPlatesRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $this->assertEquals(1, $restaurant->plates->count());
        $this->assertTrue($restaurant->plates->contains($plate));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $restaurant->plates);
    }

    /**
     * Test has categories
     *
     * Test if restaurant model has categories relationship
     *
     * @group model-restaurant-has-categories
     *
     */
    public function testHasCategoriesRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $this->assertEquals(1, $restaurant->categories->count());
        $this->assertTrue($restaurant->categories->contains($category));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $restaurant->categories);
    }

    /**
     * Test has orders
     *
     * Test if restaurant model has orders relationship
     *
     * @group model-restaurant-has-orders
     *
     */
    public function testHasOrdersRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $order = Order::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $this->assertEquals(1, $restaurant->orders->count());
        $this->assertTrue($restaurant->orders->contains($order));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $restaurant->orders);
    }

    /**
     * Test has users
     *
     * Test if restaurant model has users relationship
     *
     * @group model-restaurant-has-users
     *
     */
    public function testHasUsersRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $this->assertEquals(1, $restaurant->users->count());
        $this->assertTrue($restaurant->users->contains($user));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $restaurant->users);
    }

    /**
     * Test slug creates
     *
     * Test if restaurant slug creates after restaurant creation
     *
     * @group model-restaurant-slug-creates
     *
     */
    public function testSlugCreates()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $this->assertIsString($restaurant->slug);
    }
}
