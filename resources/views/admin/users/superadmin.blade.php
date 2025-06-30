@extends('layouts.admin')

@section('content')
    <h2>SuperAdmin User Management</h2>
    <div class="mb-3">Total Users: {{ $users->total() }}</div>
    @foreach($users as $user)
        <div class="user-card">
            <div class="user-header">{{ $user->name }} <span class="text-muted">({{ $user->email }})</span></div>
            <div>Balance: ${{ $user->credit_balance }} | Credit: {{ $user->credit_points }} | Reward: {{ $user->reward_points }}</div>
            <div class="mt-2">
                <form action="{{ route('admin.users.impersonate', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Impersonate</button>
                </form>
            </div>
        </div>
    @endforeach
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
@endsection
