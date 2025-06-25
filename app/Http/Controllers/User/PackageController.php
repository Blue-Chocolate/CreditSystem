<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Packages\PackageRepository;
use App\Actions\Package\BuyPackageAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packages;
    protected $buyAction;

    public function __construct(PackageRepository $packages, BuyPackageAction $buyAction)
    {
        $this->packages = $packages;
        $this->buyAction = $buyAction;
    }

    public function index()
    {
        $packages = $this->packages->all();
        return view('users.packages.index', compact('packages'));
    }

    public function buy($id)
    {
        $user = Auth::user();
        try {
            $package = $this->buyAction->execute($user, $id);
            // Reload user from DB to get updated balance and relationships
            $user = \App\Models\User::find($user->id);
            return redirect()->route('user.packages.index')->with([
                'success' => 'Package bought successfully! Your new balance is $' . number_format($user->credit_balance, 2),
                'balance' => $user->credit_balance
            ]);
        } catch (\Exception $e) {
            return redirect()->route('user.packages.index')->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $user = \App\Models\User::find(Auth::id());
        $purchases = $user->purchases()->with('package')->orderBy('purchased_at', 'desc')->get();
        return view('users.packages.history', compact('purchases'));
    }
}
