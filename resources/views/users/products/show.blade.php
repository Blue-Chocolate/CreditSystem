@extends('layouts.user')

@section('content')
<h2>{{ $product->name }}</h2>

@if($product->image)
    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-3">
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
