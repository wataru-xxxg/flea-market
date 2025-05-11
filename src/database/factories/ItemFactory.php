<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'brand' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'imagePath' => $this->faker->imageUrl(),
            'condition' => $this->faker->numberBetween(1, 4),
            'price' => $this->faker->numberBetween(100, 1000000),
            'purchased' => 0,
        ];
    }
}
