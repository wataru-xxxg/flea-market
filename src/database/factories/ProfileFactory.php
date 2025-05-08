<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
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
            'imagePath' => $this->faker->imageUrl(),
            'postCode' => $this->faker->postcode(),
            'address' => $this->faker->streetAddress(),
            'building' => 'テストマンション'
        ];
    }
}
