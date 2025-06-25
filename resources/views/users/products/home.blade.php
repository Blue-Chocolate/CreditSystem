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
                        class="add-to-cart-btn btn btn-primary"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Load cart immediately on page load
    loadCart();

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            axios.post("{{ route('user.cart.add') }}", { id })
                .then(response => {
                    if (response.data.success) {
                        alert('Product added to cart!');
                        loadCart(); 
                    } else {
                        alert('Failed to add to cart.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to add to cart.');
                });
        });
    });

    function loadCart() {
        axios.get("{{ route('user.cart.get') }}")
            .then(response => {
                const cart = response.data.cart;
                let container = document.getElementById('cart-items');
                let total = 0;
                container.innerHTML = '';

                cart.forEach(item => {
                    total += item.price * item.quantity;

                    container.innerHTML += `
                        <div class="cart-item">
                            <span>${item.name} x ${item.quantity}</span>
                            <span>$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    `;
                });

                document.getElementById('cart-total').innerText = total.toFixed(2);
            })
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
@endsection
