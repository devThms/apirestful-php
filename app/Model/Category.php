<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Model\Product;

class Category extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $filable = [
        'name',
        'description'
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
