<?php

namespace App\model\admin;

use Illuminate\Database\Eloquent\Model;

class AdminMenuAccess extends Model
{
    protected $fillable = [
        'user_id','sub_menu_id','status'
    ];

    public function submenu()
    {
        return $this->belongsTo('App\model\admin\AdminSubMenu','sub_menu_id');
    }

    public function user()
    {
        return $this->belongsTo('App\admin');
    } 
}
