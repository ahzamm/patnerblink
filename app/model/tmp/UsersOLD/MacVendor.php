<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class MacVendor extends Model
{


    protected $table = "mac_vendor";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'oui', 'vendor'

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
