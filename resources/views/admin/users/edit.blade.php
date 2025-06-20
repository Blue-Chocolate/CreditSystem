@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-xl">
    <h1 class="text-xl font-bold mb-4">Edit User</h1>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf @method('PUT')

        <label>Name:</label>
        <input name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded mb-3" required>

        <label>Role:</label>
        <select name="role" class="w-full border p-2 rounded mb-3">
            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>

        <label>Points Balance:</label>
        <input type="number" name="points_balance" value="{{ old('points_balance', $user->points_balance) }}" class="w-full border p-2 rounded mb-3">

        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Save</button>
    </form>
</div>
@endsection
