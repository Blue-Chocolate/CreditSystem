<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class RagChatController extends Controller
{
    public function chat(Request $request)
    {
        $msg = strtolower($request->input('message'));
        $reply = '';

        if (str_starts_with($msg, 'search users')) {
            $query = $this->extractQuery($msg);
            $users = User::where('name', 'like', "%{$query}%")
                         ->orWhere('email', 'like', "%{$query}%")
                         ->limit(10)->get();
            $reply = $users->isEmpty() ? "No users found." : $users->map(fn($u) => "{$u->id}) {$u->name} - {$u->email}")->implode("\n");

        } elseif (str_starts_with($msg, 'search products')) {
            $query = $this->extractQuery($msg);
            $products = Product::where('name', 'like', "%{$query}%")->limit(10)->get();
            $reply = $products->isEmpty() ? "No products found." : $products->map(fn($p) => "{$p->id}) {$p->name} - {$p->price} EGP")->implode("\n");

        } elseif (str_starts_with($msg, 'create user')) {
            $data = $this->parseParams($msg);
            $validator = Validator::make($data, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                $reply = "Error: " . implode(' ', $validator->errors()->all());
            } else {
                User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => bcrypt($data['password'])]);
                $reply = "User created successfully.";
            }

        } elseif (str_starts_with($msg, 'delete user')) {
            $data = $this->parseParams($msg);
            $user = User::find($data['id'] ?? null);
            if ($user) {
                $user->delete();
                $reply = "User deleted.";
            } else {
                $reply = "User not found.";
            }

        } elseif (str_starts_with($msg, 'update user')) {
            $data = $this->parseParams($msg);
            $user = User::find($data['id'] ?? null);
            if ($user) {
                $user->update(array_filter($data));
                $reply = "User updated.";
            } else {
                $reply = "User not found.";
            }

        } else {
            $reply = "Commands I understand:\n- search users name=John\n- search products name=Hoodie\n- create user name=John email=john@example.com password=secret\n- delete user id=5\n- update user id=5 name=NewName";
        }

        return response()->json(['reply' => $reply]);
    }

    private function extractQuery($msg)
    {
        $parts = explode(' ', $msg, 3);
        return $parts[2] ?? '';
    }

    private function parseParams($msg)
    {
        preg_match_all('/(\w+)=([^\s]+)/', $msg, $matches, PREG_SET_ORDER);
        $params = [];
        foreach ($matches as $match) {
            $params[$match[1]] = $match[2];
        }
        return $params;
    }
}
