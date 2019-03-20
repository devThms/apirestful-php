<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Product;

class Category extends Model
{
    protected $filable = [
        'name',
        'description'
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
