<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ElSalam Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Breeze assets --}}
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fafb;
        }

        .sidebar {
            background-color: #1f2937;
            color: white;
            min-height: 100vh;
        }

        .sidebar a {
            display: block;
            padding: 1rem;
            color: #d1d5db;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #374151;
            color: white;
        }

        .active-link {
            background-color: #111827;
            color: white;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 1rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
</head>
<body class="flex">

    {{-- Sidebar --}}
    <aside class="sidebar w-64">
        <div class="p-6 text-xl font-bold border-b border-gray-700">ElSalam Admin</div>
        <nav class="mt-6">
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.packages.index') }}">Packages</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ url('admin/users') }}">Users</a>
            <a href="{{ url('admin/settings') }}">Settings</a>

        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-h-screen">
        <header class="p-4 bg-white border-b flex align-items-center justify-content-between">
            <form method="GET" action="{{ route('admin.search') }}" class="d-flex" style="max-width:400px;">
                <input type="text" name="q" class="form-control me-2" placeholder="Search users, products, packages by name or ID..." value="{{ request('q') }}" autocomplete="off" aria-label="Admin search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <footer class="footer">
            &copy; {{ now()->year }} ElSalam. All rights reserved.
        </footer>
    </div>

</body>
</html>
