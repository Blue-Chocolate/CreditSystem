<?php
namespace App\Actions\Product;

use App\Models\Category;

class CreateProductAction
{
    public function handle()
    {
        return Category::all();
    }
}
