<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static mixed getWithPrivileges() Get account with its privileges
 * @method mixed getWithPrivileges() Get account with its privileges
 */
class MailSourceModel extends Model
{
    use HasFactory;

    const CREATED_AT = 'tms_createdAt';
    const UPDATED_AT = 'tms_updatedAt';

    protected $primaryKey = 'tms_id';
    protected $table = 'mail_source';
    protected $fillable = [
        'tms_name',
    ];
    protected $hidden = [
        'tms_createdAt',
        'tms_updatedAt',
    ];
}
