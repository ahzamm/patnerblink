<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\exceedData;



class ExceedDataController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(){
    $exData = exceedData::orderby('data','DESC')->get();
    return view('admin.ExceedData.exceed_data_usage',
    ['exData' => $exData]
    );
}


}
?>