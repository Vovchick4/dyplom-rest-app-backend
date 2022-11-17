<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PlateResource;
use App\Models\Plate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Class AdminCategoryControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Admin\CategoryController.php
 *
 * @covers \App\Http\Controllers\Api\Admin\CategoryController
 * @group category
 * @group admin-category
 */
class AdminCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private $restaurant;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $restaurant = Restaurant::factory()
            ->create();

        $this->restaurant = $restaurant;

        $this->user = User::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();
    }

    /**
     * Test user can get categories
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::index()
     * @group admin-category-index
     * @group admin-category-index-valid
     *
     */
    public function testUserCanGetCategories()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $activeCategories = Category::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();


        $inactiveCategories = Category::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 0
                ];
            })
            ->create();

        $categories = Category::get();

        $route = route('admin.categories.index');

        $testCases = [
            // all categories
            [
                'categories' => $categories,
                'route' => $route
            ],
            // active categories
            [
                'categories' => $activeCategories,
                'route' => "$route?active=1"
            ],
            // inactive categories
            [
                'categories' => $inactiveCategories,
                'route' => "$route?active=0"
            ],
        ];

        foreach ($testCases as $case) {
            $resource = CategoryResource::collection($case['categories']);

            $this->actingAs($user, 'user')
                ->getJson($case['route'])
                ->assertStatus(200)
                ->assertExactJson([
                    'data' => $resource->response()->getData(true),
                    'status' => 200,
                    'message' => 'OK'
                ]);
        }
    }

    /**
     * Test user can't get categories with invalid query
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::index()
     * @group admin-category-index
     * @group admin-category-index-invalid
     *
     */
    public function testUserCantGetCategoriesWithInvalidQuery()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        Category::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                ];
            })
            ->create();

        $route = route('admin.categories.index');

        $testCases = [
            // active can only be 0 or 1
            [
                'route' => "$route?active=2"
            ],
            // parent_id is not a number
            [
                'route' => "$route?parent_id={$this->faker->word}"
            ],
            // parent_id is not an existing category id
            [
                'route' => "$route?parent_id=0"
            ],
        ];

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->getJson($case['route']);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can get category from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::show()
     * @group admin-category-show
     * @group admin-category-show-access
     *
     */
    public function testUserCanGetCategoryFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $route = route('admin.categories.show', $category->id);

        $resource = new CategoryResource($category);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test user can't get category from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::show()
     * @group admin-category-show
     * @group admin-category-show-no-access
     *
     */
    public function testUserCantGetCategoryFromAnotherRestaurant()
    {
        $user = $this->user;
        $anotherRestaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $route = route('admin.categories.show', $category->id);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(403);
    }

    /**
     * Test user can create category
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::store()
     * @group admin-category-store
     * @group admin-category-store-valid
     *
     */
    public function testUserCanCreateCategory()
    {
        $user = $this->user;
        $image = UploadedFile::fake()->image('test-image.jpg');

        $attributes = [
            'name' => $this->faker->word,
            'image' => $image,
        ];

        $this->actingAs($user, 'user')
            ->postJson(route('admin.categories.store'), $attributes)
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data', 'status']);

        $category = Category::first();

        Storage::disk('images')->assertExists($category->image);

        $array = $category->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);

        $this->assertDatabaseHas('categories', $array);
    }

    /**
     * Test user cant create category with invalid data
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::store()
     * @group admin-category-store
     * @group admin-category-store-invalid
     *
     */
    public function testUserCantCreateCategoryWithInvalidData()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $image = UploadedFile::fake()->image('test-image.jpg');
        $incorrectImage = UploadedFile::fake()->image('test-image.svg');
        $takenName = $this->faker->word;

        Category::factory()
            ->state(function (array $attributes) use ($restaurant, $takenName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'name' => $takenName
                ];
            })
            ->create();

        $testCases = [
            // image required
            [
                'name' => $this->faker->word,
            ],
            // name required
            [
                'image' => $image,
            ],
            // name has already been taken
            [
                'name' => $takenName,
                'image' => $image,
            ],
            // image mime type incorrect
            [
                'name' => $this->faker->word,
                'image' => $incorrectImage,
            ],
            // image is not a file
            [
                'name' => $this->faker->word,
                'image' => $this->faker->word,
            ],
            // active can only be 0 or 1
            [
                'name' => $this->faker->word,
                'image' => $image,
                'active' => 2
            ],
        ];

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->postJson(route('admin.categories.store'), $case);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can update category from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::update()
     * @group admin-category-update
     * @group admin-category-update-access
     *
     */
    public function testUserCanUpdateCategoryFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $imageName = 'test-image.jpg';
        $image = UploadedFile::fake()->image($imageName);
        $newImage = UploadedFile::fake()->image('test-new-image.jpg');

        Storage::disk('images')->putFileAs('/', $image, $imageName);

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant, $imageName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'image' => $imageName
                ];
            })
            ->create();

        $route = route('admin.categories.update', $category->id);

        // update with same name
        $attributes = [
            'name' => $category->name
        ];

        $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(200);

        //  update with new name and image
        $attributes = [
            'name' => $this->faker->word,
            'image' => $newImage
        ];

        $response = $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(200);

        $category = $category->fresh();

        Storage::disk('images')->assertExists($category->image);
        Storage::disk('images')->assertMissing($imageName);

        $array = $category->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);

        $this->assertDatabaseHas('categories', $array);

        $resource = new CategoryResource($category);

        $response->assertExactJson([
            'data' => $resource->response()->getData(true),
            'status' => 200,
            'message' => 'Updated'
        ]);
    }

    /**
     * Test user cant update category from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::update()
     * @group admin-category-update
     * @group admin-category-update-no-access
     *
     */
    public function testUserCantUpdateCategoryFromAnotherRestaurant()
    {
        $user = $this->user;

        $anotherRestaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $attributes = [
            'name' => $this->faker->word,
        ];

        $route = route('admin.categories.update', $category->id);

        $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(403);
    }

    /**
     * Test user cant update category with invalid data
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::update()
     * @group admin-category-update
     * @group admin-category-update-invalid
     *
     */
    public function testUserCantUpdateCategoryWithInvalidData()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $incorrectImage = UploadedFile::fake()->image('test-image.svg');
        $takenName = $this->faker->word;

        Category::factory()
            ->state(function (array $attributes) use ($restaurant, $takenName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'name' => $takenName
                ];
            })
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $testCases = [
            // image mime type incorrect
            [
                'name' => $this->faker->word,
                'image' => $incorrectImage,
            ],
            // image is not a file
            [
                'name' => $this->faker->word,
                'image' => $this->faker->word,
            ],
            // name less then 2 chars
            [
                'name' => substr($this->faker->word, 0, 1),
            ],
            // name has already been taken
            [
                'name' => $takenName
            ],
            // active can only be 0 or 1
            [
                'active' => 2
            ],
        ];

        $route = route('admin.categories.update', $category->id);

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->putJson($route, $case);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can delete category from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::destroy()
     * @group admin-category-destroy
     * @group admin-category-destroy-access
     *
     */
    public function testUserCanDeleteCategoryFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $imageName = 'test-image.jpg';
        $image = UploadedFile::fake()->image($imageName);

        Storage::disk('images')->putFileAs('/', $image, $imageName);

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant, $imageName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'image' => $imageName
                ];
            })
            ->create();

        $route = route('admin.categories.destroy', $category->id);

        $response = $this->actingAs($user, 'user')
            ->deleteJson($route)
            ->assertStatus(200);

        Storage::disk('images')->assertMissing($imageName);

        $this->assertDeleted($category);

        $response->assertExactJson([
            'data' => null,
            'status' => 200,
            'message' => 'Deleted'
        ]);
    }

    /**
     * Test user cant delete category from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::destroy()
     * @group admin-category-destroy
     * @group admin-category-destroy-no-access
     *
     */
    public function testUserCantDeleteCategoryFromAnotherRestaurant()
    {
        $user = $this->user;

        $anotherRestaurant = Restaurant::factory()
            ->create();

        $category = Category::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $route = route('admin.categories.destroy', $category->id);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(403);
    }
    /**
     * Test user can get plates to add to category
     *
     * @covers \App\Http\Controllers\Api\Admin\CategoryController::platesList()
     * @group admin-category-plates-list
     *
     */
    public function testUserCanGetPlatesToAddToCategory()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        $anotherCategory = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $category->id
                ];
            })
            ->create();

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant, $anotherCategory) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $anotherCategory->id
                ];
            })
            ->create();

        $route = route('admin.categories.plates-list', $category->id);

        $plates = Plate::where('restaurant_id', $restaurant->id)
            ->where(function ($query) use ($category) {
                $query->whereNull('category_id')
                    ->orWhere('category_id', $category->id);
            })
            ->get();

        $resource = PlateResource::collection($plates);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $resource->response()->getData(true),
                'status' => 200,
                'message' => 'OK'
            ]);
    }

    /**
     * Test user can add plates to category
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::platesSync()
     * @group admin-category-plates-sync
     *
     */
    public function testUserCanAddPlatesToCategory()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $category = Category::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        Plate::factory()
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1
                ];
            })
            ->create();

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant, $category) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 1,
                    'category_id' => $category->id
                ];
            })
            ->create();

        $route = route('admin.categories.plates-sync', $category->id);

        $plateIds = Plate::where('restaurant_id', $restaurant->id)
            ->take(4)
            ->pluck('id')
            ->toArray();

        $this->actingAs($user, 'user')
            ->postJson($route, ['plate_ids' => $plateIds])
            ->assertStatus(200)
            ->assertExactJson([
                'data' => null,
                'status' => 200,
                'message' => 'OK'
            ]);

        $platesCount = Plate::where('restaurant_id', $restaurant->id)
            ->where('category_id', $category->id)
            ->count();

        $this->assertEquals(4, $platesCount);
    }
}
