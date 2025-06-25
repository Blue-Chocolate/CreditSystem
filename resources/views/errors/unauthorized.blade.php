@extends('layouts.admin')
@section('content')
<div class="text-center mt-5">
    <h1 class="display-4">403 - Unauthorized</h1>
    <p class="lead">You are not authorized to access this page.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go Home</a>
</div>
@endsection
