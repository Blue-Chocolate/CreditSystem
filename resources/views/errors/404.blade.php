@extends('layouts.admin')
@section('content')
<div class="text-center mt-5">
    <h1 class="display-4">404 - Page Not Found</h1>
    <p class="lead">Sorry, the page you are looking for does not exist.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go Home</a>
</div>
@endsection
