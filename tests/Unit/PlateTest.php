<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Class PlateTest
 *
 * Tests for @see App\Models\Plate.php
 *
 * @covers \App\Models\Plate
 * @group plate
 * @group model-plate
 */
class PlateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if plates table has expected columns
     *
     * @group model-plate-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('plates', [
                'id',
                'name',
                'description',
                'category_id',
                'restaurant_id',
                'image',
                'active',
                'quantity',
                'weight',
                'created_at',
            ]),
            1
        );
    }

    /**
     * Test plate link
     *
     * Test if plate link property exists
     *
     * @group model-plate-has-link
     *
     */
    public function testHasLinkProperty()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'category_id' => $category->id,
                    'active' => 1,
                ];
            })
            ->create();

        $this->assertIsString($plate->link);
    }

    /**
     * Test active scope
     *
     * Test if plate active scope exists
     *
     * @group model-plate-has-active-scope
     *
     */
    public function testHasActiveScope()
    {
        $this->assertInstanceOf(Builder::class, Plate::active());
    }

    /**
     * Test inactive scope
     *
     * Test if plate inactive scope exists
     *
     * @group model-plate-has-inactive-scope
     *
     */
    public function testHasInactiveScope()
    {
        $this->assertInstanceOf(Builder::class, Plate::inactive());
    }

    /**
     * Test has restaurant
     *
     * Test if plate model has restaurant relationship
     *
     * @group model-plate-has-restaurant
     *
     */
    public function testHasRestaurantRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                ];
            })
            ->create();

        $this->assertInstanceOf(Restaurant::class, $plate->restaurant);
    }

    /**
     * Test has category
     *
     * Test if plate model has category relationship
     *
     * @group model-plate-has-category
     *
     */
    public function testHasCategoryRelationship()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();


        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'category_id' => $category->id,
                    'active' => 1,
                ];
            })
            ->create();

        $this->assertInstanceOf(Category::class, $plate->category);
    }
}
