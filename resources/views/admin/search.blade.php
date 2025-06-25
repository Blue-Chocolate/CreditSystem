@extends('layouts.admin')
@section('content')
<h2>Admin Search Results</h2>
@if($q)
    <h5>Results for: <mark>{{ $q }}</mark></h5>
    <div class="mb-4">
        <h6>Users</h6>
        @if($users->count())
            <ul class="list-group mb-3">
                @foreach($users as $user)
                    <li class="list-group-item">ID: {{ $user->id }} | {{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        @else
            <div class="text-muted">No users found.</div>
        @endif
        <h6>Products</h6>
        @if($products->count())
            <ul class="list-group mb-3">
                @foreach($products as $product)
                    <li class="list-group-item">ID: {{ $product->id }} | {{ $product->name }}</li>
                @endforeach
            </ul>
        @else
            <div class="text-muted">No products found.</div>
        @endif
        <h6>Packages</h6>
        @if($packages->count())
            <ul class="list-group mb-3">
                @foreach($packages as $package)
                    <li class="list-group-item">ID: {{ $package->id }} | {{ $package->name }}</li>
                @endforeach
            </ul>
        @else
            <div class="text-muted">No packages found.</div>
        @endif
    </div>
@endif
@endsection
