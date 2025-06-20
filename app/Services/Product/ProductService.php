<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $repo;

    public function __construct(ProductRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list($perPage = 10)
    {
        return $this->repo->paginate($perPage);
    }

    public function show($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
