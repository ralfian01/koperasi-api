<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class IncomingMailModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'tim_createdAt';
    const UPDATED_AT = 'tim_updatedAt';

    protected $primaryKey = 'tim_id';
    protected $table = 'incoming_mail';
    protected $fillable = [
        'tim_mailNumber',
        'tim_agendaNumber',
        'tim_mailFrom',
        'tim_mailMaterial',
        'tim_receiveDate',
        'tim_mailDate',
        'tim_createdBy',
        'tim_attachmentPath',
        'tim_statusDeletable',
    ];
    protected $hidden = [
        'tim_createdAt',
        'tim_updatedAt',
    ];

    /**
     * Relation with table incoming mail disposition
     */
    public function incomingMailDisposition()
    {
        return $this->hasMany(IncomingMailDispositionModel::class, 'tim_id', 'tim_id');
    }
}
