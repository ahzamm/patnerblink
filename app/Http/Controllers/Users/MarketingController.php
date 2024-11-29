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

class MarketingController extends Controller
{

    public function index()
    {
        ///////////////////// check access ///////////////////////////////////
        if(!MyFunctions::check_access('Commercials',Auth::user()->id)){
            abort(404);
        }
        /////////////////////////////////////////////////////
        $get_marketing_data = Marketing::Orderby('id', 'desc')->get();
        return view('users.marketing.index', compact('get_marketing_data'));
    }
    public function add()
    {
        $reseller_data = UserInfo::where('status', 'reseller')->get();
        return view('users.marketing.add', compact('reseller_data'));
    }

    public function store(Request $request)
    {


        $validator = $request->validate([
            'resellerid' => 'required', 'category' => 'required', 'image_data' => 'required'
        ]);
        //
        $short_description = '';
        
        $new_str = str_replace(' ', '', $request['category']);

        if ($request->hasFile('image_data')) {

            $files = $request->file('image_data');
            if ($files->getSize() > 52428800) {
                session()->flash('error', 'File size should not exceed 50 MB.');
                return back();
            }

            if ($new_str === 'TipsandTricks') {
                if (!in_array(strtolower($files->getClientOriginalExtension()), ['pdf'])) {
                    session()->flash('error', 'Invalid File Format. Only PDF is supported');
                    return back();
                }
            } elseif ($new_str === 'PromotionsVideo') {
                if (!in_array(strtolower($files->getClientOriginalExtension()), ['mp4'])) {
                    session()->flash('error', 'Invalid File Format. Only MP4 is supported.');
                    return back();
                }
            } elseif (in_array($new_str, ['Broucher', 'SocialMediaPost', 'Billboard'])) {
                if (!in_array(strtolower($files->getClientOriginalExtension()), ['jpeg', 'jpg', 'png', 'gif'])) {
                    session()->flash('error', 'Invalid File Format. Only JPG, PNG, GIF are supported.');
                    return back();
                }
            } else {
                session()->flash('error', 'Invalid selection or file format.');
                return back();
            }
            //
            $store_marketing = new Marketing();
            $store_marketing->reseller_id = $request['resellerid'];
            $store_marketing->category = $request['category'];
            if (!empty($request['short_description'])) {
                $short_description = $request['short_description'];
            }

            $store_marketing->short_description = $short_description;
            $store_marketing->save();
            //
            $destinationPath = public_path('marketing' . '/' . $request['resellerid'] . '/' . $new_str);
            $profileImage = $request['resellerid'] . '-' . date('d-M-Y-H-i-s') . '-' . Str::random(12) . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $store_marketing->file_name = $profileImage;
            $store_marketing->save();
        }
        session::flash('success', 'Added Successfully.');
        return redirect()->route('users.marketing.index');
        
    }

    public function view()
    {
        $resellerid = Auth::user()->resellerid;

        $show_data = Marketing::where('reseller_id', $resellerid)->where('category', 'Social Media Post')->where('status', 1)->orderBy('id','DESC')->get();
        $show_data_video = Marketing::where('reseller_id', $resellerid)->where('category', 'Promotions Video')->where('status', 1)->orderBy('id','DESC')->get();
        $show_data_brouchers = Marketing::where('reseller_id', $resellerid)->where('category', 'Broucher')->where('status', 1)->orderBy('id','DESC')->get();
        $show_data_bill_board = Marketing::where('reseller_id', $resellerid)->where('category', 'BillBoard')->where('status', 1)->orderBy('id','DESC')->get();
        $show_data_tip = Marketing::where('reseller_id', $resellerid)->where('category', 'Tips and Tricks')->where('status', 1)->orderBy('id','DESC')->get();


        return view('users.marketing.view', compact('show_data', 'show_data_video', 'show_data_brouchers', 'show_data_bill_board', 'show_data_tip'));
    }
    public function show(Request $request)
    {

        $show_market_data = Marketing::find($request->id);
        return $show_market_data;
    }
    

    public function delete(Request $request)
    {
            $delete_marketing = Marketing::find($request->id);
            if ($delete_marketing) {
                $new_str = str_replace(' ', '', $delete_marketing['category']);
                $file_path = public_path('marketing' . '/' . $delete_marketing['reseller_id'] . '/' . $new_str . '/' . $delete_marketing['file_name']);
                //file exist
                if (file_exists($file_path) && !empty($delete_marketing['file_name']) ) {
                    unlink($file_path);
                }else{}

                $delete_marketing->delete();
                return response()->json(['success' => 'Successfully Deleted.']);
            } else {}
   
    }



    public function update(Request $request)
    {

        $id = $request->id;
        $update_status = Marketing::find($id)->update(['status' => $request->status]);
        return response()->json(['success' => 'Update Successfully.']);
    }
}
