<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Repositories\Packages\PackageRepository;
use App\Actions\Package\BuyPackageAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB Facade
use App\Models\CreditPackage;
use App\Models\User;


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
        try {
            $packages = $this->packages->all();
            return view('users.packages.index', compact('packages'));
        } catch (\Throwable $e) {
            \Log::error('Package index error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('user.home')->with('error', 'Unable to load packages. Please try again later.');
        }
    }

  public function buy($id)
{
    $userId = Auth::id();
    $cacheKey = "user:{$userId}:buying_package:{$id}";

    if (Cache::has($cacheKey)) {
        return redirect()->route('user.packages.index')->with('error', 'Please wait a few seconds before trying to buy this package again.');
    }

    try {
        DB::beginTransaction();

        $user = \App\Models\User::where('id', $userId)->lockForUpdate()->firstOrFail();

        $package = method_exists($this->packages, 'query') ?
            $this->packages->query()->where('id', $id)->lockForUpdate()->first() :
            \App\Models\CreditPackage::where('id', $id)->lockForUpdate()->first();

        if (!$package) {
            DB::rollBack();
            return redirect()->route('user.packages.index')->with('error', 'This package is no longer available.');
        }

        if ($user->credit_balance < $package->price) {
            DB::rollBack();
            return redirect()->route('user.packages.index')->with('error', 'Insufficient balance.');
        }

        Cache::put($cacheKey, true, now()->addSeconds(10));

        $this->buyAction->execute($user, $id);
        $user->refresh();

        DB::commit();

        return redirect()->route('user.packages.index')->with([
            'success' => 'Package purchased successfully! Your new balance is $' . number_format($user->credit_balance, 2),
            'balance' => $user->credit_balance
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Package buy error: ' . $e->getMessage(), ['exception' => $e]);
        return redirect()->route('user.packages.index')->with('error', 'An error occurred. Please try again.');
    }
}


    public function history()
    {
        try {
            $user = \App\Models\User::findOrFail(Auth::id());
            $purchases = $user->purchases()->with('package')->orderBy('purchased_at', 'desc')->get();
            return view('users.packages.history', compact('purchases'));
        } catch (\Throwable $e) {
            Log::error('Package history error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('user.packages.index')->with('error', 'Unable to load your package history. Please try again later.');
        }
    }
}
