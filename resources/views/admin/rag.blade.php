@extends('layouts.admin')

@section('content')
<h2>Admin RAG (Resource Access Gateway)</h2>

<div class="mb-3">
    <label>Chat with the System</label>
    <input type="text" id="message" class="form-control" placeholder="e.g., search users name=John">
</div>
<button class="btn btn-primary" onclick="sendMessage()">Send</button>

<div class="mt-3" id="chat-response" style="white-space: pre-line;"></div>

<script>
function sendMessage() {
    const msg = document.getElementById('message').value;
    fetch("{{ route('admin.rag.chat') }}", {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('chat-response').innerText = data.reply;
    });
}
</script>
@endsection
