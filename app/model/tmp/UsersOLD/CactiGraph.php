<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;



class CactiGraph extends Model
{
    //
    

    protected $table = "cacti_graph";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'graph_no'

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
