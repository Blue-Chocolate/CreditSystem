@extends('layouts.user')

@section('content')
<h2>{{ $product->name }}</h2>

@if(!empty($product->image))
    <img src="{{ asset('storage/' . ltrim($product->image, '/')) }}" class="img-fluid mb-3" alt="{{ $product->name }} image">
@else
    <img src="{{ asset('images/default-product.png') }}" class="img-fluid mb-3" alt="No image available">
@endif
<p><strong>Price:</strong> ${{ $product->price }}</p>
<p><strong>Category:</strong> {{ $product->category }}</p>
<p><strong>Stock:</strong> {{ $product->stock }}</p>
<p><strong>In Offer Pool:</strong> {{ $product->is_offer_pool ? 'Yes' : 'No' }}</p>
<p><strong>Reward Points:</strong> {{ $product->reward_points ? $product->reward_points : '-' }}</p>

@php
    $productData = json_encode([

        'id' => $product->id,

        'name' => $product->name,
        'price' => $product->price
    ]);
@endphp

@endsection
