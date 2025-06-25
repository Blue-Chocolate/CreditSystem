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
            if ($user->credit_balance < $package->price) {
                throw new Exception('Insufficient balance.');
            }
            // Deduct price
            $user->credit_balance -= $package->price;
            // Add credit points
            $user->credit_points += $package->credits;
            // Add reward points
            $user->reward_points += $package->reward_points;
            if (!$user->save()) {
                throw new Exception('Failed to update user balance.');
            }
            // Log the purchase
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
