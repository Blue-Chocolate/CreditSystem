<?php 


namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\CreditPackage;
use App\Models\Purchase;
use Carbon\Carbon;

class DashboardService
{
    public function getStats(): array
    {
        $today = Carbon::today();

        return [
            'total_users' => User::count(),
            'total_packages' => CreditPackage::count(),
            'today_revenue' => Purchase::whereDate('created_at', $today)->sum('credits_received'),
            'package_usage' => Purchase::selectRaw('credit_package_id, COUNT(*) as usage_count')
                ->groupBy('credit_package_id')
                ->with('creditPackage')
                ->get()
        ];
    }
}
