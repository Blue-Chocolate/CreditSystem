<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ElSalam Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Breeze assets --}}
    <style>
        :root {
            --primary-color: #74b9ff;
            --primary-hover: #4aa3ff;
            --sidebar-bg: #dcefff;
            --sidebar-hover: #b9e3ff;
            --sidebar-active: #a5d8ff;
            --text-color: #333;
            --background: #f1f6fb;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar .logo {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #aad4f7;
            text-align: center;
            background: var(--sidebar-active);
            color: #074d80;
        }

        .sidebar nav a {
            display: block;
            padding: 1rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            transition: background 0.3s ease, color 0.3s ease;
            border-bottom: 1px solid #cce7f9;
        }

        .sidebar nav a:hover {
            background-color: var(--sidebar-hover);
            color: #074d80;
        }

        .active-link {
            background-color: var(--sidebar-active);
            color: #074d80;
            font-weight: bold;
        }

        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .header form {
            max-width: 400px;
            display: flex;
            gap: 8px;
            width: 100%;
        }

        .header input {
            flex: 1;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        main {
            padding: 2rem;
            flex: 1;
        }

        .footer {
            background-color: #edf5fb;
            padding: 1rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="logo">ElSalam Admin</div>
        <nav class="mt-4">
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active-link' : '' }}">Products</a>
            <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'active-link' : '' }}">Packages</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active-link' : '' }}">Orders</a>
            <a href="{{ url('admin/users') }}" class="{{ request()->is('admin/users*') ? 'active-link' : '' }}">Users</a>
            <a href="{{ url('admin/settings') }}" class="{{ request()->is('admin/settings*') ? 'active-link' : '' }}">Settings</a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main">
        <header class="header">
            <form method="GET" action="{{ route('admin.search') }}">
                <input type="text" name="q" class="form-control" placeholder="Search users, products, packages by name or ID..." value="{{ request('q') }}" autocomplete="off" aria-label="Admin search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="footer">
            &copy; {{ now()->year }} ElSalam. All rights reserved.
        </footer>
    </div>

</body>
</html>
