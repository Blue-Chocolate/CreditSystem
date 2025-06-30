@extends('layouts.user')

@section('content')
<h2>Your Cart</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; $allInOfferPool = true; @endphp
        @foreach($cartItems as $item)
            @php
                if (!$item->product) {
                    continue; // skip missing products
                }
                $subtotal = $item->product->price * $item->quantity;
                $total += $subtotal;
                if(!$item->product->is_offer_pool) $allInOfferPool = false;
            @endphp
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>${{ $item->product->price }}</td>
                <td>
                    <form action="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'decrement']) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-sm btn-secondary">-</button>
                    </form>
                    <span class="mx-2">{{ $item->quantity }}</span>
                    <form action="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'increment']) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-sm btn-secondary">+</button>
                    </form>
                </td>
                <td>${{ $subtotal }}</td>
                <td>
                    <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Remove item">&times;</button>
                    </form>

                    @if($item->product->is_offer_pool)
                        <form action="{{ route('user.cart.redeem', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-danger mt-1">Redeem</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($cartItems->count() > 0)
    <form method="POST" action="{{ route('user.orders.store') }}">
        @csrf
        <div class="mb-3">
            <strong>Total: ${{ $total }}</strong>
            <input type="hidden" name="total" value="{{ $total }}">
        </div>

        <div class="mb-3">
            <label>Payment Method</label>
            <div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="pay_balance" value="balance" required>
                    <label class="form-check-label" for="pay_balance">
                        Credit Balance (${{ auth()->user()->credit_balance }})
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="pay_points" value="points" required>
                    <label class="form-check-label" for="pay_points">
                        Credit Points ({{ auth()->user()->credit_points }})
                    </label>
                </div>
                @if(!$cartItems->isEmpty() && $cartItems->contains(function($item){ return $item->product && $item->product->is_offer_pool; }))
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="pay_reward" value="reward" required>
                    <label class="form-check-label" for="pay_reward">
                        Reward Points ({{ auth()->user()->reward_points }})
                    </label>
                </div>
                @endif
            </div>
        </div>

        <button class="btn btn-success">Checkout</button>
    </form>
@else
    <div class="alert alert-info">Your cart is empty.</div>
@endif
@endsection
