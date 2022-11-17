<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Http\Resources\PlateResource;
use App\Http\Resources\PlateCollection;

/**
 * Class ClientPlateControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Client\PlateController.php
 *
 * @covers \App\Http\Controllers\Api\Client\PlateController
 * @group plate
 * @group client-plate
 */
class ClientPlateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }
    /**
     * Test show single active plate
     *
     * @covers \App\Http\Controllers\Api\Client\PlateController::show()
     * @group client-plate-show
     *
     */
    public function testShowActivePlate()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $route = route('client.plates.show', $plate->id);

        $resource = new PlateResource($plate);

        $this->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test show single inactive plate
     *
     * @covers \App\Http\Controllers\Api\Client\PlateController::show()
     * @group client-plate-show-inactive
     *
     */
    public function testShowInactivePlate()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 0
                ];
            })
            ->create();

        $route = route('client.plates.show', $plate->id);

        $this->get($route)
            ->assertNotFound();
    }

    /**
     * Test get restaurant plates by category
     *
     * @covers \App\Http\Controllers\Api\Client\PlateController::index()
     *
     * @group client-plate-index
     * @group client-plate-index-valid
     *
     */
    public function testGetPlates()
    {
        $restaurant = Restaurant::factory()
            ->create();

        Category::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $categoryId = Category::first()->id;

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant, $categoryId) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $categoryId
                ];
            })
            ->create();

        $secondCategoryId = Category::latest()->first()->id;

        Plate::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant, $secondCategoryId) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $secondCategoryId
                ];
            })
            ->create();

        $plates = Plate::active()
            ->where('restaurant_id', $restaurant->id)
            ->where('category_id', $categoryId)
            ->paginate(9);

        $route = route('client.plates.index', $restaurant->id) . "?category_id=$categoryId&per_page=9";
        $resource = new PlateCollection($plates);

        $data = $resource->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = 'OK';

        $this->getJson($route)
            ->assertStatus(200)
            ->assertExactJson($data);
    }

    /**
     * Test can't get restaurant plates with invalid query
     *
     * @covers \App\Http\Controllers\Api\Client\PlateController::index()
     *
     * @group client-plate-index
     * @group client-plate-index-invalid
     *
     */
    public function testCantGetPlatesWithInvalidQuery()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $route = route('client.plates.index', $restaurant->id);

        $testCases = [
            // category_id is not an existing category id
            [
                'route' => "$route?category_id=0"
            ],
            // per_page is not a number
            [
                'route' => "$route?per_page={$this->faker->word}"
            ],
        ];

        foreach ($testCases as $case) {
            $response = $this->getJson($case['route']);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test search restaurant plates
     *
     * @covers \App\Http\Controllers\Api\Client\PlateController::search()
     *
     * @group client-plate-search
     *
     */
    public function testSearchPlates()
    {
        $restaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'name' => 'Pizza'
                ];
            })
            ->create();

        Plate::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $category->id
                ];
            })
            ->create();

        Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'name' => 'Nameforsearch',
                    'category_id' => $category->id
                ];
            })
            ->create();

        $testCases = [
            // plate name substring
            'search',
            // category name substring
            'pizz'
        ];

        foreach ($testCases as $case) {
            $route = route('client.plates.search', ['restaurant_id' => $restaurant->id, 'searchText' => $case]);

            $plates = Plate::select('plates.*')
                ->distinct('plates.id')
                ->join('categories', 'categories.id', '=', 'plates.category_id')
                ->where('plates.restaurant_id', $restaurant->id)
                ->active()
                ->where(function ($query) use ($case) {
                    $query->where('plates.name', 'like', "%$case%")
                        ->orWhere('categories.name', 'like', "%$case%");
                })
                ->get();

            $resource = PlateResource::collection($plates);

            $this->getJson($route)
                ->assertStatus(200)
                ->assertExactJson([
                    'data' => $resource->response()->getData(true),
                    'status' => 200,
                    'message' => 'OK'
                ]);
        }
    }
}
