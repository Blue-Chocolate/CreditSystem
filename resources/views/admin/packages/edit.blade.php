@extends('layouts.admin')

@section('content')
    <h2>Edit Package</h2>
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
    <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" class="form-box">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ $package->name }}" required>

        <label>Price</label>
        <input type="number" name="price" step="0.01" value="{{ $package->price }}" required>

        <label>Credit Points</label>
        <input type="number" name="credits" value="{{ $package->credits }}" required>

        <label>Reward Points</label>
        <input type="number" name="reward_points" value="{{ $package->reward_points }}" required>

        <button type="submit" class="btn" style="background-color: green;">Update</button>
    </form>
@endsection
