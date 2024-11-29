<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RadIPpool extends Model
{
    //
    use Notifiable;

    protected $connection = 'mysql1';
    protected $table = "radippool";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pool_name', 'framedipaddress', 'nasipaddress', 'calledstationid', 
        'callingstationid', 'expiry_time', 'username', 'pool_key'

    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
