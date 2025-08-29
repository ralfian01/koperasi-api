<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class ProfileModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'tpr_createdAt';
    const UPDATED_AT = 'tpr_updatedAt';

    protected $primaryKey = 'tpr_id';
    protected $table = 'profile';
    protected $fillable = [
        'tpr_name',
        'tpr_nip',
        'tpr_digitalSignature',
        'tpr_digitalInitials',
        'tpr_phoneNumber',
        'tp_id',
        'ta_id',
    ];
    protected $hidden = [
        'tpr_createdAt',
        'tpr_updatedAt',
    ];

    /**
     * Relation with table account
     */
    public function account()
    {
        return $this->belongsTo(AccountModel::class, 'ta_id');
    }

    /**
     * Relation with table position
     */
    public function position()
    {
        return $this->belongsTo(PositionModel::class, 'tp_id');
    }

    /**
     * Get account with its privileges
     */
    protected function scopeGetWithPrivileges(Builder $query)
    {
        return $query
            ->with(['accountPrivilege', 'accountRole.rolePrivilege'])
            ->addSelect(['tpr_id', 'tr_id'])
            ->get()
            ->map(function ($acc) {

                $acc->makeHidden(['accountPrivilege', 'accountRole']);

                if (isset($acc->accountPrivilege)) {
                    $acc->privileges = $acc->accountPrivilege->map(function ($prv) {
                        return $prv->tp_code;
                    })->toArray();
                }

                if (isset($acc->accountRole->rolePrivilege)) {
                    $acc->privileges = array_unique(
                        $acc->accountRole->rolePrivilege->map(function ($prv) {
                            return $prv->tp_code;
                        })->toArray()
                    );
                }

                return $acc;
            });
    }
}
