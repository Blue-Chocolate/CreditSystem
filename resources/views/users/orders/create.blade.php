@extends('layouts.user')
@section('content')
<h2>Create Order</h2>
<form method="POST" action="{{ route('user.orders.store') }}" onsubmit="return confirmOrderCheckout();">
    @csrf
    <div class="mb-3">
        <label>Total</label>
        <input type="number" step="0.01" name="total" class="form-control" value="{{ $total }}" readonly required>
    </div>
    <div class="mb-3">
        <label>Payment Method</label>
        <div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pay_balance" value="balance" required>
                <label class="form-check-label" for="pay_balance">
                    Credit Balance (${{ auth()->user()->credit_balance }})
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pay_points" value="points" required>
                <label class="form-check-label" for="pay_points">
                    Credit Points ({{ auth()->user()->credit_points }})
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pay_reward" value="reward" required>
                <label class="form-check-label" for="pay_reward">
                    Reward Points ({{ auth()->user()->reward_points }})
                </label>
            </div>
        </div>
    </div>
    <button class="btn btn-primary">Create</button>
</form>
<script>
function confirmOrderCheckout() {
    return confirm('Are you sure you want to place this order?');
}
</script>
@endsection
