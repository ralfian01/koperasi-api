<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoSchedule extends Model
{
    protected $fillable = ['promo_id', 'day_of_week', 'start_time', 'end_time'];
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
