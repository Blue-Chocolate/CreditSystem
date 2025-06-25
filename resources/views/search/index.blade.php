@extends('layouts.user')
@section('content')
<div class="container">
    <form method="GET" action="{{ route('search.index') }}" class="mb-4" id="search-form">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" id="search-input" value="{{ request('q') }}" class="form-control" placeholder="Search products..." autocomplete="off" aria-label="Search products">
                <div id="autocomplete-list" class="list-group position-absolute w-100" style="z-index: 10;"></div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select" aria-label="Filter by category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Min Price" aria-label="Min price">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max Price" aria-label="Max price">
            </div>
            <div class="col-md-2">
                <select name="is_offer_pool" class="form-select" aria-label="Offer pool filter">
                    <option value="">All</option>
                    <option value="1" {{ request('is_offer_pool') === '1' ? 'selected' : '' }}>Offer Pool Only</option>
                    <option value="0" {{ request('is_offer_pool') === '0' ? 'selected' : '' }}>Not in Offer Pool</option>
                </select>
            </div>
            <div class="col-md-2 mt-2 mt-md-0">
                <select name="sort" class="form-select" aria-label="Sort">
                    <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>
            <div class="col-md-2 mt-2 mt-md-0">
                <button class="btn btn-primary w-100" type="submit">Search</button>
            </div>
        </div>
    </form>
    @if(isset($products) && $products->count())
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{!! str_ireplace(request('q'), '<mark>'.e(request('q')).'</mark>', $product->name) !!}</h5>
                            <p class="card-text text-muted mb-1">Category: {{ $product->category }}</p>
                            <p class="card-text mb-1">Price: <strong>${{ $product->price }}</strong></p>
                            <p class="card-text mb-1">Stock: <span class="badge bg-info">{{ $product->stock }}</span></p>
                            @if($product->is_offer_pool)
                                <span class="badge bg-success mb-1">Offer Pool</span>
                            @endif
                            <span class="badge bg-secondary mb-1">Reward: {{ $product->reward_points }}</span>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <form method="POST" action="{{ route('user.cart.add') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $products->withQueryString()->links() }}</div>
    @elseif(request('q'))
        <div class="alert alert-warning">No results found for <strong>{{ request('q') }}</strong>.</div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search-input');
    const list = document.getElementById('autocomplete-list');
    let timeout = null;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const val = this.value;
        if (!val) { list.innerHTML = ''; return; }
        timeout = setTimeout(() => {
            fetch(`{{ route('search.autocomplete') }}?q=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    data.forEach(item => {
                        const el = document.createElement('button');
                        el.type = 'button';
                        el.className = 'list-group-item list-group-item-action';
                        el.innerHTML = item.replace(new RegExp(val, 'gi'), match => `<mark>${match}</mark>`);
                        el.onclick = () => { input.value = item; list.innerHTML = ''; document.getElementById('search-form').submit(); };
                        list.appendChild(el);
                    });
                });
        }, 200);
    });
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) list.innerHTML = '';
    });
});
</script>
@endsection
