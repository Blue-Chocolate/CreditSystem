@extends('layouts.user')

@section('content')
<h2>Your Cart</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('user.orders.store') }}">
    @csrf
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
                    $subtotal = $item->product->price * $item->quantity;
                    $total += $subtotal;
                    if(!$item->product->is_offer_pool) $allInOfferPool = false;
                @endphp
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>${{ $item->product->price }}</td>
                    <td>
                        <button type="submit" formaction="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'decrement']) }}" class="btn btn-sm btn-secondary">-</button>
                        <span class="mx-2">{{ $item->quantity }}</span>
                        <button type="submit" formaction="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'increment']) }}" class="btn btn-sm btn-secondary">+</button>
                    </td>
                    <td>${{ $subtotal }}</td>
                    <td>
                        <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
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

    <div class="mb-3">
        <strong>Total: ${{ $total }}</strong>
        <input type="hidden" name="total" value="{{ $total }}">
    </div>

    <div class="mb-3">
        <label>Payment Method</label>
        <select name="payment_method" class="form-control">
            <option value="cash">Cash</option>
            <option value="visa">Visa</option>
            <option value="credit_points">Credit Points</option>
            @if($allInOfferPool)
                <option value="reward_points">Reward Points</option>
            @endif
        </select>
    </div>

    <button class="btn btn-success">Checkout</button>
</form>
@endsection
