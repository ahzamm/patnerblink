<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NeverExpire extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "never_expire";

}
