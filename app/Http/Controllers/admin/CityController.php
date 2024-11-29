<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\model\Users\City;
use Illuminate\Http\Request;
use Validator;



class CityController extends Controller
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
     $city_data = City::all();
     $cities_data = City::get('city_name')->toArray();

     
     return view('admin.City.view_city',compact('city_data'));
     
 }
 
 
public function store(Request $request)
 {
    $validator = Validator::make($request->all(),['city_name' => 'required|unique:cities']);
    if($validator->passes())
    {
     $city_data = new City();
     $city_data->city_name = $request->get('city_name');
     $city_data->save();

     return response()->json(['success'=>'City has been Successfully Added']);
 }
 return response()->json(['error'=>$validator->errors()->all()]);
}


public function deletethis(Request $request)
{
   $id =$request->get('id');
   City::find($id);
   City::where(['id' => $id])->delete();
}


}
