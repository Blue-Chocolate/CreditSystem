<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Actions\User\IndexUserAction;
use App\Actions\User\ShowUserAction;
use App\Actions\User\EditUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\DestroyUserAction;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(IndexUserAction $action)
    {
        $users = $action->handle($this->service, 15);
        return view('admin.users.index', compact('users'));
    }

    public function show($id, ShowUserAction $action)
    {
        $user = $action->handle($this->service, $id);
        if ($user) {
            return view('admin.users.show', compact('user'));
        }
        return redirect()->back()->with('error', 'User not found.');
    }

    public function edit($id, EditUserAction $action)
    {
        $user = $action->handle($this->service, $id);
        if ($user) {
            return view('admin.users.edit', compact('user'));
        }
        return redirect()->back()->with('error', 'User not found.');
    }

    public function update(Request $request, $id, UpdateUserAction $action)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'points_balance' => 'required|integer|min:0',
        ]);
        if ($action->handle($this->service, $id, $data)) {
            return redirect()->route('admin.users.index')->with('success', 'User updated.');
        }
        return redirect()->back()->with('error', 'User not found.');
    }

    public function destroy($id, DestroyUserAction $action)
    {
        if ($action->handle($this->service, $id)) {
            return redirect()->route('admin.users.index')->with('success', 'User deleted.');
        }
        return redirect()->back()->with('error', 'User not found.');
    }
}
