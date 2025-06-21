@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8 mt-8 border-4 border-[#dbc524]">
    <div class="flex flex-col items-center">
        <img src="{{ asset('storage/' . $product->image) }}" class="w-48 h-48 rounded mb-4 border-4 border-[#987cac]" onerror="this.onerror=null;this.src='https://via.placeholder.com/192';" />
        <h2 class="text-3xl font-bold mb-2 text-[#2059c1]">{{ $product->name }}</h2>
        <p class="mb-2 text-[#987cac]">Category: <span class="font-semibold">{{ $product->category->name ?? 'N/A' }}</span></p>
        <p class="mb-2 text-[#d92600]">Points Required: <span class="font-semibold">{{ $product->points_required }}</span></p>
        <p class="mb-2 text-[#15bf5d]">Offer Pool: <span class="font-semibold">{{ $product->is_offer_pool ? 'Yes' : 'No' }}</span></p>
        <p class="mb-4 text-gray-700">{{ $product->description }}</p>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 rounded bg-[#dbc524] text-white font-bold hover:bg-[#987cac] transition">Back to Products</a>
    </div>
</div>
@endsection
