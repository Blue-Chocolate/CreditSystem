@extends('layouts.user')
@section('content')
<h2>Available Packages</h2>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Credits</th><th>Price</th><th>Reward Points</th><th>Action</th></tr></thead>
    <tbody>
        @foreach($packages as $package)
        <tr>
            <td>{{ $package->id }}</td>
            <td>{{ $package->name ?? 'N/A' }}</td>
           
            <td>{{ $package->credits ?? '-' }}</td>
             <td>${{ $package->price ?? '-' }}</td>
            <td>{{ $package->reward_points?? '-'}}</td>
            <td>
                <form action="{{ route('user.packages.buy', $package->id) }}" method="POST" onsubmit="return confirmPackageBuy(this, '{{ $package->name }}', '{{ $package->price }}');">
                    @csrf
                    <button class="btn btn-success">Buy</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    @if(method_exists($packages, 'links'))
        {{ $packages->links() }}
    @endif
</div>
<script>
function confirmPackageBuy(form, name, price) {
    return confirm('Are you sure you want to buy the package "' + name + '" for $' + price + '?');
}
</script>
@endsection
