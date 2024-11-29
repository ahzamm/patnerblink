<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class RaduserGroup extends Model
{
    //
    use Notifiable;

    protected $connection = 'mysql1';
    protected $table = "radusergroup";
    public $primaryKey = 'username';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'groupname', 'priority'

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
