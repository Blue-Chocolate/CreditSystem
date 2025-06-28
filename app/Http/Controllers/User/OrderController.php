<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Orders\OrderRepository;
use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Actions\Order\StoreOrderAction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Order;

class OrderController extends Controller
{
    protected $orders;
    protected $createAction;
    protected $updateAction;
    protected $deleteAction;

    public function __construct(OrderRepository $orders, CreateOrderAction $createAction, UpdateOrderAction $updateAction, DeleteOrderAction $deleteAction)
    {
        $this->orders = $orders;
        $this->createAction = $createAction;
        $this->updateAction = $updateAction;
        $this->deleteAction = $deleteAction;
    }

    public function index()
    {
        $orders = $this->orders->paginateForUser(Auth::id(), 10);
        return view('users.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = $this->orders->find($id);
        return view('users.orders.show', compact('order'));
    }

    public function create()
    {
        return view('users.orders.create');
    }

     // ⬇️ Insert the store method here ⬇️
    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'payment_method' => 'required|in:cash,visa,credit_points,reward_points',
        ]);

        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = $request->total;

        // Always check for enough credit points if payment method is credit_points
        if ($request->payment_method === 'credit_points') {
            $requiredPoints = (int) ceil($total); // 1 EGP = 1 credit point
            if ($user->credit_points < $requiredPoints) {
                return back()->with('error', 'Insufficient credit points. You need ' . $requiredPoints . ' credit points.');
            }
        }
        if ($request->payment_method === 'reward_points' && $user->reward_points < $total) {
            return back()->with('error', 'Insufficient reward points.');
        }

        DB::transaction(function () use ($user, $cart, $total, $request) {
            if ($request->payment_method === 'credit_points') {
                $requiredPoints = (int) ceil($total);
                $user->credit_points -= $requiredPoints;
            } elseif ($request->payment_method === 'reward_points') {
                $user->reward_points -= $total;
            } elseif ($request->payment_method === 'cash' || $request->payment_method === 'visa') {
                $user->credit_balance -= $total;
            }
            $user->save();

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'Pending',
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart->items as $item) {
                // Reduce product stock
                $product = $item->product;
                if ($product && $product->stock !== null) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $cart->items()->delete();
        });

        return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    }
    public function edit($id)
    {
        $order = $this->orders->find($id);
        return view('users.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $this->updateAction->execute($id, $data);

        return redirect()->route('user.orders.index')->with('success', 'Order updated.');
    }

public function destroy($id)
{
    $this->deleteAction->execute($id);

    return redirect()->route('user.orders.index')->with('success', 'Order deleted.');
}
    public function history()
    {
        $orders = $this->orders->paginateForUser(Auth::id(), 10);
        return view('users.orders.history', compact('orders'));
    }
}
