<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    use HasFactory;
    protected $fillable = ['business_id', 'name', 'description'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
