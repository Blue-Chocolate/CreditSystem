@extends('layouts.user')
@section('content')
<h2>Order #{{ $order->id }}</h2>
<p>Status: {{ $order->status }}</p>
<p>Total: ${{ $order->total }}</p>
<a href="{{ route('user.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
@endsection
