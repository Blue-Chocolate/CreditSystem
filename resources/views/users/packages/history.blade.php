@extends('layouts.user')
@section('content')
<h2>Package Purchase History</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Package</th>
            <th>Price</th>
            <th>Credits</th>
            <th>Reward Points</th>
            <th>Date</th>
        </tr>
    </thead>
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
<div class="d-flex justify-content-center mt-3">
        {{ $purchases->links() }}
    </div>
@endsection
