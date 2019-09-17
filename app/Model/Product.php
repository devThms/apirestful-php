<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Category;
use App\Model\Seller;
use App\Model\Transaction;

class Product extends Model
{
    use SoftDeletes;
    
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    public function estaDisponible () {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
