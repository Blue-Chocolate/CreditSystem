@extends('layouts.user')
@section('content')
<h2>Available Packages</h2>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Credits</th><th>Price</th><th>Reward Points</th><th>Action</th></tr></thead>
    <tbody>
        @foreach($packages as $package)
        <tr>
            <td>{{ $package->id }}</td>
            <td>{{ $package->name ?? 'N/A' }}</td>
            <td>${{ $package->price ?? '-' }}</td>
            <td>{{ $package->credits ?? '-' }}</td>
    
            <td>{{ $package->reward_points?? '-'}}</td>
            
            <td>
                <form action="{{ route('user.packages.buy', $package->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Buy</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
        {{ $packages->links() }}
    </div>
@endsection
