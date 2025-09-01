<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // Import Attribute
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


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

    /**
     * KUNCI UNTUK FRONTEND: Accessor untuk mendapatkan URL logo secara otomatis.
     * Ini akan membuat properti virtual `logo_url` pada model.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Jika kolom logo tidak kosong dan filenya ada, kembalikan URL lengkap.
                // Jika tidak, kembalikan null atau URL placeholder.
                if ($this->logo && Storage::disk('public')->exists($this->logo)) {
                    return Storage::disk('public')->url($this->logo);
                }
                return null; // atau 'https://via.placeholder.com/150'
            }
        );
    }
}
