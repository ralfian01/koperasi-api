<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class DispositionModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'td_createdAt';
    const UPDATED_AT = 'td_updatedAt';

    protected $primaryKey = 'td_id';
    protected $table = 'disposition';
    protected $fillable = [
        'td_name',
        'td_nextAction',
        'td_statusActive',
    ];
    protected $hidden = [
        'td_createdAt',
        'td_updatedAt',
    ];
}
