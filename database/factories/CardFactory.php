<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'number' => $this->faker->randomNumber(8, true) . $this->faker->randomNumber(8, true),
            'cvv' => bcrypt($this->faker->randomNumber(3, true)),
            'expiration_date' => now()->addMonths(rand(12, 48))->format('Y-m-01'),
        ];
    }
}
