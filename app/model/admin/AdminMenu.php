<?php

namespace App\model\admin;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = "admin_menus";
    protected $fillable = [
        'menu','has_submenu','icon'
    ];
    public function subMenus()
    {
        return $this->hasMany('App\model\Admin\AdminSubMenu');
    }
}
