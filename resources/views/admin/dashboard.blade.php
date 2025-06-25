@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text display-6">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Packages</h5>
                    <p class="card-text display-6">{{ $totalPackages }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Today's Sales</h5>
                    <p class="card-text display-6">${{ number_format($totalSalesToday, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection