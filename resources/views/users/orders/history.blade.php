@extends('layouts.user')
@section('content')
<h2>Order & Package Purchase History</h2>
<table class="table">
    <thead>
        <tr>
            <th>Type</th>
            <th>ID</th>
            <th>Status/Package</th>
            <th>Total</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($history as $entry)
            @if($entry['type'] === 'order')
                <tr>
                    <td>Order</td>
                    <td>{{ $entry['data']->id }}</td>
                    <td>{{ $entry['data']->status }}</td>
                    <td>${{ $entry['data']->total }}</td>
                    <td>{{ $entry['data']->created_at->format('Y-m-d H:i') }}</td>
                    <td><a href="{{ route('user.orders.show', $entry['data']->id) }}" class="btn btn-info btn-sm">Show</a></td>
                </tr>
            @elseif($entry['type'] === 'package')
                <tr>
                    <td>Package</td>
                    <td>{{ $entry['data']->id }}</td>
                    <td>{{ $entry['data']->package ? $entry['data']->package->name : 'N/A' }}</td>
                    <td>${{ $entry['data']->price }}</td>
                    <td>{{ ($entry['data']->purchased_at ?? $entry['data']->created_at)->format('Y-m-d H:i') }}</td>
                    <td>-</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endsection
