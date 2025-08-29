<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $primaryKey = 'resource_id';

    protected $fillable = ['product_id', 'name', 'capacity'];

    /**
     * Sebuah resource/aset dimiliki oleh satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Sebuah resource/aset memiliki banyak jadwal ketersediaan.
     */
    public function availability(): HasMany
    {
        return $this->hasMany(ResourceAvailability::class, 'resource_id', 'resource_id');
    }
}
