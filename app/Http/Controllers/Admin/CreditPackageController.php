<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CreditPackage\CreditPackageService;
use App\Actions\CreditPackage\IndexCreditPackageAction;
use App\Actions\CreditPackage\StoreCreditPackageAction;
use App\Actions\CreditPackage\UpdateCreditPackageAction;
use App\Actions\CreditPackage\DestroyCreditPackageAction;

class CreditPackageController extends Controller
{
    protected $service;

    public function __construct(CreditPackageService $service)
    {
        $this->service = $service;
    }

    public function index(IndexCreditPackageAction $action)
    {
        return response()->json($action->handle($this->service));
    }

    public function store(Request $request, StoreCreditPackageAction $action)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price_egp' => 'required|numeric',
            'credit_amount' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);
        return response()->json($action->handle($this->service, $data));
    }

    public function update(Request $request, $id, UpdateCreditPackageAction $action)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price_egp' => 'required|numeric',
            'credit_amount' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);
        return response()->json($action->handle($this->service, $id, $data));
    }

    public function destroy($id, DestroyCreditPackageAction $action)
    {
        return response()->json([
            'deleted' => $action->handle($this->service, $id)
        ]);
    }
}
