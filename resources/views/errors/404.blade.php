@extends(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('user') ? 'layouts.user' : 'layouts.admin')
@section('content')
<div class="text-center mt-5">
    <h1 class="display-4">404 - Page Not Found</h1>
    <p class="lead">Sorry, the page you are looking for does not exist.</p>
    <a href="{{ url(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('user') ? '/user/home' : '/') }}" class="btn btn-primary mt-3">Go Home</a>
</div>
@endsection
