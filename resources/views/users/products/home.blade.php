@extends('layouts.user')

@section('content')
<h2>Products</h2>
<div class="row">
    @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                @elseif($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No image">
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

<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
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
                        showToast('Product added to cart!');
                        loadCart(); 
                    } else {
                        showToast('Failed to add to cart.', true);
                    }
                })
                .catch(error => {
                    console.error(error);
                    showToast('Failed to add to cart.', true);
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

    // Modern toast notification
    function showToast(message, isError = false) {
        let toast = document.createElement('div');
        toast.className = 'custom-toast' + (isError ? ' error' : '');
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(() => { toast.classList.add('show'); }, 10);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 2000);
    }
});
</script>
<style>
.custom-toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #2ecc71;
    color: #fff;
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 1rem;
    opacity: 0;
    pointer-events: none;
    z-index: 9999;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: opacity 0.3s, transform 0.3s;
    transform: translateY(30px);
}
.custom-toast.show {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}
.custom-toast.error {
    background: #e74c3c;
}
</style>
@endsection
