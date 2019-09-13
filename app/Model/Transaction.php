<?php

namespace App\Model;
use App\Model\Buyer;
use App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];

    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
