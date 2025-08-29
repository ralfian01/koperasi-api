<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class OutcomingMailModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'tom_createdAt';
    const UPDATED_AT = 'tom_updatedAt';

    protected $primaryKey = 'tom_id';
    protected $table = 'outcoming_mail';
    protected $fillable = [
        'tom_mailNumber',
        'tom_mailIntro',
        'tom_mailDescription',
        'tom_initialCoordination',
        'tom_attachmentPath',
        'tim_id',
        'timd_id',
        'tom_createdBy',
    ];
    protected $hidden = [
        'tom_createdAt',
        'tom_updatedAt',
    ];

    /**
     * Relation with table incoming mail disposition
     */
    public function incomingMailDisposition()
    {
        return $this->hasMany(IncomingMailDispositionModel::class, 'tom_id', 'tom_id');
    }
}
