<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="flex">
        <aside class="w-64 bg-white shadow-lg h-screen p-4">
            <h1 class="text-xl font-bold mb-6">Admin Panel</h1>
            <ul class="space-y-3">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a></li>
                <li><a href="{{ route('admin.credit-packages.index') }}" class="text-blue-600 hover:underline">Credit Packages</a></li>
                <li><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Users</a></li>
                <li><a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">Products</a></li>
            </ul>
        </aside>

        <main class="flex-1">
            @yield('content')
        </main>
    </div>

</body>
</html>
