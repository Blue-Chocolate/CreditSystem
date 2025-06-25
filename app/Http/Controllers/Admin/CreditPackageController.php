<?php

namespace App\Http\Controllers\Admin;
use App\Actions\CreditPackage\StoreCreditPackageAction;
use App\Actions\CreditPackage\UpdateCreditPackageAction;
use App\Actions\CreditPackage\DeleteCreditPackageAction;
use App\Models\CreditPackage;
use Illuminate\Http\Request;

class CreditPackageController
{
    public function index()
    {
        $packages = CreditPackage::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request, StoreCreditPackageAction $action)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'credits' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);

        $action->handle($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created');
    }
    public function show (CreditPackage $package)
    {
        return view('admin.packages.show', compact('package'));
    }
}
