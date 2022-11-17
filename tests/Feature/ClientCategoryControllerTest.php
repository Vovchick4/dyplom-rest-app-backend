<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Restaurant;
use App\Http\Resources\CategoryResource;

/**
 * Class ClientCategoryControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Client\CategoryController.php
 *
 * @covers \App\Http\Controllers\Api\Client\CategoryController
 * @group category
 * @group client-category
 */
class ClientCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test show single active category
     *
     * @covers \App\Http\Controllers\Api\Client\CategoryController::show()
     * @group client-category-show
     *
     */
    public function testShowActiveCategory()
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

        $route = route('client.categories.show', $category->id);

        $resource = new CategoryResource($category);

        $this->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test show single inactive category
     *
     * @covers \App\Http\Controllers\Api\Client\CategoryController::show()
     * @group client-category-show-inactive
     *
     */
    public function testShowInactiveCategory()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 0
                ];
            })
            ->create();

        $route = route('client.categories.show', $category->id);

        $this->get($route)
            ->assertNotFound();
    }

    /**
     * Test get restaurant categories
     *
     * @covers \App\Http\Controllers\Api\Client\CategoryController::index()
     *
     * @group client-category-index
     *
     */
    public function testGetCategories()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $categories = Category::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $route = route('client.categories.index', $restaurant->id);

        $resource = CategoryResource::collection($categories);

        $this->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }
}
