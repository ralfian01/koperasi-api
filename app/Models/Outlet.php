<?php

namespace App\Models;

use App\odels\Promo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    /**
     * KUNCI PERBAIKAN: Mendefinisikan kolom yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'business_id',
        'name',
        'contact',
        'address',
        'geolocation',
        'is_active', // Mengganti 'status_active' menjadi 'is_active' sesuai migrasi
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'outlet_employee');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'outlet_product');
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'outlet_promo', 'outlet_id', 'promo_id');
    }
}
