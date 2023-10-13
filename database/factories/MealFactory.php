<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "price"=> $this->faker->numberBetween(50, 220),
            "discount"=> $this->faker->numberBetween(0, 10),
            "description"=> $this->faker->paragraph(),
            "available_quantity" => $this->faker->numberBetween(50, 120),
        ];
    }
}
