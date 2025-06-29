<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Merge guest cart (session) into user cart
        $user = Auth::user();
        $sessionCart = session('cart', []);
        if (!empty($sessionCart)) {
            $cart = \App\Models\Cart::firstOrCreate(['user_id' => $user->id]);
            foreach ($sessionCart as $item) {
                $cartItem = \App\Models\CartItem::firstOrNew([
                    'cart_id' => $cart->id,
                    'product_id' => $item['id'],
                ]);
                $cartItem->quantity = ($cartItem->quantity ?? 0) + $item['quantity'];
                $cartItem->save();
            }
            session()->forget('cart');
        }
        // Redirect based on role
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if (property_exists($user, 'role') && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->intended('/user/home');
    }

    /**
     * Handle actions after user is authenticated.
     */
    public function authenticated(Request $request, $user)
    {
        if ($user->first_login) {
            $user->update(['first_login' => false]);
            session()->flash('show_driver_tour', true);
        }
        // return redirect()->intended('/user/home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
