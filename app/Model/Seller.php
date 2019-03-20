<?php

namespace App\Model;
use App\Model\Product;

class Seller extends User
{
    public function products() {
        return $this->hasMany(Product::class);
    }
}
