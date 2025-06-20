<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CreditPackage\CreditPackageService;

class CreditPackageController extends Controller
{
    protected $service;

    public function __construct(CreditPackageService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price_egp' => 'required|numeric',
            'credit_amount' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price_egp' => 'required|numeric',
            'credit_amount' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);

        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json([
            'deleted' => $this->service->delete($id)
        ]);
    }
}
