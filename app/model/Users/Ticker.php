<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Ticker extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "tickers";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','english_content','english_content','announcement_content','creation_by'
    ];
    

   
}
