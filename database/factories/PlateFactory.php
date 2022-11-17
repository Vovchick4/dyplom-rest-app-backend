<?php

namespace Database\Factories;

use App\Models\Plate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->word),
            'description' => $this->faker->paragraph,
            'image' => 'images/plate.jpeg',
            'active' => rand(0, 1),
            'quantity' => rand(75, 100),
            'weight' => rand(10, 75) * 10 . 'g',
            'price' => rand(100, 2000) / 100
        ];
    }
}
