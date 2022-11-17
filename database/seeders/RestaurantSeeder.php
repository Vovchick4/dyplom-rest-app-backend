<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Category;
use App\Models\Plate;
use App\Models\Order;
use App\Models\Client;

class RestaurantSeeder extends Seeder
{
    private $restaurants_amount = 10;
    private $categories_per_restaurant = 10;
    private $plates_per_category = 10;
    private $orders_per_restaurant = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::factory()
            ->times($this->restaurants_amount)
            ->has(User::factory()->count(1)->state(function (array $attributes, Restaurant $restaurant) {
                return [
                    'role' => 'owner',
                    'restaurant_id' => $restaurant->id
                ];
            }))
            ->has(User::factory()->count(1)->state(function (array $attributes, Restaurant $restaurant) {
                return [
                    'role' => 'admin',
                    'restaurant_id' => $restaurant->id
                ];
            }))
            ->has(Category::factory()
                ->count($this->categories_per_restaurant)
                ->has(Plate::factory()->count($this->plates_per_category)->state(function (array $attributes, Category $category) {
                    return [
                        'restaurant_id' => $category->restaurant_id
                    ];
                })))
            ->hasOrders($this->orders_per_restaurant)
            ->create();

        Order::each(function (Order $order) {
            $plate = $order->restaurant->plates()->first();
            $order->plates()->attach($plate, ['price' => $plate->price, 'amount' => rand(1, 4)]);

            if (Client::count())
                $order->client()->associate(Client::inRandomOrder()->first())->save();
        });
    }
}
