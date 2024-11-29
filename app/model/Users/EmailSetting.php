<?php
namespace App\model\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class EmailSetting extends Model {

    protected $fillable = ['resellerid' , 'logo' , 'title' , 'port' , 'email'  , 'password' , 'host' , 'encryption'];


}