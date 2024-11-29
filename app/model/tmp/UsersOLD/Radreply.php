<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Radreply extends Model
{
    //
    use Notifiable;

    protected $connection = 'mysql1';

    protected $table = "radreply";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'attribute', 'op', 'value', 'dealerid', 'resellerid', 
        'sub_dealer_id'

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
