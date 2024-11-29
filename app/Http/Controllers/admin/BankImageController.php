<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\model\Users\BankImage; // Banks_image
use Illuminate\Http\Request;
use Validator;


class BankImageController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
// View All Banks Image
     public function index(){
       $banks_image_data = BankImage::all();

     return view('admin.bank-image.view',compact('banks_image_data'));
    }
  // Add New Bank Images
         public function store(Request $request)
            {
                $validator = Validator::make($request->all(),['bank_name' => 'required|unique:banks_image',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif']);
                if($validator->passes())
                {
              $files = $request->file('image');
              $destinationPath = public_path('images/bank-images');
              $profileImage = $request->get('bank_name') . "." . $files->getClientOriginalExtension();
              $files->move($destinationPath, $profileImage);
           $insert['image'] = "$profileImage";
                       $brand_data = new BankImage();
                        $brand_data->bank_name = $request->get('bank_name');
                        $brand_data->image = $profileImage;
                        $brand_data->save();
                        return response()->json(['success'=>'Bank Images has been Successfully Added']);
                }
                        return response()->json(['error'=>$validator->errors()->all()]);
            }
// Destroy Bank Image
  public function destroy(Request $request)
        {
             $id =$request->get('id');
             BankImage::find($id);
             BankImage::where(['id' => $id])->delete();
             return response()->json(['success'=>'Data has been Deleted Successfully']);
        }
        public function update(Request $request)
        {
     
          $status = $request->get('status');
          $id = $request->get('id');
          BankImage::find($id)->update(['status'=>$status]);
          if($status == 1){
          return response()->json(['success'=>'Bank Images status has been Active Successfully']);
          }
          return response()->json(['success'=>'Bank Images status has been De-Active Successfully']);
        }
    
}
