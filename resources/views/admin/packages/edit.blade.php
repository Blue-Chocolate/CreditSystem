@extends('layouts.admin')

@section('content')
    <h2>Edit Package</h2>

    <form action="{{ route('packages.update', $package->id) }}" method="POST" class="form-box">
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

        <button type="submit" class="btn">Update</button>
    </form>
@endsection
