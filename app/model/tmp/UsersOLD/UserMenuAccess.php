<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;

class UserMenuAccess extends Model
{
    protected $fillable = [
        'user_id','sub_menu_id','status'
    ];

    public function submenu()
    {
        return $this->belongsTo('App\model\Users\SubMenu','sub_menu_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    } 
}
