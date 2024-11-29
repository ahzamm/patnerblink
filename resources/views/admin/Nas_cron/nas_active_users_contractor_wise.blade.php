<?php
$nas_data = DB::table('assigned_nas')->distinct()->get('nas');      
$nas_dealer = DB::table('assigned_nas')->distinct()->get('id');     
$nascount = DB::table('assigned_nas')->distinct()->count('nas');
$dealercount = DB::table('user_info')
->join('assigned_nas', 'assigned_nas.id', '=', 'user_info.username')                                                                                                
->where('user_info.status','=','dealer')
->where('user_info.account_disabled','=','0')
->count();      
$dealerCollection  = DB::table('user_info')
->join('assigned_nas', 'assigned_nas.id', '=', 'user_info.username')                                                                                                
->where('user_info.status','=','dealer')
->where('user_info.account_disabled','=','0')
->get();        
$activeUser = DB::table('user_info')
->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
->where('user_status_info.card_expire_on', '>', DATE('Y-m-d H:i:s')  )//DATE('Y-m-d H:i:s'))                    
->where('user_info.status','=','user')
->count();    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
</head>
<body style="font-family: Arial, sans-serif;
margin: 0;
padding: 0;
background-color: #f4f4f4;
display: flex;
justify-content: center;
align-items: center;
height: auto;">
<div class="container" style="width: 100%;
background-color: #fff;                              
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);padding:10px">
<div class="header" style="text-align: center;

border-radius: 5px;
box-shadow: 1px 1px 3px #333;
height: 150px;
position: relative;">
<img class="logo" src="https://manager.logon.com.pk/img/login-Logo.png" alt="Company Logo" style="max-width: 150px;
height: auto;
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);">
</div>
<center>
    <h2>NAS Wise Active User Data</h2>
</center>
<div class="content" style="line-height: 1.6;color: #333;">      
    <!-- NAS Record -->                            
    <style type="css">
        .tbl_nas tr td { text-align: left !important;}
    </style>
    <p style="font-weight:bold;font-size:16px">Summary | As on: {{date('D, d M Y - h:i a')}}</p>
    <div style="display:flex;">
        <table class="tbl_nas">                            
            <tbody>
                <tr>
                    <th style="text-align: left !important;white-space:nowrap;padding-right:20px">Total NAS's:</th>
                    <td style="text-align: right !important;padding-right:20px">{{$nascount}}</td>   
                </tr>
                <tr>
                    <th style="text-align: left !important;white-space:nowrap;padding-right:20px">Total Contractors:</th>    
                    <td style="text-align: right !important;padding-right:20px">{{$dealercount}}</td>                                    
                </tr>    
                <tr>    
                    <th style="text-align: left !important;white-space:nowrap;padding-right:20px">Total Consumers:</th>
                    <td style="text-align: right !important;padding-right:20px">{{$activeUser}}</td>                                    
                </tr>
            </tbody>
        </table>
        <table class="tbl_nas" style="width:100%">                            
            <tbody>
                <tr style="border-bottom: 1pt solid black;"><td colspan="8" style="text-align: center !important;"><h3>Nas Wise Consumers</h3></td></tr>
                <tr>
                    <th>Nas Name</th>
                    <th>Consumer</th>
                    <th>Nas Name</th>
                    <th>Consumer</th>
                    <th>Nas Name</th>
                    <th>Consumer</th>
                    <th>Nas Name</th>
                    <th>Consumer</th>
                </tr>
                <tr  style="border-bottom: 1pt solid black;">
<?php  //DATE('Y-m-d H:i:s'))                                  
foreach($nas_data as $cont => $nas){
    $nasc = 0;
    $nas_dealer  =  DB::table('user_info')
    ->join('assigned_nas', 'assigned_nas.id', '=', 'user_info.username')                                                                                                
    ->where('user_info.status','=','dealer')
    ->where('user_info.account_disabled','=','0')
    ->where('assigned_nas.nas','=',$nas->nas)
    ->get();                                        
    foreach($nas_dealer as $key => $nas_cons){                                                                        
        $nas_userCount  = DB::table('user_info')
        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
->where('user_status_info.card_expire_on', '>', DATE('Y-m-d H:i:s') )//DATE('Y-m-d H:i:s'))                 
->where('user_info.status','=','user')
->where('user_info.dealerid',$nas_cons->username)                                                        
->count();
$nasc = $nasc+$nas_userCount;
}                                                
?>
<td style="text-align: left !important;">NAS-{{$cont+1}} <label>{{$nas->nas}} </label></td>
<th style="text-align: center !important;">{{$nasc}}</th>                                    
<?php } ?>
</tr>
</tbody>
</table> 
</div>
<center><h3 style="margin-top: 20px">Contractor Wise Active Users</h3></center>
<hr> 
<div style="display: flex; align-items:flex-start; justify-content:center; gap: 5px">                                                               
    @foreach($nas_data as $key => $nas)                            
    <div class="col-md-3">
        <center><label><strong>{{$nas->nas}} (NAS-{{$key+1}})</strong></label></center>                                                                                                                                                  
        <table style="padding:5px;min-width:330px">
            <thead style="border: 1px solid black;">
                <tr style="background-color: #0d4dab;color:#fff">
                    <th>#</th>
                    <th>Cont ID</th>
                    <th>Contractor</th>                                                                                        
                    <th>Consumers</th>                                            
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">                                                    
                <?php $dealerCollection  = DB::table('user_info')
                ->join('assigned_nas', 'assigned_nas.id', '=', 'user_info.username')                                                                                                
                ->where('user_info.status','=','dealer')
                ->where('user_info.account_disabled','=','0')
                ->where('assigned_nas.nas','=',$nas->nas)
                ->get();
                ?>
                @foreach($dealerCollection as $key => $data)
                <?php
// Active Consumers Against Contractor
                $userCount  = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.card_expire_on', '>', DATE('Y-m-d H:i:s') )
                ->where('user_info.dealerid',$data->username)
                ->where('user_info.status','=','user')
                ->count();
                ?>
                <tr style="border: 1px solid gray;">
                    <td style="padding-left:3px;">{{$key+1}}</td>
                    <td style="text-align:Left;padding-left:3px;"><b>{{$data->dealerid}}</b></td>
                    <td style="text-align:Left;padding-left:3px;">{{$data->firstname}}</td>                                                                                                
                    <td style="text-align:right;padding-right:3px;">{{$userCount}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>                                            
    </div>
    @endforeach    
</div>
<!-- NAS Record -->    
<p></p>
<p>If you have query, please contact our Support Team at
info@squadcloud.co or (021)3123456.</p>
<p>Best regards,<br>Logon</p>      
</div>
<div class="footer" style="margin-top: 20px;
text-align: center;
color: #777;
font-size: 0.7rem;">
<p>This is an automated email. Please do not reply to this email.</p>
</div>
</div>
</body>
</html>