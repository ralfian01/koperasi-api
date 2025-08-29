<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    /**
     * KUNCI PERBAIKAN: Menambahkan foreign key baru ke daftar fillable.
     * @var array
     */
    protected $fillable = [
        'business_id',      // -- DITAMBAHKAN --
        'category_id',      // -- DITAMBAHKAN --
        'name',
        'description',
        'product_type',
        'booking_mechanism'
    ];

    // Relasi-relasi yang sudah ada
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(
            Outlet::class,
            'outlet_product', // Nama tabel pivot
            'product_id',     // Foreign key dari model INI (Product) di tabel pivot
            'outlet_id'       // Foreign key dari model LAIN (Outlet) di tabel pivot
        );
    }


    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'product_id', 'product_id');
    }

    public function pricing(): HasMany
    {
        return $this->hasMany(Pricing::class, 'product_id', 'product_id');
    }

    public function serviceStock(): HasMany
    {
        return $this->hasMany(ServiceStock::class, 'product_id', 'product_id');
    }
}
