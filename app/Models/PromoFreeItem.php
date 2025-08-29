<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoFreeItem extends Model
{
    protected $table = 'promo_free_items';
    protected $fillable = ['promo_id', 'product_id', 'quantity'];
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
