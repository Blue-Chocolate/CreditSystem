@extends('layouts.user')
@section('content')
<h2>Create Order</h2>
<form method="POST" action="{{ route('user.orders.store') }}">
    @csrf
    <div class="mb-3">
        <label>Total</label>
        <input type="number" step="0.01" name="total" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <input type="text" name="status" class="form-control" value="pending" required>
    </div>
    <button class="btn btn-primary">Create</button>
</form>
@endsection
