<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    // Nama tabel bisa dispesifikasikan jika tidak mengikuti konvensi
    protected $table = 'business';

    /**
     * KUNCI PERBAIKAN: Mendefinisikan kolom yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'logo',
        'name',
        'email',
        'contact',
        'description',
        'website',
        'instagram',
        'tiktok',
        'is_active', // Mengganti 'status_active' menjadi 'is_active' sesuai migrasi
    ];

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
