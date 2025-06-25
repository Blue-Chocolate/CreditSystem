@extends('layouts.user')
@section('content')
<h2>Order History</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Status</th>
            <th>Total</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->status }}</td>
            <td>${{ $order->total }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            <td><a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-info btn-sm">Show</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>
@endsection
