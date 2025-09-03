<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $fillable = ['business_id', 'outlet_id', 'name'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
