<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class StaticIPServer extends Model
{
    //
    use Notifiable;

    protected $table = "static_ips_server";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gid', 'serverip', 'ipaddress', 'type', 'status', 'dealerid', 'resellerid',
        'userid', 'added_by', 'added_by_ip', 'date_added','bras'

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
