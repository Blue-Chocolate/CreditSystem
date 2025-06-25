@extends('layouts.admin')
@section('content')
<h2>All Orders</h2>
@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Status</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? '-' }}</td>
            <td>{{ $order->status }}</td>
            <td>${{ $order->total }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Show</a>
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline">
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
<a href="{{ route('admin.orders.create') }}" class="btn btn-primary mt-3">Create Order</a>
@endsection
