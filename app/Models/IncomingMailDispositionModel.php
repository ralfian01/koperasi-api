<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class IncomingMailDispositionModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'timd_createdAt';
    const UPDATED_AT = 'timd_updatedAt';

    protected $primaryKey = 'timd_id';
    protected $table = 'incoming_mail__disposition';
    protected $fillable = [
        'timd_classification',
        'timd_content',
        'timd_note',
        'timd_digitalSignBy',
        'timd_digitalSignStatus',
        'timd_digitalSignDate',
        'tp_id',
        'td_id',
        'tim_id',
        'timd_dispositionFrom',
        'timd_dispositionTo',
    ];
    protected $hidden = [
        'timd_createdAt',
        'timd_updatedAt',
    ];

    /**
     * Relation with table disposition
     */
    public function disposition()
    {
        return $this->belongsTo(DispositionModel::class, 'td_id');
    }

    /**
     * Relation with table incoming mail
     */
    public function incomingMail()
    {
        return $this->belongsTo(IncomingMailModel::class, 'tim_id');
    }

    /**
     * Relation with table position
     */
    public function dispositionFrom()
    {
        return $this->belongsTo(PositionModel::class, 'timd_dispositionFrom', 'tp_id');
    }

    /**
     * Relation with table position
     */
    public function dispositionTo()
    {
        return $this->belongsTo(PositionModel::class, 'timd_dispositionTo', 'tp_id');
    }

    /**
     * Relation with table profile
     */
    public function profile()
    {
        return $this->belongsTo(ProfileModel::class, 'timd_digitalSignBy', 'tpr_id');
    }
}
