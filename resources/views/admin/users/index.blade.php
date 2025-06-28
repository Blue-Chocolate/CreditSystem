@extends('layouts.admin')

@section('content')
<style>
    .user-card {
        border: 1px solid #e3e3e3;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        margin-bottom: 32px;
        padding: 24px;
        background: #fff;
    }
    .user-header {
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }
    .order-list {
        margin-top: 10px;
        padding-left: 20px;
    }
    .order-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 8px;
        border-left: 4px solid #74b9ff;
    }
    .order-item strong { color: #333; }
</style>
<h2>Users</h2>
<div class="mb-3">Total Users: {{ $users->total() }}</div>
@foreach($users as $user)
    <div class="user-card">
        <div class="user-header">{{ $user->name }} <span class="text-muted">({{ $user->email }})</span></div>
        <div>Balance: ${{ $user->credit_balance }} | Credit: {{ $user->credit_points }} | Reward: {{ $user->reward_points }}</div>
        <div class="mt-2"><strong>Orders:</strong></div>
        <div class="order-list">
            @forelse($user->orders as $order)
                <div class="order-item">
                    <div><strong>Order #{{ $order->id }}</strong> - ${{ $order->total }} - {{ $order->created_at->format('Y-m-d') }}</div>
                    <div>Items: {{ $order->items->count() }}</div>
                </div>
            @empty
                <div class="text-muted">No orders yet.</div>
            @endforelse
        </div>
    </div>
@endforeach
<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endsection
