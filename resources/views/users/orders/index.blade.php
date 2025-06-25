@extends('layouts.user')
@section('content')
<h2>Your Orders</h2>
<a href="{{ route('user.orders.create') }}" class="btn btn-primary mb-3">Create Order</a>
<table class="table">
    <thead><tr><th>ID</th><th>Status</th><th>Total</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->status }}</td>
            <td>${{ $order->total }}</td>
            <td>
                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-info btn-sm">Show</a>
                <a href="{{ route('user.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('user.orders.destroy', $order->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>
@endsection
