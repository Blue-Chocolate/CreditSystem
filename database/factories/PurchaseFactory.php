<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
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
        'credit_package_id' => \App\Models\CreditPackage::factory(),
        'credits_received' => $this->faker->randomElement([50, 100, 200]),
        'reward_points_given' => $this->faker->randomElement([25, 50, 100]),
    ];
}
}
