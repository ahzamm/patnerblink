<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $fillable = [
        'submenu','menu_id','route_name'
    ];
    public function menu()
    {
        return $this->belongsTo('App\model\Users\Menu');
    }
    public function usermenu_accesses()
    {
        return $this->hasMany('App\model\Users\UserMenuAccess','sub_menu_id');
    }
}
