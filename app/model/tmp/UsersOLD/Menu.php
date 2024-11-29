<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'menu','has_submenu','icon'
    ];
    public function subMenus()
    {
        return $this->hasMany('App\model\Users\SubMenu');
    }
}
