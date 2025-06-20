<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Redemption>
 */
class RedemptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'user_id' => \App\Models\User::factory(),
        'product_id' => \App\Models\Product::factory(),
        'points_used' => $this->faker->numberBetween(10, 200),
    ];
}
}
