<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Menambahkan kolom-kolom baru ke daftar fillable.
     */
    protected $fillable = [
        'business_id',
        'customer_category_id',
        'name',
        'phone_number',
        'email',
        'address'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function category()
    {
        return $this->belongsTo(CustomerCategory::class, 'customer_category_id');
    }
}
