<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_product_with_missing_fields()
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);
        $response = $this->post(route('admin.products.store'), [
            'name' => '',
            'price' => '',
            'category' => '',
        ]);
        $response->assertSessionHasErrors(['name', 'price', 'category']);
    }

    public function test_admin_cannot_create_duplicate_product_name()
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'UniqueName']);
        $this->actingAs($admin);
        $response = $this->post(route('admin.products.store'), [
            'name' => 'UniqueName',
            'price' => 10,
            'category' => 'Electronic Devices',
            'stock' => 5
        ]);
        $response->assertSessionHasErrors(['name']);
    }
}
