<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'member_code',
        'name',
        'is_active',
    ];

    /**
     * Relasi ke Transactions (jika masih diperlukan).
     * Saat member dihapus, member_id di transaksi akan menjadi NULL.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id', 'id');
    }
}
