<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DealerFUP extends Model
{

    protected $table = "fup_data_limit";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'dealerid','resellerid','manager_id','groupname','datalimit'

    ];

     public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
    ];

    // 
    public function profile(){
        return $this->hasOne(Profile::class,'groupname','groupname'); // model,f_k,p_k
    }
}
