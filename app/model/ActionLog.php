<?php
namespace App\model;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $table = 'action_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'model',
        'beforeupdate',
        'afterupdate',
        'operation',
        'performed_by'
    ];

}
