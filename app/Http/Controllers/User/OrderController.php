<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Orders\OrderRepository;
use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Actions\Order\DeleteOrderAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $orders = $this->orders->allForUser(Auth::id());
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'total' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);
        $user = Auth::user();
        $cart = \App\Models\Cart::with('items.product')->where('user_id', $user->id)->first();
        $total = $data['total'];
        $paymentMethod = $data['payment_method'];
        $allInOfferPool = true;
        $creditPointsEarned = 0;
        $rewardPointsEarned = 0;
        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Cart is empty.');
        }
        // Check payment method and user balance/points
        if ($paymentMethod === 'cash') {
            if ($user->credit_balance < $total) {
                return back()->with('error', 'Insufficient balance.');
            }
            $user->credit_balance -= $total;
        } elseif ($paymentMethod === 'credit_points') {
            if ($user->credit_points < $total) {
                return back()->with('error', 'Insufficient credit points.');
            }
            $user->credit_points -= $total;
        } elseif ($paymentMethod === 'reward_points') {
            foreach ($cart->items as $item) {
                if (!$item->product->is_offer_pool) {
                    $allInOfferPool = false;
                    break;
                }
            }
            if (!$allInOfferPool) {
                return back()->with('error', 'All items must be in offer pool to use reward points.');
            }
            if ($user->reward_points < $total) {
                return back()->with('error', 'Insufficient reward points.');
            }
            $user->reward_points -= $total;
        }
        // Calculate credit points and reward points
        $creditPointsEarned = (int) $total; // 1 EGP = 1 credit point
        $rewardPointsEarned = intdiv($creditPointsEarned, 10); // 10 credit points = 1 reward point
        // Add reward points if any product is in offer pool
        foreach ($cart->items as $item) {
            if ($item->product->is_offer_pool) {
                $user->reward_points += $rewardPointsEarned;
                break;
            }
        }
        $user->credit_points += $creditPointsEarned;
        $user->save();
        $data['user_id'] = $user->id;
        $order = $this->createAction->execute($data);
        // Move cart items to order_items
        foreach ($cart->items as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }
        $cart->items()->delete();
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
        return redirect()->route('user.orders.index');
    }

    public function destroy($id)
    {
        $this->deleteAction->execute($id);
        return redirect()->route('user.orders.index');
    }

    public function history()
    {
        $orders = $this->orders->allForUser(Auth::id());
        return view('users.orders.history', compact('orders'));
    }
}
