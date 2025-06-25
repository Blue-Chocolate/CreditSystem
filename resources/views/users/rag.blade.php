@extends('layouts.user')

@section('content')
<h2>My Smart Assistant (RAG)</h2>

<div class="mb-3">
    <strong>Balance:</strong> {{ $user->credit_balance }} EGP<br>
    <strong>Credit Points:</strong> {{ $user->credit_points }}<br>
    <strong>Reward Points:</strong> {{ $user->reward_points }}
</div>

<h4>Products you can buy:</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price (EGP)</th>
            <th>Reward Points (Offer Pool)</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->is_offer_pool ? $product->reward_points : '-' }}</td>
                <td>{{ $product->category }}</td>
                <td>
                    @if($user->credit_points >= $product->price || ($product->is_offer_pool && $user->reward_points >= $product->reward_points))
                        <a href="{{ route('user.products.show', $product->id) }}" class="btn btn-sm btn-success">View/Buy</a>
                    @else
                        <span class="text-muted">Not enough points</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<h4>Ask the Assistant:</h4>
<div class="mb-3">
    <input type="text" id="chat-input" class="form-control" placeholder="Type your question..." />
</div>
<div>
    <button id="send-btn" class="btn btn-primary">Send</button>
</div>

<div class="mt-3">
    <h5>Assistant Reply:</h5>
    <div id="chat-reply" class="border p-3" style="min-height: 100px;"></div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('send-btn').addEventListener('click', function () {
        const message = document.getElementById('chat-input').value.trim();
        if (message === '') return;

        fetch("{{ route('user.rag.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('chat-reply').innerText = data.reply || 'No response.';
        })
        .catch(err => {
            console.error(err);
            document.getElementById('chat-reply').innerText = 'Error contacting assistant.';
        });
    });
</script>
@endsection
