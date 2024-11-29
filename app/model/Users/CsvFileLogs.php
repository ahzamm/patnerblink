<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CsvFileLogs extends Model
{
    //


    protected $table = "csv_file_logs";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = [];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
	
}
