<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Nas extends Model
{


    protected $connection = 'mysql1';
    protected $table = "nas";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nasname', 'server', 'shortname', 'type', 'ports', 'secret', 'community', 'description', 'addedbyip'

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
