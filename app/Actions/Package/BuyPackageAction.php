<?php

namespace App\Actions\Package;

use App\Repositories\Packages\PackageRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Purchase;

class BuyPackageAction
{
    protected $repo;
    public function __construct(PackageRepository $repo)
    {
        $this->repo = $repo;
    }
    public function execute($user, $packageId)
    {
        // Use transaction for safety
        return DB::transaction(function () use ($user, $packageId) {
            $package = $this->repo->find($packageId);
            if (!$package) {
                throw new Exception('Package not found.');
            }
            if ($package->currency && $package->currency !== 'USD') {
                throw new Exception('Currency mismatch. Please contact support.');
            }
            if ($user->credit_balance < $package->price) {
                throw new Exception('Insufficient balance.');
            }
            $user->credit_balance = max(0, round($user->credit_balance - $package->price, 2));
            $user->credit_points = round($user->credit_points + $package->credits, 2);
            $user->reward_points = min(2147483647, $user->reward_points + $package->reward_points); // cap to int(11)
            if (!$user->save()) {
                throw new Exception('Failed to update user balance.');
            }
            Purchase::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'price' => $package->price,
                'credits' => $package->credits,
                'reward_points' => $package->reward_points,
                'purchased_at' => now(),
            ]);
            return $package;
        });
    }
}
