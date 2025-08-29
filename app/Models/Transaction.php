<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id',
        'member_id',
        'total_amount',
        'payment_method_id',
        'cash_received',
        'change_due',
        'transaction_date'
    ];
}
