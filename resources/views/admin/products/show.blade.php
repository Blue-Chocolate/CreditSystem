@extends('layouts.admin')

@section('content')
<style>
    .product-box { padding: 20px; background: #f7f7f7; border: 1px solid #ccc; max-width: 500px; margin-top: 20px; }
    img { max-width: 200px; margin-top: 10px; }
</style>

<h2>Product Details</h2>

<div class="product-box">
    <p><strong>ID:</strong> {{ $product->id }}</p>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Price:</strong> {{ $product->price }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>
    <p><strong>In Offer Pool:</strong> {{ $product->is_offer_pool ? 'Yes' : 'No' }}</p>
    <p><strong>Reward Points:</strong> {{ $product->reward_points ?? '-' }}</p>
    @if ($product->image)
        <p><strong>Image:</strong></p>
        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image">
    @endif
</div>
@endsection
