@extends('layouts.admin')

@section('content')
<div>
    <div>
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}">Add Product</a>
    </div>

    @if(session('success')) <p>{{ session('success') }}</p> @endif

    <div>
        @foreach($products as $product)
        <div onclick="window.location='{{ route('admin.products.show', $product->id) }}'">
            <div>
                <img src="{{ asset('storage/' . $product->image) }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/96';" />
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->category->name ?? 'N/A' }}</p>
                <p>Points: {{ $product->points_required }}</p>
                <p>Offer Pool: {{ $product->is_offer_pool ? 'Yes' : 'No' }}</p>
                <div>
                    <input type="checkbox" class="offer-pool-checkbox" data-id="{{ $product->id }}" {{ $product->is_offer_pool ? 'checked' : '' }} onclick="event.stopPropagation();" />
                    <span>Toggle Offer</span>
                </div>
                <div>
                    <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this product?');event.stopPropagation();">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div>
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.offer-pool-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        fetch(`/admin/products/${this.dataset.id}/toggle-offer-pool`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_offer_pool: this.checked ? 1 : 0 })
        });
    });
});
</script>
@endsection
