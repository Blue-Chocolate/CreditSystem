<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    
    use Searchable;

    protected $fillable = ['name', 'price', 'stock', 'is_offer_pool', 'reward_points', 'image', 'image_url', 'category'];
    protected $casts = [
        'is_offer_pool' => 'boolean',
        'reward_points' => 'integer',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_offer_pool' => $this->is_offer_pool,
            'reward_points' => $this->reward_points,
        ];
    }
}
