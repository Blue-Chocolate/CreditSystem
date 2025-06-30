@extends('layouts.user')

@section('content')
<div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 600px; margin: 0 auto; background: repeating-linear-gradient(
  45deg,
  #f9f9f9,
  #f9f9f9 10px,
  #f1f1f1 10px,
  #f1f1f1 20px
);">
    @if($product->image_url)
        <img src="{{ $product->image_url }}" class="card-img-top img-fluid" alt="{{ $product->name }}">
    @elseif($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid" alt="{{ $product->name }}">
    @else
        <img src="https://via.placeholder.com/600x400?text=No+Image" class="card-img-top img-fluid" alt="No image">
    @endif

    <div class="card-body p-4">
        <h2 class="card-title">{{ $product->name }}</h2>
        <p><strong>Price:</strong> ${{ $product->price }}</p>
        <p><strong>Category:</strong> {{ $product->category }}</p>
        <p><strong>Stock:</strong> {{ $product->stock }}</p>
        <p><strong>In Offer Pool:</strong> {{ $product->is_offer_pool ? 'Yes' : 'No' }}</p>
        <p><strong>Reward Points:</strong> {{ $product->reward_points ? $product->reward_points : '-' }}</p>      
        <a href="{{ route('user.products.show', $product->id) }}" class="btn btn-secondary">View Details</a>
 <button
                        class="add-to-cart-btn btn btn-primary"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                    >
                        Add to Cart
                    </button>
    </div>
</div>
@endsection
