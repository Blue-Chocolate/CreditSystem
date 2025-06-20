@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">All Users</h1>

    @if(session('success')) <p class="text-green-600">{{ session('success') }}</p> @endif
    @if(session('error')) <p class="text-red-600">{{ session('error') }}</p> @endif

    <table class="min-w-full bg-white shadow rounded-xl">
        <thead>
            <tr>
                <th class="p-3 border-b">Name</th>
                <th class="p-3 border-b">Email</th>
                <th class="p-3 border-b">Role</th>
                <th class="p-3 border-b">Points</th>
                <th class="p-3 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="p-3 border-b">{{ $user->name }}</td>
                <td class="p-3 border-b">{{ $user->email }}</td>
                <td class="p-3 border-b">{{ $user->role }}</td>
                <td class="p-3 border-b">{{ $user->points_balance }}</td>
                <td class="p-3 border-b">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600">View</a> |
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-600">Edit</a> |
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Are you sure?')" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
