@extends('layouts.user')

@section('content')
<h2>Products</h2>
<div class="row">
    @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top">
                @endif
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>${{ $product->price }}</p>
                    <p>Category: {{ $product->category }}</p>
                    <p>Stock: {{ $product->stock }}</p>
                    <p>In Offer Pool: {{ $product->is_offer_pool ? 'Yes' : 'No' }}</p>
                    <p>Reward Points: {{ $product->reward_points ?? '-' }}</p>
                    <a href="{{ route('user.products.show', $product->id) }}" class="btn btn-secondary">View Details</a>

                    <button 
                        class="btn btn-primary add-to-cart-btn"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const product = {
                id: this.dataset.id,
                name: this.dataset.name,
                price: parseFloat(this.dataset.price)
            };
            addToCart(product);
        });
    });

    function addToCart(product) {
        console.log("Adding to cart:", product);
        // TODO: Send AJAX request here
        // e.g., axios.post('/cart/add', product).then(...)
    }
});
</script>
@endsection
