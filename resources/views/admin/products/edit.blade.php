@extends('layouts.admin')

@section('content')
<style>
    .form-box { max-width: 600px; padding: 20px; background-color: #f7f7f7; border: 1px solid #ccc; margin-top: 20px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; }
    input[type="checkbox"] { width: auto; margin-top: 0; }
    .btn { margin-top: 20px; padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background-color: #1e7e34; }
    img { margin-top: 10px; max-width: 100px; }
</style>

<h2>Edit Product</h2>
@if(session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif
<div class="form-box">
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>

        <label>Category</label>
        <select name="category" required>
            <option value="Electronic Devices" {{ old('category', $product->category) == 'Electronic Devices' ? 'selected' : '' }}>Electronic Devices</option>
            <option value="Kitchen Devices" {{ old('category', $product->category) == 'Kitchen Devices' ? 'selected' : '' }}>Kitchen Devices</option>
            <option value="Home Essentials" {{ old('category', $product->category) == 'Home Essentials' ? 'selected' : '' }}>Home Essentials</option>
        </select>

        <label>Image</label>
        <input type="file" name="image">
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image">
        @endif

        <label>Image URL</label>
        <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://...">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="Current Image URL">
        @endif

        <label>
            <input type="hidden" name="is_offer_pool" value="0">
            <input type="checkbox" name="is_offer_pool" value="1" {{ old('is_offer_pool', $product->is_offer_pool) ? 'checked' : '' }}>
            Is Offer Pool?
        </label>

        <label>Reward Points (if offer pool)</label>
        <input type="number" name="reward_points" value="{{ old('reward_points', $product->reward_points) }}">

        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>

        <button type="submit" class="btn">Update Product</button>
    </form>
</div>
@endsection
