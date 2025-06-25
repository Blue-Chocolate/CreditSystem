@extends('layouts.admin')

@section('content')
<style>
    .form-box { max-width: 600px; padding: 20px; background-color: #f7f7f7; border: 1px solid #ccc; margin-top: 20px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; }
    input[type="checkbox"] { width: auto; margin-top: 0; }
    .btn { margin-top: 20px; padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background-color: #0056b3; }
    img { margin-top: 10px; max-width: 100px; }
</style>

<h2>{{ isset($product) ? 'Edit' : 'Create' }} Product</h2>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-box">
    <form method="POST" action="{{ isset($product) ? route('admin.products.store', $product->id) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required>
<label>Category</label>
<select name="category" required>
    <option value="Electronic Devices" {{ old('category', $product->category ?? '') == 'Electronic Devices' ? 'selected' : '' }}>Electronic Devices</option>
    <option value="Kitchen Devices" {{ old('category', $product->category ?? '') == 'Kitchen Devices' ? 'selected' : '' }}>Kitchen Devices</option>
    <option value="Home Essentials" {{ old('category', $product->category ?? '') == 'Home Essentials' ? 'selected' : '' }}>Home Essentials</option>
</select>
        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>

        <label>Image (Upload a file or provide a URL)</label>
        <input type="file" name="image">
        <div style="text-align:center; margin: 10px 0;">OR</div>
        <input type="url" name="image_url" placeholder="https://example.com/image.jpg" value="{{ old('image_url', $product->image_url ?? '') }}">
        @if (isset($product) && $product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image">
        @elseif (isset($product) && $product->image_url)
            <img src="{{ $product->image_url }}" alt="Current Image">
        @endif

        <label>
            <input type="checkbox" name="is_offer_pool" value="1" {{ old('is_offer_pool', $product->is_offer_pool ?? false) ? 'checked' : '' }}>
            Is Offer Pool?
        </label>

        <label>Reward Points (only if offer pool)</label>
        <input type="number" name="reward_points" value="{{ old('reward_points', $product->reward_points ?? '') }}">

        <button type="submit" class="btn">{{ isset($product) ? 'Update' : 'Create' }} Product</button>
    </form>
</div>
@endsection
