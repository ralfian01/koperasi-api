<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'name', // -- DITAMBAHKAN --
        'variant_id',
        'session_id',
        'booking_id',
        'service_stock_id',
        'resource_id',
        'price_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];
}
