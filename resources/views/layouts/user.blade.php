<!DOCTYPE html>
<html>
<head>
    <title>4Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #cart-toggle {
            position: fixed;
            right: 310px;
            top: 20px;
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 20px 0 0 20px;
            cursor: pointer;
            z-index: 1001;
        }

        #cart-sidebar {
            width: 300px;
            background: #fff;
            position: fixed;
            right: -300px;
            top: 0;
            height: 100%;
            box-shadow: -2px 0 8px rgba(0,0,0,0.2);
            border-radius: 12px 0 0 12px;
            overflow-y: auto;
            padding: 20px;
            transition: right 0.3s ease;
            z-index: 1000;
        }

        #cart-sidebar.show {
            right: 0;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .cart-item:hover {
            background: #e2e6ea;
        }

        .cart-item button {
            border: none;
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 4px;
            font-size: 12px;
        }

        .cart-footer {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">4Sale</a>
        <div class="ms-auto">
            Welcome, {{ auth()->user()->name }} |
            Balance: {{ auth()->user()->credit_balance }}$ |
            Credit Points: {{ auth()->user()->credit_points }} |
            Reward Points: {{ auth()->user()->reward_points }}

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger btn-sm ms-2">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<div id="cart-toggle">🛒 Cart</div>

<div id="cart-sidebar">
    <h4>Your Cart</h4>
    <div id="cart-items"></div>
    <div class="cart-footer">
        Total: $<span id="cart-total">0.00</span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let cart = [];

function renderCart() {
    let container = document.getElementById('cart-items');
    let total = 0;
    container.innerHTML = '';
    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        container.innerHTML += `
            <div class="cart-item" onclick="window.location.href='/user/products/${item.id}'">
                <span>${item.name} x ${item.quantity}</span>
                <div>
                    <button onclick="event.stopPropagation(); changeQty(${index}, 1)">+</button>
                    <button onclick="event.stopPropagation(); changeQty(${index}, -1)">-</button>
                </div>
            </div>`;
    });
    document.getElementById('cart-total').innerText = total.toFixed(2);
}

function addToCart(product) {
    let existing = cart.find(p => p.id == product.id);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({...product, quantity: 1});
    }
    renderCart();
}

function changeQty(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) cart.splice(index, 1);
    renderCart();
}

document.getElementById('cart-toggle').addEventListener('click', () => {
    document.getElementById('cart-sidebar').classList.toggle('show');
});
</script>
</body>
</html>
