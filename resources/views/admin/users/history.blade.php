@extends('layouts.admin')
@section('content')
<h2>User Purchase & Order History</h2>
<div class="mb-3">
    <form method="GET" action="">
        <label>User:</label>
        <select name="user_id" onchange="this.form.submit()" class="form-control" style="max-width:300px;display:inline-block;">
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </form>
</div>
@if($selectedUser)
    <h4>Order History for {{ $selectedUser->name }}</h4>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Status</th><th>Total</th><th>Date</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->status }}</td>
                <td>${{ $order->total }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h4>Package Purchases for {{ $selectedUser->name }}</h4>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Package</th><th>Price</th><th>Credits</th><th>Reward Points</th><th>Date</th></tr></thead>
        <tbody>
        @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->package ? $purchase->package->name : '-' }}</td>
                <td>${{ $purchase->price }}</td>
                <td>{{ $purchase->credits }}</td>
                <td>{{ $purchase->reward_points }}</td>
                <td>{{ $purchase->purchased_at ? \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d H:i') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
@endsection
