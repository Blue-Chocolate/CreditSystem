@extends('layouts.admin')

@section('content')
<div>
    <h1>Ask Ai</h1>
    <form method="POST" action="{{ route('rag.ask') }}">
        @csrf
        <input type="text" name="query" value="{{ old('query', $query ?? '') }}" placeholder="Ask a question..." required>
        <button type="submit">Ask</button>
    </form>
    @isset($answer)
        <div>
            <h2>Answer:</h2>
            <p>{{ $answer }}</p>
        </div>
    @endisset
</div>
@endsection
