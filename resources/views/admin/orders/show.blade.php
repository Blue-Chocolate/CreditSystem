@extends('layouts.admin')
@section('content')
<h2>Order #{{ $order->id }}</h2>
<p><strong>User:</strong> {{ $order->user->name ?? '-' }}</p>
<p><strong>Status:</strong> {{ $order->status }}</p>
<p><strong>Total:</strong> ${{ $order->total }}</p>
<h4>Items</h4>
@if($order->items->count())
<table class="table">
    <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ $item->price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No items.</p>
@endif
<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back</a>
@endsection
