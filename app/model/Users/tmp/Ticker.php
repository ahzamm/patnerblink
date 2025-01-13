<?php

namespace App\model\Users\Tmp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Ticker extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "ticker";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eng','urdu','last_update'
    ];


}
