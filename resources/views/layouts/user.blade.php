<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>4Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>

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
        .card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.card-img-top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    height: 200px;
    object-fit: cover;
}

.card-body {
    background: white;
    padding: 16px;
}

.card-title {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 8px;
}

.card-text {
    font-size: 0.9rem;
    color: #555;
}

.badge.bg-info {
    background: var(--primary-color);
    color: white;
}

.badge.bg-success {
    background: #55efc4;
}

.badge.bg-secondary {
    background: #b2bec3;
}

.card-footer {
    background: white;
    border-top: none;
    padding: 12px 16px;
}

.btn-primary {
    background: var(--primary-color);
    border: none;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: var(--primary-hover);
}
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <a href="{{ url('/user/home') }}" title="Home">Home</a>

           <a href="{{ route('user.orders.index') }}" title="Order History">History</a>
            <a href="{{ route('user.packages.index') }}" title="Buy Credit Packages" id="packages">Packages</a>
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
                <span>{{ $item->product->name }}</span>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'decrement']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-secondary" @if($item->quantity <= 1) disabled @endif>-</button>
                    </form>
                    <span>x{{ $item->quantity }}</span>
                    <form action="{{ route('user.cart.update', ['id' => $item->id, 'action' => 'increment']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-secondary">+</button>
                    </form>
                    <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Remove item">&times;</button>
                    </form>
                </div>
                <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                @if($item->product->is_offer_pool)
                    <form action="{{ route('user.cart.redeem', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-sm btn-danger mt-2">Redeem</button>
                    </form>
                @endif
            </div>
            @endif
        @endforeach
        <div class="cart-footer" title="Total Price">
            Total: ${{ number_format($cartSidebarTotal, 2) }}
        </div>
        <div class="d-grid mt-2">
            <a href="{{ route('user.cart.show') }}" class="btn btn-primary">Checkout</a>
        </div>
    @else
        <div class="cart-footer">Cart is empty</div>
    @endif
</div>

<!-- Floating Chatbot Icon -->
<div id="user-chatbot-icon" style="position:fixed;bottom:30px;right:30px;z-index:9999;cursor:pointer;">
    <img src="https://cdn-icons-png.flaticon.com/512/4712/4712035.png" alt="Chatbot" width="60" height="60">
</div>

<!-- Floating User Guide Icon -->
<div id="user-guide-icon" style="position:fixed;bottom:30px;right:100px;z-index:9999;cursor:pointer;">
    <img src="https://cdn-icons-png.flaticon.com/512/633/633759.png" alt="User Guide" width="60" height="60">
</div>

<!-- Chatbot Modal -->
<div id="user-chatbot-modal" style="display:none;position:fixed;bottom:100px;right:30px;width:350px;max-width:90vw;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.2);z-index:10000;overflow:hidden;">
    <div style="background:#007bff;color:#fff;padding:12px 16px;font-weight:bold;display:flex;justify-content:space-between;align-items:center;">
        User Assistant
        <span id="user-chatbot-close" style="cursor:pointer;font-size:20px;">&times;</span>
    </div>
    <div id="user-chatbot-body" style="padding:16px;max-height:350px;overflow-y:auto;font-size:15px;"></div>
    <form id="user-chatbot-form" style="display:flex;border-top:1px solid #eee;">
        <input type="text" id="user-chatbot-input" class="form-control" placeholder="Ask about your balance, points, or what you can buy..." style="flex:1;border:none;">
        <button class="btn btn-primary" type="submit">Send</button>
    </form>
</div>

<!-- Guide Tour on First Login -->
@if(session('show_driver_tour'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    // First-time login tour
    const firstLoginTour = new Driver({
        animate: true,
        allowClose: false,
        showProgress: true,
        steps: [
            { 
                element: '.user-info', 
                popover: { 
                    title: 'Your Balance', 
                    description: 'Here you can see your balance, credit points, and reward points.', 
                    position: 'bottom' 
                } 
            },
            { 
                element: '#packages', 
                popover: { 
                    title: 'Credit Packages', 
                    description: 'Buy credit packages to purchase products and earn reward points.', 
                    position: 'bottom' 
                } 
            },
            { 
                element: '#cart-sidebar', 
                popover: { 
                    title: 'Your Cart', 
                    description: 'Manage your cart items and checkout from here.', 
                    position: 'left' 
                } 
            }
        ]
    });
    firstLoginTour.drive();
});
</script>
@endif

<!-- User Guide Icon Click Handler (keep this OUTSIDE the @if block) -->
<script>
document.getElementById('user-guide-icon').onclick = function() {
    const tour = new Driver({
        animate: true,
        allowClose: true,
        showProgress: true,
        steps: [
            { 
                element: '.user-info', 
                popover: { 
                    title: 'Your Balance', 
                    description: 'Here you can see your balance, credit points, and reward points.', 
                    position: 'bottom' 
                } 
            },
            { 
                element: '#packages', 
                popover: { 
                    title: 'Credit Packages', 
                    description: 'Buy credit packages to purchase products and earn reward points.', 
                    position: 'bottom' 
                } 
            },
            { 
                element: '#cart-sidebar', 
                popover: { 
                    title: 'Your Cart', 
                    description: 'Manage your cart items and checkout from here.', 
                    position: 'left' 
                } 
            }
        ]
    });
    tour.drive();
};
</script>

</body>
</html>
