<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Plate;
use App\Http\Resources\PlateResource;
use App\Http\Resources\PlateCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Class AdminPlateControllerTest
 *
 * Tests for @see App\Http\Controllers\Api\Admin\PlateController.php
 *
 * @covers \App\Http\Controllers\Api\Admin\PlateController
 * @group plate
 * @group admin-plate
 */
class AdminPlateControllerTest extends TestCase
{
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
     * Test user can get plates
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::index()
     * @group admin-plate-index
     * @group admin-plate-index-valid
     *
     */
    public function testUserCanGetPlates()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

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
            ->count(3)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'active' => 0
                ];
            })
            ->create();

        $activePlates = Plate::where('active', 1)
            ->where('restaurant_id', $restaurant->id)
            ->paginate(9);
        $inactivePlates = Plate::where('active', 0)
            ->where('restaurant_id', $restaurant->id)
            ->paginate(9);;
        $plates = Plate::where('restaurant_id', $restaurant->id)
            ->paginate(9);

        $route = route('admin.plates.index');

        $testCases = [
            // all plates
            [
                'plates' => $plates,
                'route' => $route
            ],
            // active plates
            [
                'plates' => $activePlates,
                'route' => "$route?active=1"
            ],
            // inactive plates
            [
                'plates' => $inactivePlates,
                'route' => "$route?active=0"
            ],
        ];

        foreach ($testCases as $case) {
            $resource = new PlateCollection($case['plates']);

            $data = $resource->response()->getData(true);
            $data['status'] = 200;
            $data['message'] = 'OK';

            $this->actingAs($user, 'user')
                ->getJson($case['route'])
                ->assertStatus(200)
                ->assertExactJson($data);
        }
    }

    /**
     * Test user can't get plates with invalid query
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::index()
     * @group admin-plate-index
     * @group admin-plate-index-invalid
     *
     */
    public function testUserCantGetPlatesWithInvalidQuery()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        Plate::factory()
            ->count(2)
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $route = route('admin.plates.index');

        $testCases = [
            // active can only be 0 or 1
            [
                'route' => "$route?active=2"
            ],
            // category_id is not a number
            [
                'route' => "$route?category_id={$this->faker->word}"
            ],
            // category_id is not an existing category id
            [
                'route' => "$route?category_id=0"
            ],
        ];

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->getJson($case['route']);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can get plate from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::show()
     * @group admin-plate-show
     * @group admin-plate-show-access
     *
     */
    public function testUserCanGetPlateFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant) {
                return [
                    'restaurant_id' => $restaurant->id
                ];
            })
            ->create();

        $route = route('admin.plates.show', $plate->id);

        $resource = new PlateResource($plate);

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
     * Test user can't get plate from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::show()
     * @group admin-plate-show
     * @group admin-plate-show-no-access
     *
     */
    public function testUserCantGetPlateFromAnotherRestaurant()
    {
        $user = $this->user;
        $anotherRestaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $route = route('admin.plates.show', $plate->id);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(403);
    }

    /**
     * Test user can create plate
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::store()
     * @group admin-plate-store
     * @group admin-plate-store-valid
     *
     */
    public function testUserCanCreatePlate()
    {
        $user = $this->user;
        $image = UploadedFile::fake()->image('test-image.jpg');

        $attributes = [
            'name' => $this->faker->word,
            'image' => $image,
            'quantity' => 1,
            'description' => $this->faker->paragraph(),
            'price' => 100.55
        ];

        $response = $this->actingAs($user, 'user')
            ->postJson(route('admin.plates.store'), $attributes)
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data', 'status']);

        $plate = Plate::first();

        Storage::disk('images')->assertExists($plate->image);

        $array = $plate->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);

        $this->assertDatabaseHas('plates', $array);
    }

    /**
     * Test user cant create plate with invalid data
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::store()
     * @group admin-plate-store
     * @group admin-plate-store-invalid
     *
     */
    public function testUserCantCreatePlateWithInvalidData()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $image = UploadedFile::fake()->image('test-image.jpg');
        $incorrectImage = UploadedFile::fake()->image('test-image.svg');
        $takenName = 'nameHasBeenTaken';

        Plate::factory()
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
            // quantity is not a number
            [
                'name' => $this->faker->word,
                'image' => $image,
                'quantity' => $this->faker->word
            ],
            // price is not numeric
            [
                'name' => $this->faker->word,
                'image' => $image,
                'price' => $this->faker->word
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
                ->postJson(route('admin.plates.store'), $case);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can update plate from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::update()
     * @group admin-plate-update
     * @group admin-plate-update-access
     *
     */
    public function testUserCanUpdatePlateFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $imageName = 'test-image.jpg';
        $image = UploadedFile::fake()->image($imageName);
        $newImage = UploadedFile::fake()->image('test-new-image.jpg');

        Storage::disk('images')->putFileAs('/', $image, $imageName);

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $imageName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'image' => $imageName
                ];
            })
            ->create();

        $route = route('admin.plates.update', $plate->id);

        // update with same name
        $attributes = [
            'name' => $plate->name
        ];

        $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(200);

        //  update with new name and image
        $attributes = [
            'name' => $this->faker->word,
            'image' => $newImage,
        ];

        $response = $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(200);

        $plate = $plate->fresh();

        Storage::disk('images')->assertExists($plate->image);
        Storage::disk('images')->assertMissing($imageName);

        $array = $plate->toArray();
        unset($array['created_at']);
        unset($array['updated_at']);

        $this->assertDatabaseHas('plates', $array);

        $resource = new PlateResource($plate);

        $response->assertExactJson([
            'data' => $resource->response()->getData(true),
            'status' => 200,
            'message' => 'Updated'
        ]);
    }

    /**
     * Test user cant update plate from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::update()
     * @group admin-plate-update
     * @group admin-plate-update-no-access
     *
     */
    public function testUserCantUpdatePlateFromAnotherRestaurant()
    {
        $user = $this->user;

        $anotherRestaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $attributes = [
            'name' => $this->faker->word,
        ];

        $route = route('admin.plates.update', $plate->id);

        $this->actingAs($user, 'user')
            ->putJson($route, $attributes)
            ->assertStatus(403);
    }

    /**
     * Test user cant update plate with invalid data
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::update()
     * @group admin-plate-update
     * @group admin-plate-update-invalid
     *
     */
    public function testUserCantUpdatePlateWithInvalidData()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $incorrectImage = UploadedFile::fake()->image('test-image.svg');
        $takenName = $this->faker->word;

        Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $takenName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'name' => $takenName
                ];
            })
            ->create();

        $plate = Plate::factory()
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
            // quantity is not a number
            [
                'name' => $this->faker->word,
                'quantity' => $this->faker->word
            ],
            // price is not numeric
            [
                'name' => $this->faker->word,
                'price' => $this->faker->word
            ],
            // active only can be 0 or 1
            [
                'active' => 2
            ],
        ];

        $route = route('admin.plates.update', $plate->id);

        foreach ($testCases as $case) {
            $response = $this->actingAs($user, 'user')
                ->putJson($route, $case);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * Test user can delete plate from his restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::destroy()
     * @group admin-plate-destroy
     * @group admin-plate-destroy-access
     *
     */
    public function testUserCanDeletePlateFromHisRestaurant()
    {
        $restaurant = $this->restaurant;
        $user = $this->user;
        $imageName = 'test-image.jpg';
        $image = UploadedFile::fake()->image($imageName);

        Storage::disk('images')->putFileAs('/', $image, $imageName);

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($restaurant, $imageName) {
                return [
                    'restaurant_id' => $restaurant->id,
                    'image' => $imageName
                ];
            })
            ->create();

        $route = route('admin.plates.destroy', $plate->id);

        $response = $this->actingAs($user, 'user')
            ->deleteJson($route)
            ->assertStatus(200);

        $response->assertExactJson([
            'data' => null,
            'status' => 200,
            'message' => 'Deleted'
        ]);
    }

    /**
     * Test user cant delete plate from another restaurant
     *
     * @covers \App\Http\Controllers\Api\Admin\PlateController::destroy()
     * @group admin-plate-destroy
     * @group admin-plate-destroy-no-access
     *
     */
    public function testUserCantDeletePlateFromAnotherRestaurant()
    {
        $user = $this->user;

        $anotherRestaurant = Restaurant::factory()
            ->create();

        $plate = Plate::factory()
            ->state(function (array $attributes) use ($anotherRestaurant) {
                return [
                    'restaurant_id' => $anotherRestaurant->id
                ];
            })
            ->create();

        $route = route('admin.plates.destroy', $plate->id);

        $this->actingAs($user, 'user')
            ->getJson($route)
            ->assertStatus(403);
    }
}
