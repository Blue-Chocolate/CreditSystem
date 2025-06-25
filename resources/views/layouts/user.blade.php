<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>4Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #74b9ff;
            --primary-hover: #4aa3ff;
            --background: #f0f7fb;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--background);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 12px 20px;
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--primary-hover);
        }

        .navbar .ms-auto a {
            margin-left: 15px;
            font-size: 0.95rem;
            color: #495057;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar .ms-auto a:hover {
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .user-info {
            background: #eaf4fb;
            padding: 6px 12px;
            border-radius: 8px;
            margin-left: 15px;
            font-size: 0.9rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info strong {
            color: var(--primary-color);
        }

        .btn-danger {
            margin-left: 20px;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .search-bar {
            max-width: 350px;
            margin-left: auto;
            margin-right: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #cart-sidebar {
            width: 300px;
            background: white;
            position: fixed;
            right: 20px;
            top: 80px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1000;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #eaf4fb;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: background 0.3s ease;
        }

        .cart-item:hover {
            background: #d0e8f7;
        }

        .cart-footer {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
        }

        /* Tooltip Simulation */
        [title] {
            position: relative;
        }

        [title]:hover::after {
            content: attr(title);
            position: absolute;
            background: #000;
            color: #fff;
            padding: 4px 8px;
            font-size: 0.75rem;
            border-radius: 4px;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            opacity: 0.9;
            margin-top: 4px;
            z-index: 2000;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}" title="Go to Dashboard">Rio</a>

        <form method="GET" action="{{ route('search.index') }}" class="search-bar" id="search-form" title="Search for products">
            <input type="text" name="q" id="search-input" class="form-control" placeholder="Search products..." value="{{ request('q') }}" autocomplete="off">
            <button class="btn btn-primary" type="submit" title="Start Search">Search</button>
            <div id="autocomplete-list" class="list-group position-absolute w-100" style="z-index: 10;"></div>
        </form>

        <div class="ms-auto d-flex align-items-center flex-wrap">
            <a href="{{ route('user.orders.index') }}" title="View Current Orders">Orders</a>
            <a href="{{ route('user.orders.history') }}" title="Order History">History</a>
            <a href="{{ route('user.packages.index') }}" title="Buy Credit Packages">Packages</a>
            <a href="{{ route('user.packages.history') }}" title="Your Package History">Pkg History</a>
            <a href="{{ route('user.cart.show') }}" title="View Cart">Cart</a>
            <a href="{{ route('search.index') }}" title="Search Again">Search</a>

            <div class="user-info" title="Your account details">
                <strong>{{ auth()->user()->name }}</strong> |
                Balance: ${{ auth()->user()->credit_balance }} |
                Credit: {{ auth()->user()->credit_points }} |
                Reward: {{ auth()->user()->reward_points }}
            </div>

            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-3" title="Logout">
                @csrf
                <button class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<div id="cart-sidebar" title="Your Cart">
    <h5 class="mb-3">Your Cart</h5>
    @if(isset($cartSidebarItems) && count($cartSidebarItems))
        @foreach($cartSidebarItems as $item)
            @if($item->product)
            <div class="cart-item" title="{{ $item->product->name }} x{{ $item->quantity }}">
                <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
            </div>
            @endif
        @endforeach
        <div class="cart-footer" title="Total Price">
            Total: ${{ number_format($cartSidebarTotal, 2) }}
        </div>
    @else
        <div class="cart-footer">Cart is empty</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search-input');
    const list = document.getElementById('autocomplete-list');
    let timeout = null;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const val = this.value;
        if (!val) { list.innerHTML = ''; return; }
        timeout = setTimeout(() => {
            fetch(`{{ route('search.autocomplete') }}?q=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    data.forEach(item => {
                        const el = document.createElement('button');
                        el.type = 'button';
                        el.className = 'list-group-item list-group-item-action';
                        el.innerHTML = item.replace(new RegExp(val, 'gi'), match => `<mark>${match}</mark>`);
                        el.onclick = () => { input.value = item; list.innerHTML = ''; document.getElementById('search-form').submit(); };
                        list.appendChild(el);
                    });
                });
        }, 200);
    });
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) list.innerHTML = '';
    });
});
</script>

<script>
// Draggable cart sidebar
(function() {
    const sidebar = document.getElementById('cart-sidebar');
    let isDragging = false, offsetX = 0, offsetY = 0;
    sidebar.style.cursor = 'move';
    sidebar.addEventListener('mousedown', function(e) {
        isDragging = true;
        offsetX = e.clientX - sidebar.offsetLeft;
        offsetY = e.clientY - sidebar.offsetTop;
        sidebar.style.zIndex = 2000;
    });
    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            sidebar.style.left = (e.clientX - offsetX) + 'px';
            sidebar.style.top = (e.clientY - offsetY) + 'px';
            sidebar.style.right = 'auto';
        }
    });
    document.addEventListener('mouseup', function() {
        isDragging = false;
    });
})();
</script>

</body>
</html>
