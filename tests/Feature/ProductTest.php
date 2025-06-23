<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_creation()
    {
        $product = Product::factory()->create([
            'is_offer_pool' => true,
            'reward_points' => 100
        ]);

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'is_offer_pool' => true,
            'reward_points' => 100
        ]);
    }

    public function test_search_by_name()
    {
        Product::factory()->create(['name' => 'Test Product']);
        Product::factory()->create(['name' => 'Another']);

        $response = $this->get('/admin/products?name=Test');

        $response->assertSee('Test Product')
                 ->assertDontSee('Another');
    }

    public function test_search_by_offer_pool()
    {
        Product::factory()->create(['name' => 'Pool Product', 'is_offer_pool' => true]);
        Product::factory()->create(['name' => 'Regular Product', 'is_offer_pool' => false]);

        $response = $this->get('/admin/products?is_offer_pool=1');

        $response->assertSee('Pool Product')
                 ->assertDontSee('Regular Product');
    }
}
