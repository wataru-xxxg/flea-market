<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'deliveryAddress' => $this->faker->address(),
            'payment' => 'card'
        ];
    }
}
