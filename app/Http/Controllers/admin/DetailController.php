<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Profile;
use App\model\Users\UserInfo;
use App\model\Users\LoginAudit;

use App\model\Users\Nas;
class DetailController extends Controller
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
        
         
         $userCollection = UserInfo::select('username')->where('status','dealer')->get();  
            	
     return view('admin.users.login_details',['userCollection' => $userCollection]);
            
            
                
        }

       

       public function loadSubDealer(Request $request){
       $username = $request->username;
        $dealers = UserInfo::where('username',$username)->first();
        $dealerid = $dealers['dealerid'];
        $data = UserInfo::where('dealerid',$dealerid)->where('status','subdealer')->select('username')->get();
         return $data;

    }

      public function usersreport(Request $request){
       
    $date = $request->get('datetimes');
    $username = $request->get('username');
    $subname = $request->get('subname');
        
    $range = explode('-', $date);
    $from1 = $range[0];
    $to1 = $range[1];
    $from1=date('Y-m-d H:i:s' ,strtotime($from1));
    $to1=date('Y-m-d H:i:s' ,strtotime($to1));





    // 
    if($subname =="Select"){
         $export_data  =  LoginAudit::where('username' ,$username)
            ->whereBetween('login_time',[$from1,$to1])->get(); 
             $filename = $username."-UserSheet.csv";
        }else{
             $export_data  =  LoginAudit::where('username' ,$subname)
            ->whereBetween('login_time',[$from1,$to1])->get(); 
            $filename = $subname."-UserSheet.csv";
        }
    
    //
   
 
    // 
    
     


    $delimiter = ",";
    // $filename = "Export.csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('S.No#','Username','Login-Time','IP');
    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
    $num=1;
    foreach ($export_data as $value) {
        // 
       
        // 
        $data1=$num;
        $date2=$value->username;
        $data3=$value->login_time;
        $data4=$value->ip;
        
   

        // 
        $lineData = array($data1,$date2,$data3,$data4);
        fputcsv($f, $lineData, $delimiter);
        // 
        $num++;
    }
    // last row or Summary
    //move back to beginning of file
    fseek($f, 0);
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
    fpassthru($f);
// }
    exit;






// 
// index function end 

    }


    }

   

