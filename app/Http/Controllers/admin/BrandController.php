<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\model\Users\Brand;
use Illuminate\Http\Request;
use Validator;


class BrandController extends Controller
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
       $brands_data = Brand::all();

     return view('admin.Brands.view_brand',compact('brands_data'));
       
    }
   
         public function store(Request $request)
            {
                // dd($request->all());
                $validator = Validator::make($request->all(),['brand_name' => 'required|unique:brands',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif']);
                if($validator->passes())
                {
                    $files = $request->file('image');
            $destinationPath = public_path('logo/'); // upload path
 // Upload Orginal Image           
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
 
           $insert['image'] = "$profileImage";
                     // $input['image'] = time().'.'.$request->image->extension();
                     // $request->image->move(public_path('images'), $input['image']);
                       $brand_data = new Brand();
                        $brand_data->brand_name = $request->get('brand_name');
                        $brand_data->image = $profileImage;
                        $brand_data->save();

                        return response()->json(['success'=>'Brand has been Successfully Added']);
                }
                        return response()->json(['error'=>$validator->errors()->all()]);
            }
        

  public function deletethis(Request $request)
        {
             $id =$request->get('id');
            
             Brand::find($id);
                Brand::where(['id' => $id])->delete();
        }
    
}
