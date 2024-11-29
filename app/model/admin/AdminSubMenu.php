<?php

namespace App\model\admin;

use Illuminate\Database\Eloquent\Model;

class AdminSubMenu extends Model
{    
    protected $table = "admin_sub_menus";
    protected $fillable = [
        'submenu','menu_id','route_name','param','paramvalue','priority'
    ];
    public function menu()
    {
        return $this->belongsTo('App\model\Admin\AdminMenu');
    }
    public function adminmenu_accesses()
    {
        return $this->hasMany('App\model\Admin\adminMenuAccess','sub_menu_id');
    }    
}
