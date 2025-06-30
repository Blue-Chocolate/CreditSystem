<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesSeeder::class);
    }

    public function test_user_can_checkout_with_valid_cart()
    {
        $user = User::factory()->create(['credit_balance' => 100]);
        $user->assignRole('user');
        $product = Product::factory()->create(['price' => 10, 'stock' => 5]);
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user);
        $response = $this->post(route('user.orders.store'), [
            'total' => 20,
            'payment_method' => 'cash',
        ]);
        $response->assertRedirect(route('user.orders.index'));
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 20]);
        $this->assertEquals(3, $product->fresh()->stock);
    }

    public function test_checkout_fails_with_insufficient_balance()
    {
        $user = User::factory()->create(['credit_balance' => 5]);
        $user->assignRole('user');
        $product = Product::factory()->create(['price' => 10, 'stock' => 5]);
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 1]);
        $this->actingAs($user);
        $response = $this->post(route('user.orders.store'), [
            'total' => 10,
            'payment_method' => 'cash',
        ]);
        $response->assertSessionHas('error');
    }

    public function test_checkout_fails_if_product_removed()
    {
        $user = User::factory()->create(['credit_balance' => 100]);
        $user->assignRole('user');
        $product = Product::factory()->create(['price' => 10, 'stock' => 5]);
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 1]);
        $product->delete();
        $this->actingAs($user);
        $response = $this->post(route('user.orders.store'), [
            'total' => 10,
            'payment_method' => 'cash',
        ]);
        $response->assertSessionHas('error');
    }
}
