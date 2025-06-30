<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_to_cart_and_persist_in_session()
    {
        $product = Product::factory()->create();
        $response = $this->postJson(route('user.cart.add'), ['id' => $product->id]);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty(session('cart'));
    }

    public function test_logged_in_user_cart_merges_on_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $product = Product::factory()->create();
        session(['cart' => [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 2,
                'image' => $product->image,
            ]
        ]]);
        $this->post(route('login'), ['email' => $user->email, 'password' => 'password']);
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);
        $this->assertEquals(2, $cart->items()->where('product_id', $product->id)->first()->quantity);
    }

    public function test_cart_sidebar_and_cart_page_show_correct_items_for_guest_and_user()
    {
        $product = Product::factory()->create();
        session(['cart' => [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
            ]
        ]]);
        $response = $this->get('/user/home');
        $response->assertSee($product->name);
        $response = $this->get('/user/cart');
        $response->assertSee($product->name);
    }
}
