@extends('layouts.admin')
@section('content')
<h2>Edit Order #{{ $order->id }}</h2>
<form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>User</label>
        <select name="user_id" class="form-control" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Total</label>
        <input type="number" step="0.01" name="total" class="form-control" value="{{ $order->total }}" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <input type="text" name="status" class="form-control" value="{{ $order->status }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
</form>
@endsection
