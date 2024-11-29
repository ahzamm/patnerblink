<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\CactiGraph;
use App\model\Users\RadAcct;
use Illuminate\Support\Facades\Auth;
use URL;

class GraphController extends Controller
{
   public function index()
   {
      $status = auth()->user()->status;
      $cactiGraphs = CactiGraph::where('user_id', Auth::user()->username)
      ->where('graph_no', '!=', 0)
      ->get();
      $urls = [];

      foreach ($cactiGraphs as $cactiGraph) {
         $id = base64_encode($cactiGraph->graph_no);
         $url = Url::to('').'/users/get_mrtg_graph/' . $id;
         $urls[] = $url;
      }

      return view('users.mrtg-graph.index', compact('urls'));
   }

   public function refresh(Request $request)
   {

      $cactiGraphs = CactiGraph::where('user_id', Auth::user()->username)
      ->where('graph_no', '!=', 0)
      ->get();

      $urls = [];

      foreach ($cactiGraphs as $cactiGraph) {
         $id = base64_encode($cactiGraph->graph_no);
         $url = Url::to('').'/users/get_mrtg_graph/' . $id;
         $urls[] = $url;
      }

      return response()->json(['url' => $urls, 'data' => $cactiGraphs]);
   }
   public function graph_refresh(Request $request)
   {

      $cacti_graph = CactiGraph::where('id', $request->cacti_id)->where('graph_no', '!=', 0)->first();
      $id = base64_encode($cacti_graph['graph_no']);
      $url = Url::to('').'/users/get_mrtg_graph/' . $id;
      return response()->json(['data_cacti' => $cacti_graph, 'url' => $url,'id'=>$id]);
   }

   public function get_mrtg_graph($id)
   {
      $id = base64_decode($id);
      $url = 'https://graph.logon.com.pk/cacti/graph_image.php?local_graph_id=' . $id;
      $image = file_get_contents($url);
      header('Content-type: image/jpeg;');
      header("Content-Length: " . strlen($image));
      echo $image;
   }


   ///////////////////////////////////////////////////////////////

   public function user_data_usage_graph(request $request){
      //
      $username = $request->username;
      //
      $data['uploadDate'] = array(); 
      $data['uploadData'] = array(); 
      //
      $data['downloadDate'] = array(); 
      $data['downloadData'] = array(); 
      //
      $download = RadAcct::select("acctoutputoctets", "acctstarttime")
      ->where("acctstarttime",">=",date("Y-m-01 00:00:00") )
      ->where( "acctstarttime", "<=", date("Y-m-t 00:00:00") )
      ->where(["username" => $username])
      ->get();
      //
      $upload = RadAcct::select( "acctinputoctets", "acctstarttime")
      ->where( "acctstarttime", ">=", date("Y-m-01 00:00:00") )
      ->where( "acctstarttime", "<=", date("Y-m-t 00:00:00") )
      ->where(["username" => $username])
      ->get();
      //
      //
      foreach($download as $key => $entry){
         //
        $usage = ($entry->acctoutputoctets)/1048576;
        $time = date('M,d',strtotime($entry->acctstarttime));

        // $downloadData[$key]['label']=$time;
        // $downloadData[$key]['y']=$data;
        array_push($data['downloadDate'], $time);
        array_push($data['downloadData'], ($usage));
        // array_push($data['downloadData'], 10+$key);
     } 


     // $uploadData = array();

     foreach($upload as $key2 => $entry2){
        $usage2=($entry2->acctinputoctets)/1048576;
        $time2=date('M,d',strtotime($entry2->acctstarttime));

        // $uploadData[$key2]['label']=$time2;
        // $uploadData[$key2]['y']=$data2;
        array_push($data['uploadDate'], $time2);
        array_push($data['uploadData'], ($usage2));
        // array_push($data['uploadData'], 10+$key2);
     }
     //
     return json_encode($data);
     // dd($data);
     //
  }

} 