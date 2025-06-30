<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['orders.items']);
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('email', 'like', "%$q%")
                     ->orWhere('id', $q);
            });
        }
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function impersonate($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if (!auth()->user()->hasRole('SuperAdmin')) {
            abort(403, 'Unauthorized.');
        }
        session(['impersonate' => $user->id]);
        auth()->login($user);
        return redirect('/')->with('success', 'You are now impersonating ' . $user->name);
    }

    public function stopImpersonate()
    {
        if (!session()->has('impersonate')) {
            return redirect('/admin/users')->with('error', 'Not impersonating any user.');
        }
        $originalId = auth()->id();
        $admin = \App\Models\User::role('SuperAdmin')->first();
        session()->forget('impersonate');
        auth()->login($admin);
        return redirect('/admin/users')->with('success', 'Impersonation ended. You are back as SuperAdmin.');
    }

    public function superadminUserManagement()
    {
        if (!auth()->user()->hasRole('SuperAdmin')) {
            abort(403, 'Unauthorized.');
        }
        $users = \App\Models\User::paginate(10);
        return view('admin.users.superadmin', compact('users'));
    }
}
