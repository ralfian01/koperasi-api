<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCondition extends Model
{
    protected $fillable = ['promo_id', 'condition_type', 'target_id', 'min_quantity'];
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
