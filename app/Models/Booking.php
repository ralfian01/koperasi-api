<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';

    /**
     * KUNCI PERBAIKAN: Menambahkan kolom pax_quantity.
     * @var array
     */
    protected $fillable = [
        'transaction_item_id',
        'resource_id',
        'start_datetime',
        'end_datetime',
        'pax_quantity', // -- DITAMBAHKAN --
        'status'
    ];
}
