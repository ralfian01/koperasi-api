<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceAvailability extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * @var string
     */
    protected $table = 'resource_availability';

    /**
     * Primary key dari tabel.
     * @var string
     */
    protected $primaryKey = 'availability_id';

    /**
     * Kolom yang bisa diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'resource_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    /**
     * Mendefinisikan relasi bahwa jadwal ini milik satu resource.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'resource_id');
    }
}
