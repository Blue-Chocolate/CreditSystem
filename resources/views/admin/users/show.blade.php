@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User: {{ $user->name }}</h1>

    <p>Email: {{ $user->email }}</p>
    <p>Role: {{ $user->role }}</p>
    <p>Points: {{ $user->points_balance }}</p>

    <h2 class="text-lg font-semibold mt-6 mb-2">Credit Purchases</h2>
    <table class="min-w-full bg-white shadow rounded-xl">
        <thead>
            <tr>
                <th class="p-3 border-b">Package</th>
                <th class="p-3 border-b">Credits</th>
                <th class="p-3 border-b">Reward Points</th>
                <th class="p-3 border-b">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->purchases as $purchase)
            <tr>
                <td class="p-3 border-b">{{ $purchase->creditPackage->name ?? 'N/A' }}</td>
                <td class="p-3 border-b">{{ $purchase->credits_received }}</td>
                <td class="p-3 border-b">{{ $purchase->reward_points_given }}</td>
                <td class="p-3 border-b">{{ $purchase->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
