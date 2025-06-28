@extends('layouts.admin')

@section('content')
<style>
    .btn {
        padding: 8px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn:hover { background-color: #0056b3; }
    .danger { background-color: red; }
    .gray { background-color: gray; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }
    form.inline { display: inline; }
    .search-form {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .search-form input, .search-form select {
        padding: 5px;
        margin-right: 10px;
    }
</style>

<h2>Products</h2>

<form method="GET" action="{{ route('admin.products.index') }}" class="search-form">
    
    <input type="text" name="name" placeholder="Search by name" value="{{ request('name') }}">

    <label style="margin-right: 10px;">
        <input type="checkbox" name="is_offer_pool" value="1" {{ request('is_offer_pool') ? 'checked' : '' }}>
        Offer Pool Only
    </label>

    <select name="category">
        <option value="">All Categories</option>
        <option value="Electronic Devices" {{ request('category') === 'Electronic Devices' ? 'selected' : '' }}>
            Electronic Devices
        </option>
        <option value="Kitchen Devices" {{ request('category') === 'Kitchen Devices' ? 'selected' : '' }}>
            Kitchen Devices
        </option>
        <option value="Home Essentials" {{ request('category') === 'Home Essentials' ? 'selected' : '' }}>
            Home Essentials
        </option>
    </select>

    <button type="submit" class="btn">Search</button>
</form>

<a href="{{ route('admin.products.create') }}" class="btn">+ Add Product</a>

<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Is in Offer Pool</th>
            <th>Reward Points</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->is_offer_pool ? 'Yes' : 'No' }}</td>
                <td>{{ $product->reward_points ?? '-' }}</td>
                <td>
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" width="50" alt="{{ $product->name }}">
                    @elseif ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="50" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/50x50?text=No+Image" width="50" alt="No image">
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn gray">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align: center;">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $products->links() }}
</div>
@endsection
