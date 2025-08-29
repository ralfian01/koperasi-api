<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'variant_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'stock_quantity',
    ];

    /**
     * Get the product that owns the variant.
     * Mendefinisikan relasi bahwa setiap varian "milik" satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get the pricing information associated with the variant.
     * Mendefinisikan relasi bahwa setiap varian memiliki satu data harga.
     */
    public function price(): HasOne
    {
        return $this->hasOne(Pricing::class, 'variant_id', 'variant_id');
    }
}
