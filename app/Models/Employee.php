<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * KUNCI PERBAIKAN: Mendefinisikan kolom yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'account_id',
        'name',
        'phone_number',
        'is_active', // Mengganti 'status_active' menjadi 'is_active' sesuai migrasi
    ];

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_employee');
    }
}
