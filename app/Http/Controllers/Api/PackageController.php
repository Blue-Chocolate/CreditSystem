<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        return response()->json(CreditPackage::all());
    }
    public function buy(Request $request, $id)
    {
        try {
            // Buy package logic here
            return response()->json(['message' => 'Package purchased']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to purchase package', 'error' => $e->getMessage()], 500);
        }
    }
    public function history(Request $request)
    {
        try {
            // Return package purchase history logic here
            return response()->json(['history' => []]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to get history', 'error' => $e->getMessage()], 500);
        }
    }
}
