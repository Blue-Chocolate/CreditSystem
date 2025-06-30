<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rio Admin</title>
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

        /* Chatbot styles */
        #admin-chatbot-icon {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            cursor: pointer;
        }

        #admin-chatbot-modal {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            max-width: 90vw;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            overflow: hidden;
        }

        #admin-chatbot-modal .modal-header {
            background: #007bff;
            color: #fff;
            padding: 12px 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #admin-chatbot-modal .modal-body {
            padding: 16px;
            max-height: 350px;
            overflow-y: auto;
            font-size: 15px;
        }

        #admin-chatbot-modal .modal-footer {
            display: flex;
            border-top: 1px solid #eee;
        }

        #admin-chatbot-modal .modal-footer input {
            flex: 1;
            border: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="logo">Rio Admin</div>
        <nav class="mt-4">
            <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'active-link' : '' }}">Packages</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active-link' : '' }}">Products</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active-link' : '' }}">Orders</a>
            <a href="{{ url('admin/users') }}" class="{{ request()->is('admin/users*') ? 'active-link' : '' }}">Users</a>
            <a href="{{ route('admin.superadmin.users') }}" class="{{ request()->routeIs('admin.superadmin.users') ? 'active-link' : '' }}">
                User Management
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-danger w-100">Logout</button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main">
        <header class="header">
            @if(request()->routeIs('admin.products.*'))
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <input type="text" name="q" class="form-control" placeholder="Search products by ID, name, offer pool, etc..." value="{{ request('q') }}" autocomplete="off">
                    <select name="offer_pool" class="form-control">
                        <option value="">All</option>
                        <option value="1" {{ request('offer_pool')=='1' ? 'selected' : '' }}>Offer Pool</option>
                        <option value="0" {{ request('offer_pool')==='0' ? 'selected' : '' }}>Regular</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            @elseif(request()->routeIs('admin.users.*') || request()->is('admin/users*'))
                <form method="GET" action="{{ url('admin/users') }}">
                    <input type="text" name="q" class="form-control" placeholder="Search users by ID or name..." value="{{ request('q') }}" autocomplete="off">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            @elseif(request()->routeIs('admin.packages.*'))
                <form method="GET" action="{{ route('admin.packages.index') }}">
                    <input type="text" name="q" class="form-control" placeholder="Search packages by ID or name..." value="{{ request('q') }}" autocomplete="off">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            @endif
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="footer">
            &copy; {{ now()->year }} ElSalam. All rights reserved.
        </footer>
    </div>

    <!-- Floating Chatbot Icon -->
    <div id="admin-chatbot-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/4712/4712035.png" alt="Chatbot" width="60" height="60">
    </div>
    <!-- Chatbot Modal -->
    <div id="admin-chatbot-modal">
        <div class="modal-header">
            Admin Assistant
            <span id="admin-chatbot-close" style="cursor:pointer;font-size:20px;">&times;</span>
        </div>
        <div id="admin-chatbot-body" class="modal-body"></div>
        <form id="admin-chatbot-form" class="modal-footer">
            <input type="text" id="admin-chatbot-input" class="form-control" placeholder="Search users or products..." style="flex:1;border:none;">
            <button class="btn btn-primary" type="submit">Send</button>
        </form>
    </div>
    <script>
    document.getElementById('admin-chatbot-icon').onclick = function() {
        document.getElementById('admin-chatbot-modal').style.display = 'block';
    };
    document.getElementById('admin-chatbot-close').onclick = function() {
        document.getElementById('admin-chatbot-modal').style.display = 'none';
    };
    document.getElementById('admin-chatbot-form').onsubmit = function(e) {
        e.preventDefault();
        var input = document.getElementById('admin-chatbot-input');
        var body = document.getElementById('admin-chatbot-body');
        var msg = input.value.trim();
        if (!msg) return;
        body.innerHTML += '<div style="margin-bottom:8px;"><b>You:</b> '+msg+'</div>';
        input.value = '';
        fetch('/admin/rag/chat', {
            method: 'POST',
            headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({message: msg})
        })
        .then(r=>r.json())
        .then(d=>{
            body.innerHTML += '<div style="margin-bottom:8px;"><b>Bot:</b> '+d.reply+'</div>';
            body.scrollTop = body.scrollHeight;
        });
    };
    </script>
</body>
</html>
