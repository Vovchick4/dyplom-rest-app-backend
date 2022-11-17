<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Plate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Class CategoryTest
 *
 * Tests for @see App\Models\Category.php
 *
 * @covers \App\Models\Category
 * @group category
 * @group model-category
 */
class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database columns
     *
     * Test if categories table has expected columns
     *
     * @group model-category-database-columns
     *
     */
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('categories', [
                'id',
                'name',
                'image',
                'active',
                'parent_id',
                'restaurant_id',
                'created_at'
            ]),
            1
        );
    }

    /**
     * Test category link
     *
     * Test if category link property exists
     *
     * @group model-category-has-link
     *
     */
    public function testHasLinkProperty()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $this->assertIsString($category->link);
    }

    /**
     * Test active scope
     *
     * Test if category active scope exists
     *
     * @group model-category-has-active-scope
     *
     */
    public function testHasActiveScope()
    {
        $this->assertInstanceOf(Builder::class, Category::active());
    }

    /**
     * Test inactive scope
     *
     * Test if category inactive scope exists
     *
     * @group model-category-has-inactive-scope
     *
     */
    public function testHasInactiveScope()
    {
        $this->assertInstanceOf(Builder::class, Category::inactive());
    }

    /**
     * Test has restaurant
     *
     * Test if category model has restaurant relationship
     *
     * @group model-category-has-restaurant
     *
     */
    public function testHasRestaurantRelationship()
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

        $this->assertInstanceOf(Restaurant::class, $category->restaurant);
    }

    /**
     * Test has plates
     *
     * Test if category model has plates relationship
     *
     * @group model-category-has-plates
     *
     */
    public function testHasPlatesRelationship()
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

        $this->assertEquals(1, $category->plates->count());
        $this->assertTrue($category->plates->contains($plate));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $category->plates);
    }
}
