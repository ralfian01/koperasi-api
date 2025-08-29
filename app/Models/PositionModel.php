<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class PositionModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'tp_createdAt';
    const UPDATED_AT = 'tp_updatedAt';

    protected $primaryKey = 'tp_id';
    protected $table = 'position';
    protected $fillable = [
        'tp_name',
        'tp_parentId',
        'tp_statusActive',
    ];
    protected $hidden = [
        'tp_createdAt',
        'tp_updatedAt',
    ];

    /**
     * Relation with table position - parent
     */
    public function parent()
    {
        return $this->belongsTo(PositionModel::class, 'tp_parentId', 'tp_id');
    }
}
