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
        $packages = CreditPackage::paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }
    public function edit(CreditPackage $package)
    {
        $now = now();
        $lockTimeout = 10; // minutes

        if ($package->locked_by && $package->locked_by !== auth()->id() && $package->locked_at) {
            try {
                $lockedAt = $package->locked_at instanceof \Carbon\Carbon ? $package->locked_at : \Carbon\Carbon::parse($package->locked_at);
                if ($lockedAt->diffInMinutes($now) < $lockTimeout) {
                    $lockedUser = \App\Models\User::find($package->locked_by);
                    $lockedByName = $lockedUser ? $lockedUser->name : 'another admin';
                    $lockedAtFormatted = $lockedAt->format('Y-m-d H:i:s');
                    return back()->with('error', "This package is currently being edited by {$lockedByName} (locked at {$lockedAtFormatted}). Please try again later.");
                }
            } catch (\Exception $e) {
                return back()->with('error', 'This package is currently under maintenance.');
            }
        }

        $package->locked_by = auth()->id();
        $package->locked_at = $now;
        $package->save();

        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, CreditPackage $package, UpdateCreditPackageAction $action)
    {
        if ($package->locked_by && $package->locked_by !== auth()->id()) {
            return back()->with('error', 'You do not own the lock for this package.');
        }
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'credits' => 'required|integer',
            'reward_points' => 'required|integer',
        ]);
        $action->handle($package, $validated);
        $package->locked_by = null;
        $package->locked_at = null;
        $package->save();
        return redirect()->route('admin.packages.index')->with('success', 'Package updated');
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
    public function destroy(CreditPackage $package, DeleteCreditPackageAction $action)
{
    $action->handle($package);

    return redirect()->route('admin.packages.index')->with('success', 'Package deleted');
}
}
