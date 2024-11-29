<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PartnerThemesUser extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "partner_themes_user";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','color'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   

}
