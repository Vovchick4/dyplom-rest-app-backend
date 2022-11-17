<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $person_quantity = rand(1, 6);

        return [
            'status' => \Arr::random(['new', 'in_process', 'completed', 'canceled']),
            'payment_status' => \Arr::random(['pending', 'paid', 'not_paid']),
            'table' => (string) rand(1, 50),
            'name' => $this->faker->name(),
            'person_quantity' => $person_quantity,
            'people_for_quantity' => rand(1, $person_quantity),
            'is_takeaway' => rand(0, 1),
            'is_online_payment' => rand(0, 1),
            'price' => 0
        ];
    }
}
