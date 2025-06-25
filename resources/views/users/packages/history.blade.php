@extends('layouts.user')
@section('content')
<h2>Package Purchase History</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Package</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($packages as $package)
        <tr>
            <td>{{ $package->id }}</td>
            <td>{{ $package->name }}</td>
            <td>{{ $package->amount }}</td>
            <td>{{ $package->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
        {{ $packages->links() }}
    </div>
@endsection
