<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\exceedData;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\Http;


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
    // $exData = exceedData::orderby('data','DESC')->where('datetime','>=',date('y-m-01 12:00:00'))->where('datetime','<=',date('y-m-t 12:00:00'))->get();
    // return view('users.ExceedData.exceed_data_usage',
    // ['exData' => $exData]
    // );

// $response = Http::withHeaders([
//     'x-api-key' => '1eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkxvZ29uXzg2NTczIiwiaWF0IjoxNzEwNzcyNDk2fQ.xwmnPk5TBUbhZz6K0cyALrKMs1uAV4zwg6qh_WJ-8c0',
// ])->get('https://api.eocean.net/sms/v2/message-logs-by-date/2024-03-18?sortBy=asc&sort=created_at&pageNumber=1&records=11');

//     dd($response);
}


}
?>