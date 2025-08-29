<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'promo_type',
        'start_date',
        'end_date',
        'is_active',
        'is_cumulative',
        'discount_type',
        'discount_value',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_promo');
    }
    public function schedules()
    {
        return $this->hasMany(PromoSchedule::class);
    }
    public function conditions()
    {
        return $this->hasMany(PromoCondition::class);
    }
    public function freeItems()
    {
        return $this->hasMany(PromoFreeItem::class);
    }
}
