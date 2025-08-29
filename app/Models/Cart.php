<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'outlet_id',
        'employee_id',
        'customer_id',
        'member_id',
        'status',
        'subtotal',
        'total_discount',
        'grand_total'
    ];
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
