<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Domain extends Authenticatable
{
    //
    use Notifiable;

    public $timestamps = false;
    protected $table = "domain";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip', 'domainname','resellerid','manager_id','package_name','logo','slogan','powerBy','main_heading','bg_image','theme_color'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
}
