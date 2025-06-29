<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $request->user()->id,
        ]);
        try {
            $user = $request->user();
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }
    }
}
