@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-xl">
    <h1 class="text-xl font-bold mb-4">{{ isset($product) ? 'Edit' : 'Add' }} Product</h1>

    <form method="POST" action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <input type="text" name="name" placeholder="Name" value="{{ old('name', $product->name ?? '') }}" class="w-full mb-3 border p-2 rounded">
        <textarea name="description" placeholder="Description" class="w-full mb-3 border p-2 rounded">{{ old('description', $product->description ?? '') }}</textarea>

        <select name="category_id" class="w-full mb-3 border p-2 rounded">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <input type="number" name="points_required" placeholder="Points" value="{{ old('points_required', $product->points_required ?? 0) }}" class="w-full mb-3 border p-2 rounded">

        <label class="block mb-1">Offer Pool</label>
        <select name="is_offer_pool" class="w-full mb-3 border p-2 rounded">
            <option value="0" @selected(old('is_offer_pool', $product->is_offer_pool ?? 0) == 0)>No</option>
            <option value="1" @selected(old('is_offer_pool', $product->is_offer_pool ?? 0) == 1)>Yes</option>
        </select>

        <input type="file" name="image" class="mb-3">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ isset($product) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
