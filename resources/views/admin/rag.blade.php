@extends('layouts.admin')

@section('content')
<h2>Admin RAG (Resource Access Gateway)</h2>

<div class="mb-3">
    <label>Chat with the System</label>
    <input type="text" id="message" class="form-control" placeholder="e.g., get users, get products price=100, find orders status=completed">
</div>
<button class="btn btn-primary" onclick="sendMessage()">Send</button>

<div class="alert alert-info mt-2" style="white-space: pre-line;">
    <strong>Examples:</strong>
    <br>get users
    <br>get products price=100
    <br>find orders status=completed
    <br>get purchases user_id=1
    <br>get orderitems order_id=5
    <br>get carts user_id=2
    <br>get roles
    <br>how many users do we have
</div>

<div class="mt-3" id="chat-response" style="white-space: pre-line;"></div>

<script>
function sendMessage() {
    const msg = document.getElementById('message').value;
    fetch("{{ route('admin.rag.chat') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('chat-response').innerText = data.reply;
    });
}
</script>
@endsection
