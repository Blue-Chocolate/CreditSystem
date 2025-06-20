<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->list(15);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        try {
            $user = $this->service->show($id);
            return view('admin.users.show', compact('user'));
        } catch (ModelNotFoundException) {
            return redirect()->back()->with('error', 'User not found.');
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->service->show($id);
            return view('admin.users.edit', compact('user'));
        } catch (ModelNotFoundException) {
            return redirect()->back()->with('error', 'User not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'points_balance' => 'required|integer|min:0',
        ]);

        try {
            $this->service->update($id, $data);
            return redirect()->route('admin.users.index')->with('success', 'User updated.');
        } catch (ModelNotFoundException) {
            return redirect()->back()->with('error', 'User not found.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('admin.users.index')->with('success', 'User deleted.');
        } catch (ModelNotFoundException) {
            return redirect()->back()->with('error', 'User not found.');
        }
    }
}
