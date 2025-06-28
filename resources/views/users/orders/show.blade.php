@extends('layouts.user')
@section('content')
<h2>Order #{{ $order->id }}</h2>
<p>Status: {{ $order->status }}</p>
<p>Total: ${{ $order->total }}</p>
<p>Date: {{ $order->created_at->format('Y-m-d H:i') }}</p>

<h4>Order Items</h4>
<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name ?? 'N/A' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ $item->price }}</td>
            <td>${{ $item->price * $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('user.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
@endsection
