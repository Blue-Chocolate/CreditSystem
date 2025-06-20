<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/ProductFactory.php

public function definition(): array
{
   return [
    'name' => $this->faker->words(2, true), // Generates product name using two random words
    'category_id' => \App\Models\Category::factory(), // Factory for related category
    'description' => $this->faker->sentence, // Random sentence for description
    'image' => 'https://via.placeholder.com/150', // Placeholder image URL
    'points_required' => $this->faker->numberBetween(10, 200), // Random points between 10 and 200
    'is_offer_pool' => $this->faker->boolean, // Random boolean for offer pool
];
}

}
