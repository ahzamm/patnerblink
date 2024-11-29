<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class CertificateController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
// View Certificate
     public function index(){
      

     return view('admin.certificate.view');
    }
     public function view(){
      

     return view('admin.certificate.certificate');
    }

        
}
