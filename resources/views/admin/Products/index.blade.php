@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Product</a>
    </div>

    @if(session('success')) <p class="text-green-600">{{ session('success') }}</p> @endif

    <table class="w-full bg-white shadow rounded-xl">
        <thead>
            <tr>
                <th class="p-3 border-b">Image</th>
                <th class="p-3 border-b">Name</th>
                <th class="p-3 border-b">Category</th>
                <th class="p-3 border-b">Points</th>
                <th class="p-3 border-b">Offer Pool</th>
                <th class="p-3 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="p-3 border-b flex items-center gap-2">
                    <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="offer-pool-checkbox" data-id="{{ $product->id }}" {{ $product->is_offer_pool ? 'checked' : '' }} />
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded" onerror="this.onerror=null;this.src='https://via.placeholder.com/48';" />
                    </label>
                </td>
                <td class="p-3 border-b">{{ $product->name }}</td>
                <td class="p-3 border-b">{{ $product->category->name ?? 'N/A' }}</td>
                <td class="p-3 border-b">{{ $product->points_required }}</td>
                <td class="p-3 border-b">
                    <input type="checkbox" class="offer-pool-checkbox" data-id="{{ $product->id }}" {{ $product->is_offer_pool ? 'checked' : '' }} />
                </td>
                <td class="p-3 border-b">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-600">Edit</a> |
                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this product?')" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
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
