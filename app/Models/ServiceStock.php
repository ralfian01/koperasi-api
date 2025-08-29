<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceStock extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     */
    protected $table = 'service_stock';

    /**
     * Primary key dari tabel.
     */
    protected $primaryKey = 'stock_id';

    /**
     * Kolom yang bisa diisi secara massal.
     */
    protected $fillable = [
        'product_id',
        'unit_id',
        'name',
        'available_quantity',
    ];

    /**
     * Mendefinisikan relasi bahwa stok ini milik satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * KUNCI PERBAIKAN: Mendefinisikan relasi bahwa stok ini memiliki satu unit.
     * Ini melengkapi mata rantai untuk 'serviceStock.unit'.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
