<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'type',
        'name',
        'quantity',
        'unit_price',
        'subtotal',
        'product_id',
        'resource_id',
        'variant_id',
        'stock_id',
        'start_datetime',
        'end_datetime'
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
