<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\Marketing;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\Auth;
use Session;
use Str;
use App\MyFunctions;
use Illuminate\Support\Facades\DB;

class PTAMarkingController extends Controller
{

    public function index()
    {
        ///////////////////// check access ///////////////////////////////////
        // if(!MyFunctions::check_access('Commercials',Auth::user()->id)){
        //     abort(404);
        // }
        return view('users.PTA_marking.index');
    }
    //
    public function upload_action(Request $request){

        $validator = $request->validate([
            'file' => 'required|mimes:csv,txt'],
            ['file.required' => 'Please Upload a CSV File..',
            'file.mimes' => 'Only Required CSV File.. ']);
        // 
        $get_city = userInfo::where('status','dealer')
        ->first('city');
        $_SESSION["message"] = NULL;
        $_SESSION["uploadError"] = NULL;
        if(isset($_FILES['file'])){
            $errors= [];
            $file_name = $_FILES['file']['name'];
            $handle = fopen($_FILES['file']['tmp_name'],"r");
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if($ext == 'csv'){
                $data = fgetcsv($handle);
                $index = 0;
                $allData = [];
                $usernames = [];
                if(count($data) == "2"){

                    while ($fileop = fgetcsv($handle)) {

                        $allData[$index] = $fileop;
                        $index++;

                        $username = $fileop[0];
                        $date = $fileop[1];
                            //
                        array_push($usernames, $username);
                        $info = DB::table('pta_reported_check_date')->where('username',$username)->count();

                        if($info > 0) {
                            $message = "Duplicate user Kindly check line no. ".($index+1);
                            return back()->with('error',$message);
                        }if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $date)){
                         $message = "Invalid date format Kindly check line no. ".($index+1);
                         return back()->with('error',$message);
                     }

                 }
                        //
                 if (count(array_diff_assoc($usernames, array_unique($usernames))) > 0) {
                    $message = "Duplicate users in sheet";
                    return back()->with('error',$message);
                }
                        //////// if no error
                foreach ($allData as $key => $data) {

                 $username = $data[0];
                 $date = $data[1];
                        //
                 DB::table('pta_reported_check_date')->insert(['username' => $username, 'date' => $date]);
                        //
                 DB::table('user_info')->where('username',$username)->update(['pta_reported' => 1]);

             }
             return back()->with('success','Marked Successfully');

         } else{
            $message = 'Invalid columns';
            return back()->with('error',$message);
        }

    } else{
        $message = 'Invalid file format.Please upload only CSV file format.';
        return back()->with('error',$message);
    }
}


}

}
