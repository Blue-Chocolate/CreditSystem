@extends('layouts.admin')

@section('content')
<h2>Admin RAG (Resource Access Gateway)</h2>

<form method="GET" action="">
    <div class="mb-3">
        <label>Search Users</label>
        <input type="text" name="user_query" value="{{ request('user_query') }}" class="form-control" placeholder="User name or email">
    </div>
    <div class="mb-3">
        <label>Search Products</label>
        <input type="text" name="product_query" value="{{ request('product_query') }}" class="form-control" placeholder="Product name">
    </div>
    <button class="btn btn-primary">Search</button>
</form>

@if(isset($users))
    <h4>Users</h4>
    <ul>
        @forelse($users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @empty
            <li>No users found.</li>
        @endforelse
    </ul>
@endif

@if(isset($products))
    <h4>Products</h4>
    <ul>
        @forelse($products as $product)
            <li>{{ $product->name }} - {{ $product->price }} EGP</li>
        @empty
            <li>No products found.</li>
        @endforelse
    </ul>
@endif
@endsection
