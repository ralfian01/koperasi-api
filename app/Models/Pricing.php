<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricing';
    protected $primaryKey = 'price_id';

    /**
     * KUNCI PERBAIKAN: Menambahkan kolom price_type.
     * @var array
     */
    protected $fillable = [
        'product_id',
        'unit_id',
        'variant_id',
        'name',
        'price',
        'price_type' // -- DITAMBAHKAN --
    ];

    // Relasi-relasi yang sudah ada
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }
}
