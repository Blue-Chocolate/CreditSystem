<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditPackage>
 */
class CreditPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $price = $this->faker->randomElement([50, 100, 200]);
    return [
        'name' => 'Package ' . $price,
        'price_egp' => $price,
        'credit_amount' => $price,
        'reward_points' => $price / 2,
    ];
}
}
