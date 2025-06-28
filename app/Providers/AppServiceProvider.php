<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
            Paginator::useBootstrapFive();

        // Registering the IsAdmin middleware properly
        $this->app['router']->aliasMiddleware('is_admin', AdminMiddleware::class);

        // Share cart sidebar data with all views for authenticated users
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
                $cartSidebarItems = collect();
                $cartSidebarTotal = 0;
                if ($cart) {
                    $cartSidebarItems = $cart->items;
                    foreach ($cartSidebarItems as $item) {
                        if ($item->product) {
                            $cartSidebarTotal += $item->product->price * $item->quantity;
                        }
                    }
                }
                $view->with('cartSidebarItems', $cartSidebarItems)->with('cartSidebarTotal', $cartSidebarTotal);
            }
        });
    }
}
