<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Ticker;
use Illuminate\Support\Facades\DB;


class TickerController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {}
public function loadTicker(){
    $data = array();
    $ticker = Ticker::select('eng','urdu','last_update')->first();
    $engLen = strlen($ticker['eng']);
    $urduLen = strlen($ticker['urdu']);

    $data['eng'] = $ticker['eng'];
    $data['urdu'] = $ticker['urdu'];
    $data['englength'] = $engLen;
    $data['urlength'] = $urduLen;

    echo json_encode($data);
}
}
?>