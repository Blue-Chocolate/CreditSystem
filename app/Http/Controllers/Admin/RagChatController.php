<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class RagChatController extends Controller
{
    protected $modelMap = [
    'user' => \App\Models\User::class,
    'users' => \App\Models\User::class,
    'product' => \App\Models\Product::class,
    'products' => \App\Models\Product::class,
];

    public function chat(Request $request)
    {
        $msg = strtolower($request->input('message'));
        $reply = '';

        // how many users/products
        if (preg_match('/how many (\w+) (are there|do we have)/', $msg, $matches)) {
            $reply = $this->count($matches[1]);

        // get users/products with optional filters
        } elseif (preg_match('/^(get|find) (\w+)(.*)$/', $msg, $matches)) {
            $reply = $this->get($matches[2], trim($matches[3]));

        // create user/product
        } elseif (preg_match('/^create (\w+)/', $msg, $matches)) {
            $reply = $this->create($matches[1], $msg);

        // update user/product
        } elseif (preg_match('/^update (\w+)/', $msg, $matches)) {
            $reply = $this->update($matches[1], $msg);

        // delete user/product
        } elseif (preg_match('/^delete (\w+)/', $msg, $matches)) {
            $reply = $this->delete($matches[1], $msg);

        // help message
        } else {
            $reply = "Commands I understand:\n"
                   . "- get users\n"
                   . "- get products price=250\n"
                   . "- how many users do we have\n"
                   . "- create user name=John email=john@example.com password=secret\n"
                   . "- create product name=Hoodie price=200\n"
                   . "- update user id=5 name=NewName\n"
                   . "- update product id=3 price=199\n"
                   . "- delete user id=5\n"
                   . "- delete product id=3";
        }

        return response()->json(['reply' => $reply]);
    }

    private function count($entity)
    {
        $model = $this->modelMap[$entity] ?? null;
        if (!$model) return "I don't recognize '$entity'";
        $count = $model::count();
        return "There are $count $entity.";
    }

    private function get($entity, $filters)
    {
        $model = $this->modelMap[$entity] ?? null;
        if (!$model) return "Model '$entity' not supported.";

        $query = $model::query();

        if ($filters) {
            preg_match_all('/(\w+)\s*=\s*("[^"]+"|\'[^\']+\'|[^\s]+)/', $filters, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $query->where($m[1], trim($m[2], "\"'"));
            }
        }

        $results = $query->limit(10)->get();
        if ($results->isEmpty()) return "No results found.";

        return $results->map(function ($item) {
            return collect($item->toArray())->map(fn($v, $k) => "$k=$v")->implode(', ');
        })->implode("\n");
    }

    private function create($entity, $msg)
    {
        $model = $this->modelMap[$entity] ?? null;
        if (!$model) return "Model '$entity' not supported.";

        $data = $this->parseParams($msg);

        if ($entity === 'users') {
            $validator = Validator::make($data, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return "Error: " . implode(' ', $validator->errors()->all());
            }
            $data['password'] = bcrypt($data['password']);
        }

        $model::create($data);
        return ucfirst($entity) . " created successfully.";
    }

    private function update($entity, $msg)
    {
        $model = $this->modelMap[$entity] ?? null;
        if (!$model) return "Model '$entity' not supported.";

        $data = $this->parseParams($msg);
        $id = $data['id'] ?? null;
        if (!$id) return "Missing ID.";

        unset($data['id']);

        if ($entity === 'users' && isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $record = $model::find($id);
        if (!$record) return ucfirst($entity) . " not found.";

        $record->update($data);
        return ucfirst($entity) . " updated.";
    }

    private function delete($entity, $msg)
    {
        $model = $this->modelMap[$entity] ?? null;
        if (!$model) return "Model '$entity' not supported.";

        $data = $this->parseParams($msg);
        $id = $data['id'] ?? null;
        if (!$id) return "Missing ID.";

        $record = $model::find($id);
        if (!$record) return ucfirst($entity) . " not found.";

        $record->delete();
        return ucfirst($entity) . " deleted.";
    }

    private function parseParams($msg)
    {
        preg_match_all('/(\w+)\s*=\s*("[^"]+"|\'[^\']+\'|[^\s]+)/', $msg, $matches, PREG_SET_ORDER);
        $params = [];
        foreach ($matches as $match) {
            $params[$match[1]] = trim($match[2], '"\'');
        }
        return $params;
    }
}
