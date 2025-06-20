@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow">
            <h2 class="text-sm text-gray-600">Total Users</h2>
            <p class="text-xl font-semibold">{{ $stats['total_users'] }}</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow">
            <h2 class="text-sm text-gray-600">Today's Revenue</h2>
            <p class="text-xl font-semibold">{{ $stats['today_revenue'] }} EGP</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow">
            <h2 class="text-sm text-gray-600">Total Packages</h2>
            <p class="text-xl font-semibold">{{ $stats['total_packages'] }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-2">Package Usage</h3>
        <table class="min-w-full bg-white rounded-xl shadow">
            <thead>
                <tr>
                    <th class="text-left p-3 border-b">Package ID</th>
                    <th class="text-left p-3 border-b">Usage Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats['package_usage'] as $usage)
                    <tr>
                        <td class="p-3 border-b">{{ $usage->credit_package_id }}</td>
                        <td class="p-3 border-b">{{ $usage->usage_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
