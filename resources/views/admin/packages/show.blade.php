@extends('layouts.admin')

@section('content')
    <h2>Package Details</h2>

    <div class="form-box">
        <p><strong>ID:</strong> {{ $package->id }}</p>
        <p><strong>Name:</strong> {{ $package->name }}</p>
        <p><strong>Price:</strong> {{ $package->price }}</p>
        <p><strong>Credit Points:</strong> {{ $package->credits }}</p>
        <p><strong>Reward Points:</strong> {{ $package->reward_points }}</p>

        <a href="{{ route('admin.packages.index') }}" class="btn">Back</a>
    </div>
@endsection
