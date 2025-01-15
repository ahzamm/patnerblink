<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\model\ActionLog;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\admin\DeleteOldConsumerController;
use App\Http\Controllers\admin\RecreateUserController;
use App\model\admin\AdminMenu;


try {
 DB::connection()->getPdo();

 if(DB::connection()->getDatabaseName()){
      //  echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
 }else{
      //  die("Could not find the database. Please check your configuration.");
 }
} catch (\Exception $e) {
 echo '<!doctype html>
 <html lang="en">
 <head>
 <title>Title</title>
 <!-- Required meta tags -->
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
 <link rel="stylesheet" href="https://unpkg.com/nes.css@1.0.0/css/nes.min.css">
 <style>
 html, body {
   width: 100%;
   height: 100%;
 }

 body {
   background-color: #26292d;
 }

 .container {
   width: 100%;
   height: 100%;

   padding-right: 15px;
   padding-left: 15px;
   margin-right: auto;
   margin-left: auto;

   display: flex;
   flex-direction: column;
   justify-content: center;
 }

 .title {
   text-align: center;
 }

 .white-font {
   color: #fff;
 }

 @media (min-width: 576px) {
   .container {
     max-width: 540px;
   }
 }

 @media (min-width: 768px) {
   .container {
     max-width: 720px;
   }
 }

 @media (min-width: 992px) {
   .container {
     max-width: 960px;
   }
 }

 @media (min-width: 1200px) {
   .container {
     max-width: 1140px;
   }
 }

 .text-justify {
   text-align: justify;
 }

 .copyright {
   text-align: center;
   margin-top: 1rem;
 }
 </style>
 </head>
 <body>
 <div class="container">
 <h1 class="title white-font">PARTNER LOGON</h1>
 <div class="nes-container is-dark with-title is-centered">
 <p class="title">503</p>
 <p>Service update</p>
 <p class="text-justify">The server is currently unavailable (because it is overloaded or down for maintenance). Please wait and try again later.</p>
 </div>
 <p class="copyright white-font">© Copyright logon. All Rights Reserved</p>
 </div>
 <!-- Optional JavaScript -->
 <!-- jQuery first, then Popper.js, then Bootstrap JS -->

 <script src="https://fonts.googleapis.com/css?family=Press+Start+2P" ></script>

 </body>
 </html>';
 die();
   // die("Could not open connection to database server.  Please check your Billing configuration.");
}
try {
 DB::connection('mysql1')->getPdo();
 if(DB::connection('mysql1')->getDatabaseName()){
      //  echo "Yes! Successfully connected to the DB: " . DB::connection('mysql1')->getDatabaseName();
 }else{
      //  die("Could not find the database. Please check your configuration.");
 }
} catch (\Exception $e) {

  echo '<!doctype html>
  <html lang="en">
  <head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
  <link rel="stylesheet" href="https://unpkg.com/nes.css@1.0.0/css/nes.min.css">
  <style>
  html, body {
   width: 100%;
   height: 100%;
 }

 body {
   background-color: #26292d;
 }

 .container {
   width: 100%;
   height: 100%;

   padding-right: 15px;
   padding-left: 15px;
   margin-right: auto;
   margin-left: auto;

   display: flex;
   flex-direction: column;
   justify-content: center;
 }

 .title {
   text-align: center;
 }

 .white-font {
   color: #fff;
 }

 @media (min-width: 576px) {
   .container {
     max-width: 540px;
   }
 }

 @media (min-width: 768px) {
   .container {
     max-width: 720px;
   }
 }

 @media (min-width: 992px) {
   .container {
     max-width: 960px;
   }
 }

 @media (min-width: 1200px) {
   .container {
     max-width: 1140px;
   }
 }

 .text-justify {
   text-align: justify;
 }

 .copyright {
   text-align: center;
   margin-top: 1rem;
 }
 </style>
 </head>
 <body>
 <div class="container">
 <h1 class="title white-font">PARTNER LOGON</h1>
 <div class="nes-container is-dark with-title is-centered">
 <p class="title">503</p>
 <p>Service update</p>
 <p class="text-justify">The server is currently unavailable (because it is overloaded or down for maintenance). Please wait and try again later.</p>
 </div>
 <p class="copyright white-font">© Copyright logon. All Rights Reserved</p>
 </div>
 <!-- Optional JavaScript -->
 <!-- jQuery first, then Popper.js, then Bootstrap JS -->

 <script src="https://fonts.googleapis.com/css?family=Press+Start+2P" ></script>

 </body>
 </html>';
 die();
   // die("Could not open connection to database server.  Please check your Radius configuration.");
}
//

Route::get('/phpartisanobserver', function () {
    // Replace 'GlobalModelObserver' with your desired observer name
    Artisan::call('make:observer GlobalModelObserver');
    return 'Observer created successfully!';
 });

//error Routes
Route::get('/view-clear', function() {
  $CatchallError = Artisan::call('view:clear');
  return '<h4>View cache cleared</h4>';
});

Route::get('/clear-cache', 'CacheController@clearAll');

//
Route::get('/401', function () {
 return view('users.errors.401');
});
Route::get('/myScript', function(){

  $userList = App\model\Users\UserInfo::select('id','username')->where('status','user')->where('dealerid','logonhome')->get();
  $out = 'Done - ';
  foreach($userList as $user){
    $username = $user->username;
    $radCheckuser =  App\model\Users\UserInfo::where(['username' => $username])->first();
    if($radCheckuser){
//		 $out .= $user->id . ' -> '.$user->username . ' >> ' .$user->password . '-> ' . $radCheckuser->value . ' -> '.Illuminate\Support\Facades\Hash::make(''.$radCheckuser->value);
		  //$user->password = Illuminate\Support\Facades\Hash::make(''.$radCheckuser->value);
		  //$user->update();
//		  if($radCheckuser){
      $x = App\model\Users\UserInfo::find($user->id);
      $x->password = Illuminate\Support\Facades\Hash::make(''.$radCheckuser->username);
      $x->save();
//			  		  $out .= ' => '. $x->password  . ' <br/>';
    }else{
//			  $out .= "" . $username . '<br/>';
    }
  }
  return $out;
});


////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////  NAS CRON for EMAIL ROUTES   //////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

Route::get('admin/Nas-Active-Users-Contractor-Wise', 'admin\NasController@nas_active_users_mail')->name('nas.active.users.mail');

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// NEVER EXPIRE CRON JOB ROUTE
////////////////////////////////////////////////////////////////////////////////////////
Route::get('/get_never_expire_consumers','Users\NeverExpireController@get_never_expire_consumers')->name('users.get_never_expire_consumers');

////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////  ALL API ROUTES   //////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

Route::get('api/userinfo','api\ApiController@index');
Route::get('api/brands','api\ApiController@brands');
Route::get('api/city','api\ApiController@cities');
Route::get('api/invalidlogin','api\ApiController@invalid_login');
Route::get('api/disabledconsumer','api\ApiController@disabled_consumer');
Route::get('api/suspiciousconsumer','api\ApiController@suspicious_consumer');
Route::get('api/onlineconsumer','api\ApiController@online_consumer');
Route::get('api/get_domain','api\ApiController@get_domain');
Route::get('api/get_radius_info','api\RadiusApiController@index');
Route::get('api/get_cgn_ip_detail','api\ApiController@get_cgn_ip_detail');

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////


Route::group(['middleware' => 'guest:user'],function(){
  Route::get('/','Users\Auth\LoginController@showLoginForm')->name('users.home');
});


Route::get('mrtc-manager','MrctController@get_manager')->name('get.manager');
Route::post('mrtc-reseller','MrctController@get_reseller')->name('get.reseller');
Route::post('mrtc-dealer','MrctController@get_dealer')->name('get.dealer');
Route::post('mrtc-trader','MrctController@get_trader')->name('get.trader');
Route::post('profile-list','MrctController@get_profiles')->name('get.profiles');
//
//
Route::group(['prefix'=>'admin'],function(){
  Route::post('graph-single-refresh', 'admin\GraphController@graph_refresh')->name('admin.show_mrtg_graph');
  Route::get('get_mrtg_graph/{id}', 'admin\GraphController@get_mrtg_graph')->name('admin.get_mrtg_graph');

  Route::group(['middleware' => 'guest:admin'],function(){

    Route::get('/','admin\auth\LoginController@showLoginForm');
    Route::get('/login','admin\auth\LoginController@showLoginForm')->name('admin.login.show');
    Route::post('/login','admin\auth\LoginController@login')->name('admin.login.post');
  });
  Route::group(['middleware' => 'auth:admin'],function(){
   Route::get('/users/list','admin\BillingController@loadUser')->name('admin.user.status.usernameList');
   Route::post('/logout', 'admin\auth\LoginController@logout')->name('admin.logout');
   Route::get('/dashboard','admin\DashboardController@index')->name('admin.dashboard');
   //  Certificate
   Route::get('/certificate','admin\CertificateController@index')->name('admin.certificate.view');
   Route::get('/certificate/show','admin\CertificateController@view')->name('admin.certificate.show');

   Route::get('/users/{status}','admin\UserController@index')->name('admin.user.index');
   Route::post('/users/{status}','admin\UserController@store')->name('admin.user.post');
   Route::get('/users/{status}/{id}','admin\UserController@edit')->name('admin.user.edit');
   Route::post('/users/{status}/{id}','admin\UserController@update')->name('admin.user.update');
   Route::get('/user/{status}','admin\UserController@show')->name('admin.user.show');


   //RepiarIds Route
   Route::get('/repair-id','admin\RepairIdController@index')->name('admin.repair.index');
   Route::post('/repair-id','admin\RepairIdController@store')->name('admin.repair.store');
   /// END ///



   /// Migration Module Route ///
   Route::get('/migration','admin\MigrationController@index')->name('admin.migration.index');
   Route::post('/migration','admin\MigrationController@store')->name('admin.migration.store');
   Route::post('/migration/groupname','admin\MigrationController@migrate_groupname')->name('admin.migration.groupname');
   Route::post('/migration/getprofile','admin\MigrationController@getprofile')->name('admin.migration.getprofile');
   Route::get('/get-nas','admin\MigrationController@nas')->name('admin.migration.nas');
   /// END ///

   /// Banks Images ///
   Route::get('/bank/view','admin\BankImageController@index')->name('admin.banks.view');
   Route::post('/bank/store','admin\BankImageController@store')->name('admin.banks.store');
   Route::post('delete/bank','admin\BankImageController@destroy')->name('admin.banks.destroy');
   Route::post('update/bank','admin\BankImageController@update')->name('admin.banks.update');

   /// Menu Management ///
   Route::get('/menu-management','admin\ManagementController@sub_menu_list')->name('admin.Management.menu.management');
   Route::post('/sub-menu-management','admin\ManagementController@sub_menu_update')->name('admin.submenu.update');

//    User Menu Management
   Route::get('/menu','admin\ManagementController@menu')->name('admin.Management.menu');
   Route::post('/menu/update-order', 'admin\ManagementController@updateMenuOrder')->name('admin.menu.updateOrder');
   Route::get('/submenu', 'admin\ManagementController@submenu')->name('admin.Management.submenu');
    Route::post('/submenu/update-order', 'admin\ManagementController@updateUserSubmenuOrder')->name('admin.submenu.updateOrder');
    Route::post('/submenu/fetch', 'admin\ManagementController@getUserSubmenusByMenu')->name('admin.submenu.fetch');


   Route::post('/menu-store','admin\ManagementController@store_menu')->name('admin.Management.menu.store');
   Route::post('/sub-menu','admin\ManagementController@store_submenu')->name('admin.Management.submenu.store');
   Route::post('/edit-menu','admin\ManagementController@edit_menu')->name('admin.Management.menu.edit');
   Route::post('/edit-sub-menu','admin\ManagementController@edit_submenu')->name('admin.Management.submenu.edit');
   Route::post('/update-menu','admin\ManagementController@update_menu')->name('admin.Management.menu.update');
   Route::post('/update-sub-menu','admin\ManagementController@update_submenu')->name('admin.Management.submenu.update');


   ///  Admin Support Route ///

   /// View Help Agent ///
   Route::get('/view/{id}','admin\ManagementController@view')->name('admin.manage.view');
   /// END ///

   /// Taxation Routes ///
   Route::get('/taxation/view','admin\TaxationController@taxation_view')->name('admin.taxation.viewer');
   Route::post('/taxation/update','admin\TaxationController@taxation_update')->name('admin.taxation.update');
   Route::post('/provincial-taxation/update','admin\TaxationController@provincial_taxation_update')->name('admin.provincialtaxation.update');

   /// Brands ///
   Route::get('/brand/view','admin\BrandController@index')->name('admin.brands.view');
   Route::post('/brand/store','admin\BrandController@store')->name('admin.brands.store');
   Route::post('delete/brand','admin\BrandController@deletethis')->name('admin.brands.destroy');


   /// Internet Package Attribute ///
   Route::get('/show/package-attribute','admin\RadGroupReplyController@data')->name('admin.data.rad');
   Route::get('/create/package-attribute','admin\RadGroupReplyController@create')->name('admin.create.rad');
   Route::post('/store/package-attribute','admin\RadGroupReplyController@store')->name('admin.store.rad');
   Route::post('/edit/package-attribute','admin\RadGroupReplyController@edit')->name('admin.edit.rad');
   Route::post('/update/package-attribute','admin\RadGroupReplyController@update')->name('admin.update.rad');

    //    Action Logs Rotues
    Route::get('/action-logs', 'admin\ActionLogController@index')->name('admin.action-logs.index');
    Route::get('/admin/action-logs/data', 'admin\ActionLogController@getActionLogs')->name('admin.action-logs.data');

   /// END ///
   Route::get('/create-action', function() {
    $menu = AdminMenu::orderByDesc('id')->first(); // Replace this with a specific ID if needed

    if (!$menu) {
        return response()->json([
            'success' => false,
            'message' => 'No AdminMenu record found to update.',
        ]);
    }

    // Update the record
    $menu->menu = 'Updated Menu Name'; // Dummy update
    $menu->icon = 'new-icon-class'; // Dummy update
    $menu->save(); // Triggers the "updated" observer

    return response()->json([
        'success' => true,
        'message' => 'AdminMenu record updated successfully.',
    ]);
});
   /// Services ///
   Route::get('service/show', 'admin\ServicesController@index')->name('admin.services');
   Route::post('service/show-list', 'admin\ServicesController@service_list')->name('admin.service_list');
   Route::post('service/add', 'admin\ServicesController@store')->name('admin.services.store');
   Route::post('service/action', 'admin\ServicesController@action')->name('admin.services.action');
   Route::post('service/edit', 'admin\ServicesController@edit')->name('admin.services.edit');
   Route::post('service/update', 'admin\ServicesController@update')->name('admin.services.update');
   Route::post('service/delete', 'admin\ServicesController@delete')->name('admin.services.delete');
   /// END ///

   /// Email ///
   Route::get('email', 'admin\EmailController@index')->name('admin.email');
   /// END ///

   /// City Data ///
   Route::get('/city/show','admin\CityController@index')->name('admin.city.index');
   Route::post('/city/create','admin\CityController@store')->name('admin.store.city');
   Route::post('ajax/city','admin\CityController@deletethis')->name('admin.city.ajax.post');
   /// END ///

   /// Headlines ///
   Route::get('ticker/headline','admin\DashboardController@create_headline')->name('admin.headline');
   Route::post('ticker/headline','admin\DashboardController@store_headline')->name('admin.store.headline');
   /// END ///

   /// Csv Import ///
   Route::get('/csv/upload','admin\CsvController@index')->name('admin.csv.index');
   Route::post('/csv/upload/file','admin\CsvController@upload_file')->name('admin.csv.upload');
   /// END ///

   /// Active User ///
   Route::get('/csv/active','admin\CsvController@expired_user_import')->name('admin.csv.active.import');
   Route::post('/csv/expired-user','admin\CsvController@expired_user')->name('admin.csv.expired.user');
   Route::post('/csv/download-active-user-sheet','admin\CsvController@download_active_user_sheet')->name('admin.csv.download_active_user_sheet');
   /// END ///

   /// Filter data Collect ///
   Route::get('get-manager','admin\CsvController@get_manger')->name('admin.manger');
   Route::post('get-reseler','admin\CsvController@get_reseller')->name('admin.reseler');
   Route::post('get-dealer','admin\CsvController@get_dealer')->name('admin.dealer');
   Route::post('get-trader','admin\CsvController@get_trader')->name('admin.trader');
   /// END ///

   /// Shift Contractors ///
   Route::get('/csv/shift_user','admin\CsvController@shift_user')->name('admin.csv.shift_user');
   Route::post('/csv/shift_user/file','admin\CsvController@shift_user_store')->name('admin.csv.shift_user_store');
   /// END ///

   ////////////// add by Talha from CP on 2023-03-03////////////////
   Route::get('/userpdf','admin\UserReportController@index')->name('admin.users.pdf');
   Route::get('/userform','admin\UserReportController@user_form')->name('admin.users.form');
   Route::get('/getuseragreements','admin\UserReportController@getuseragreements')->name('admin.users.getuseragreements');
   Route::get('/useragreements_view/{id}','admin\UserReportController@getuseragreements_view')->name('admin.users.getuseragreements_view');
   Route::post('/postuserform','admin\UserReportController@user_form_post')->name('admin.postuserform');
   Route::get('/usersagreement/{id}','admin\UserReportController@edit')->name('usersagreement.edit');
   Route::post('/uusersagreementupdate/{id}','admin\UserReportController@update')->name('usersagreement.update');
   Route::get('/agreement-delete/{id}','admin\UserReportController@destroy')->name('usersagreement.delete');
  /////////////////////////////////////////////////////////////////


   Route::get('/contractor_trader_profile','admin\ContTradPorfileController@index')->name('admin.contractor_trader_profile');
   Route::post('/contractor_trader_profile_store','admin\ContTradPorfileController@store')->name('admin.contractor_trader_profile.store');
   Route::post('/contractor_trader_profile_delete','admin\ContTradPorfileController@delete')->name('admin.contractor_trader_profile.delete');


   Route::post('adminSupportsearch','admin\SupportSearchController@index')->name('admin.Supportsearch');
   Route::post('adminsearchResult','admin\SupportSearchController@searchResult')->name('admin.SupportsearchResult');
   Route::post('adminSupportupdownGraph','admin\SupportSearchController@showupDownGraph')->name('admin.SupportupdownGraph');
   Route::post('adminSupportclearMac','admin\SupportSearchController@SupportclearMac')->name('admin.SupportSupportclearMac');
   Route::get('/router','admin\NasController@index')->name('admin.router.index');
   Route::get('/router/{id}','admin\NasController@edit')->name('admin.router.edit');

   Route::post('/router','admin\NasController@store')->name('admin.router.post');
   Route::post('/router/update/{id}','admin\NasController@update')->name('admin.router.update');
   Route::post('ajax/router','admin\NasController@deletethis')->name('admin.router.ajax.post');


   Route::get('/cards','admin\CardController@index')->name('admin.card.index');
   Route::get('/profile','admin\ProfileController@index')->name('admin.profile.index');
   Route::post('/profile','admin\ProfileController@store')->name('admin.profile.post');
   Route::post('ajax/profile','admin\ProfileController@deletethis')->name('admin.profile.ajax.post');
   Route::get('/billing/{status}','admin\BillingController@index')->name('admin.billing.index');
   Route::post('/billing/{status}','admin\BillingController@store')->name('admin.billing.post');
   Route::post('/billing/gen/report','admin\ReportController@generateBillingReport')->name('admin.users.billing.report.generate');

   Route::post('/edit-profile','admin\ProfileController@edit')->name('admin.profile.edit');
   Route::post('/update-profile','admin\ProfileController@update')->name('admin.profile.update');

   Route::get('/report/{status}','admin\ReportController@index')->name('admin.report.index');

   Route::post('/billing/gen/summary','admin\ReportController@billingSummary')->name('admin.users.billing.summary.generate');
   Route::post('/billing/gen/summary/advance','admin\ReportController@advanceSummary')->name('admin.users.billing.summary.advance');

   Route::post('/billing/gen/summary/invoice','admin\ReportController@invoiceSummary')->name('admin.users.billing.summary.invoice');
   Route::post('/billing/billing_with_tax/billingwithtax','admin\ReportController@billing_with_tax')->name('admin.billing.billing_with_tax');
   Route::post('/billing/gen/report','admin\ReportController@reportSummary')->name('admin.users.billing.report.generate');

   Route::get('/customer/{status}','admin\OnlineUserController@index')->name('admin.customer.index');

   // Route::get('/admin/onlinePost','admin\OnlineUserController@onlinePost')->name('admin.user.onlinePost');
   // //online user details
   // Route::post('onlineUserDetails','admin\OnlineUserController@onlineUserDetails')->name('admin.onlineUserDetails');

   // //Offline user Route
   // Route::get('/admin/offlineUserView','admin\OnlineUserController@offlineUserView')->name('admin.offlineUserView');
   // Route::get('/admin/offlinePost','admin\OnlineUserController@offlinePost')->name('admin.user.offlinePost');
   // //offline user details
   // Route::post('offlineUserDetails','admin\OnlineUserController@offlineUserDetails')->name('admin.offlineUserDetails');

   Route::get('viewStatic','admin\ViewStaticController@index')->name('admin.users.view_static_ip');


   Route::get('/approve/{status}','admin\ApproveController@index')->name('admin.approve.index');
   Route::post('ajax/approve','admin\ApproveController@approveRadUserGroup')->name('admin.approve.ajax.post');
   Route::post('/verify','admin\ApproveController@approveVerification')->name('admin.approve.ajax.verify');
   Route::post('/static/approve','admin\ApproveController@approveStaticIPUser')->name('admin.approvestatic.post');
   Route::get('/report/{status}','admin\ReportController@index')->name('admin.report.index');



   Route::post('/password','admin\UpdatePasswordController@index')->name('admin.users.update_password');

   Route::get('admin/downloadsheet/{managerid}/{resellerid}','admin\DownloadSheetController@index')->name('admin.my.sheetdownload');
   Route::get('admin/downloadsheetall/{managerid}/{resellerid}','admin\DownloadSheetController@index_OLD')->name('admin.alluser.sheetdownload');
   Route::post('ajax/search','admin\SearchController@index')->name('admin.search.ajax.post');



   Route::post('ajax/checkavailable','admin\CheckAvailablityController@index')->name('admin.check.available.post');

   Route::post('/dataremove','admin\UserController@dataremove')->name('users.data.remove');


   Route::get('view/management/{status}','admin\ViewSupportController@index')->name('admin.management.support.index');

   Route::post('view/management/{status}','admin\ViewSupportController@store')->name('admin.management.support.post');

   Route::get('/error_log','admin\LoginErrorController@index')->name('admin.users.error_log');


   Route::get('/pdf','admin\PDFController@index')->name('admin.users.myPDF');

   Route::post('/admin/clearMac','admin\UserController@clearMac')->name('admin.users.user_detail');
   Route::get('/admin/DBO_user','admin\ViewSupportController@DBOUser')->name('admin.users.DBO_user');

   Route::post('/billingPDF','admin\LedgerReportController@billingpdf')->name('admin.users.billingReport_PDF');
   Route::get('/admin/adddhcpView','admin\DhcpController@add_DHCPView')->name('admin.adddhcpView');
   Route::post('/admin/adddhcp','admin\DhcpController@add_DHCP_Post')->name('admin.addDHCP');
   Route::get('/admin/dhcpUpdate','admin\DhcpController@DHCP_Post_Update')->name('admin.DHCPUpdate');


   /// MRTG & DHCP SERVER ROUTES ///
   Route::get('/dhcpAssign','admin\Dhcp_Mrtg_Controller@index')->name('admin.dhcpAssign');
   Route::get('/subdealerFetch','admin\Dhcp_Mrtg_Controller@loadSubDealer')->name('admin.loadSubDealers');
   Route::post('/postdhcp','admin\Dhcp_Mrtg_Controller@postData')->name('admin.postdhcpAli');
   /// END ///


   Route::post('/totalconsumer','admin\DashboardDataController@total_consumer')->name('admin.dashboardData.totalConsumer');
   Route::post('/activeconsumer','admin\DashboardDataController@active_consumer')->name('admin.dashboardData.activeConsumer');
   Route::post('/onlineconsumer','admin\DashboardDataController@online_consumer')->name('admin.dashboardData.onlineConsumer');
   Route::post('/disabled_consumer','admin\DashboardDataController@disabled_consumer')->name('admin.dashboardData.disabled_consumer');
   Route::post('/reseller_count','admin\DashboardDataController@reseller_count')->name('admin.dashboardData.reseller_count');
   Route::post('/contractor_count','admin\DashboardDataController@contractor_count')->name('admin.dashboardData.contractor_count');
   Route::post('/trader_count','admin\DashboardDataController@trader_count')->name('admin.dashboardData.trader_count');
   Route::post('/profile_wise_user_count_graph','admin\DashboardDataController@profile_wise_user_count_graph')->name('admin.dashboardData.profile_wise_user_count_graph');
   Route::post('/system_graph','admin\DashboardDataController@system_graph')->name('admin.dashboardData.system_graph');



   Route::get('/addnas',function(){
     return view('admin.users.login_details');
   });


   Route::get('admin/downloadsheet2/{managerid}/{dealerid}','admin\DownloadSheetController@downloadDealer')->name('admin.my.sheetdealerdownload');


   Route::get('/management','admin\ManagementController@index')->name('admin.AccessManagement.viewUsers');
   Route::post('/store','admin\ManagementController@store')->name('admin.AccessManagement.store');
   Route::get('/edit','admin\ManagementController@edit')->name('admin.AccessManagement.edit');
   Route::get('/userUpdate/{id}','admin\ManagementController@update')->name('admin.AccessManagement.userUpdate');
   Route::get('/delete/{id}','admin\ManagementController@delete')->name('admin.AccessManagement.delete');

   Route::get('/allowAccess','admin\ManagementController@allow_Access')->name('admin.AccessManagement.allowAccess');
   Route::post('/userAccess','admin\ManagementController@userAccess')->name('admin.AccessManagement.userAccess');


   /// Exceed Data Route ///
   Route::get('ExceecData','admin\ExceedDataController@index')->name('admin.ExceecData');
   /// END ///
   Route::get('/logindetails','admin\DetailController@index')->name('admin.logindetails');

   Route::get('/loadSubdealer','admin\DetailController@loadSubDealer')->name('admin.loadSubDealer');

   Route::post('/logindetails','admin\DetailController@usersreport')->name('admin.logindetails');


   /// Kick user Route ///
   Route::get('/kickUser','admin\kickUserController@index')->name('admin.kickUser');
   Route::post('/kickit','admin\kickUserController@kickit')->name('admin.kickit');
   /// END ///

   /// CGN IP Route ///
   Route::get('/showcgnView','admin\CgnController@index')->name('admin.showcgnView');
   Route::post('/postcgn','admin\CgnController@addCGN')->name('admin.postcgn');
   /// END ///

   /// Static IPs ///
   Route::get('add-static-ip','admin\CgnController@add_static_ip_data')->name('admin.ips_create');
   Route::post('staticip','admin\CgnController@store_static_ip_data')->name('admin.store_ips_data');
   /// END ///

   /// Cunsumers Bills ///
   Route::get('customerbilling/{username}/{date}','admin\LedgerReportController@customerBill')->name('admin.billing.customer_bill_PDF');
   Route::get('/admin/cgnview/data','admin\CgnController@data')->name('admin.cgnView.data');
   /// END ///

   ///  Billing Panel Maintance Mode ///
   Route::get('maintenance','admin\MaintenanceController@index')->name('maintenance.index');
   Route::post('maintenance/store','admin\MaintenanceController@store')->name('maintenance.store');
   Route::get('maintenance/deactivate','admin\MaintenanceController@deactivate')->name('maintenance.deactivate');
   Route::resource('allowedip','Admin\AllowedIpController');
   /// END ///

   ///  ApprovedNewUserController Code Route ///
   Route::get('/ApprovedNewUser', 'admin\ApprovedNewUserController@index')->name('admin.ApprovedNewUser');
   Route::get('/ApprovedNewUserNotification', 'admin\ApprovedNewUserController@approvedNewUserNotification')->name('admin.ApprovedNewUserNotification');
   Route::post('/approveNewUserPost', 'admin\ApprovedNewUserController@approveNewUserPost')->name('admin.approveNewUserPost');
   Route::post('/rejectNewUserPost', 'admin\ApprovedNewUserController@rejectNewUserPost')->name('admin.rejectNewUserPost');

   /// NEW ADMIN MENU ROUTES added on 2023-12-29
   Route::get('/admin-role/{id}','admin\ManagementController@admin_roles')->name('admin.AdminRoles.index');//
   Route::post('/Admin-menu-management','admin\ManagementController@admin_menu_update')->name('admin.adminsubmenu.update');
//    Admin Menu Management
   Route::get('/admin-menu','admin\ManagementController@admin_menu')->name('admin.AdminRoles.admin-menu');//
   Route::post('/admin/menu/updateOrder', 'admin\ManagementController@updateOrder')->name('admin.Management.adminmenu.updateOrder');
   Route::get('/admin-submenu', 'admin\ManagementController@admin_submenu')->name('admin.AdminRoles.admin-submenu');
   Route::post('/admin/submenu/updateOrder', 'admin\ManagementController@updateSubmenuOrder')->name('admin.Management.adminsubmenu.updateOrder');
   Route::post('/admin/submenu/fetch', 'admin\ManagementController@getSubmenusByMenu')->name('admin.Management.adminsubmenu.fetch');

   Route::post('/Admin-menu-store','admin\ManagementController@store_admin_menu')->name('admin.Management.adminmenu.store');//
   Route::post('/Admin-sub-menu','admin\ManagementController@store_admin_submenu')->name('admin.Management.adminsubmenu.store');
   Route::post('/Admin-edit-menu','admin\ManagementController@edit_admin_menu')->name('admin.Management.adminmenu.edit');
   Route::post('/Admin-edit-sub-menu','admin\ManagementController@edit_admin_submenu')->name('admin.Management.adminsubmenu.edit');
   Route::post('/Admin-update-menu','admin\ManagementController@update_admin_menu')->name('admin.Management.adminmenu.update');//
   Route::post('/Admin-update-sub-menu','admin\ManagementController@update_admin_submenu')->name('admin.Management.adminsubmenu.update');
   //
   // ADMIN USER ENABLE DISABLE
   Route::post('/delete','admin\ManagementController@delete')->name('admin.AccessManagement.delete');
   Route::post('/disable','admin\ManagementController@disable')->name('admin.AccessManagement.disable');
   Route::post('/enable','admin\ManagementController@enable')->name('admin.AccessManagement.enable');

 });

});

/// Users URLs ///
Route::get('/login','Users\Auth\LoginController@showLoginForm')->name('users.login.show');

Route::group(['prefix'=>'users'],function(){
	Route::group(['middleware'=>'guest:user'],function(){
		Route::get('/login','Users\Auth\LoginController@showLoginForm')->name('users.login.show');
		Route::post('/login','Users\Auth\LoginController@login')->name('users.login.post');
	});
	Route::group(['middleware'=>'auth:user'],function(){



    /// Approval ///
    Route::post('/static/approve','Users\ApproveController@approveStaticIPUser')->name('users.approvestatic.post');
    Route::post('/contractor/approve','Users\ApproveController@approveRadUserGroup')->name('users.approve.ajax.post');
    Route::post('/verify','Users\ApproveController@approveVerification')->name('users.approve.ajax.verify');

    /// MRTG $ CACTI ///
    Route::get('mrtg','Users\GraphController@index')->name('user.graph.index');
    Route::post('graph-refresh','Users\GraphController@refresh')->name('user.graph.refresh');
    Route::post('graph-single-refresh', 'Users\GraphController@graph_refresh')->name('user.show_mrtg_graph');
    Route::get('get_mrtg_graph/{id}', 'Users\GraphController@get_mrtg_graph')->name('user.get_mrtg_graph');


   ///Repiar Consumer IDs Route ///
    Route::get('/repair-id','Users\RepairIdController@index')->name('users.repair.index');
    Route::post('/repair-id','Users\RepairIdController@store')->name('users.repair.store');
   /// END ///

    //////// PTA Marking ///////////
    Route::get('pta_marking','Users\PTAMarkingController@index')->name('pta_marking');
    Route::post('pta_marking_upload','Users\PTAMarkingController@upload_action')->name('users.pta_marking_upload');

    Route::post('/bind_unbind_mac','Users\ApproveController@bind_unbind_mac')->name('users.bind_unbind_mac');

   /// Dashboard Data ///
    Route::get('/mytestfunction','Users\DashboardDataController@index')->name('users.hello.test');
    Route::post('/totalconsumer','Users\DashboardDataController@total_consumer')->name('users.dashboardData.totalConsumer');
    Route::post('/activeconsumer','Users\DashboardDataController@active_consumer')->name('users.dashboardData.activeConsumer');
    Route::post('/onlineconsumer','Users\DashboardDataController@online_consumer')->name('users.dashboardData.onlineConsumer');
    Route::post('/upcoming_expiry','Users\DashboardDataController@upcoming_expire_consumer')->name('users.dashboardData.upcoming_expire_consumer');
    Route::post('/verified_mobile','Users\DashboardDataController@verified_mobile')->name('users.dashboardData.verified_mobile');
    Route::post('/verified_cnic','Users\DashboardDataController@verified_cnic')->name('users.dashboardData.verified_cnic');
    Route::post('/invalid_login','Users\DashboardDataController@invalid_login')->name('users.dashboardData.invalid_login');
    Route::post('/reseller_count','Users\DashboardDataController@reseller_count')->name('users.dashboardData.reseller_count');
    Route::post('/contractor_count','Users\DashboardDataController@contractor_count')->name('users.dashboardData.contractor_count');
    Route::post('/trader_count','Users\DashboardDataController@trader_count')->name('users.dashboardData.trader_count');
    Route::post('/subtrader_count','Users\DashboardDataController@subtrader_count')->name('users.dashboardData.subtrader_count');
    Route::post('/disabled_consumer','Users\DashboardDataController@disabled_consumer')->name('users.dashboardData.disabled_consumer');
    Route::post('/offline_consumer','Users\DashboardDataController@offline_consumer')->name('users.dashboardData.offline_consumer');
    Route::post('/expired_consumer','Users\DashboardDataController@expired_consumer')->name('users.dashboardData.expired_consumer');
    Route::post('/new_consumer','Users\DashboardDataController@new_consumer')->name('users.dashboardData.new_consumer');
    Route::post('/suspicious_consumer','Users\DashboardDataController@suspicious_consumer')->name('users.dashboardData.suspicious_consumer');
    Route::post('/profile_wise_user_count_graph','Users\DashboardDataController@profile_wise_user_count_graph')->name('users.dashboardData.profile_wise_user_count_graph');
   //
    Route::post('/user_data_usage_graph','Users\GraphController@user_data_usage_graph')->name('users.user_graph.user_data_usage_graph');
   //
    Route::get('/adddhcpView','Users\DHCPController@add_DHCPView')->name('users.adddhcpView');
    Route::get('/user/dhcpUpdate','Users\DHCPController@DHCP_Post_Update')->name('users.DHCPUpdate');
    Route::post('/users/adddhcp','Users\DHCPController@add_DHCP_Post')->name('users.addDHCP');
    Route::get('/dhcpAssign','Users\Dhcp_Mrtg_Controller@index')->name('users.dhcpAssign');
    Route::post('/postdhcp','Users\Dhcp_Mrtg_Controller@postData')->name('users.postdhcpAli');
    Route::post('/delete_dhcpmrtg','Users\Dhcp_Mrtg_Controller@delete')->name('users.delete.dhcpmrtg');


   /// Kick Consumers Route ///
    Route::get('/kickUser','Users\kickUserController@index')->name('users.kickUser');
    Route::post('/kickit','Users\kickUserController@kickit')->name('users.kickit');

    Route::get('/dbo','Users\ViewSupportController@disabled_but_online')->name('users.users.DBO_user');

    Route::post('/dataremove','Users\UserController@dataremove')->name('users.reset.verification');


    Route::post('/getcurrent-balance','Users\ReportController@current_balance_download_csv')->name('users.billing.current_balance');

    Route::get('/bill/view/{username}/{charge}','Users\InvoiceController@pdf_viewer')->name('admin.users.pdf_viewer');
    Route::get('/tax-calculator','Users\TaxCalculationController@tax_calculation')->name('user.tax_calcultion');
    Route::post('/tax-calculator/store','Users\TaxCalculationController@tax_calculation_action')->name('user.tax_calcultion.store');
    Route::post('/users/amount','Users\UserController@amount_profile')->name('users.user.amount');
    Route::get('/invoice-error','Users\InvoiceController@invoice_error')->name('users.invoice_error');



    /// Marketing Module ///
    Route::get('/marketing','Users\MarketingController@index')->name('users.marketing.index');
    Route::get('/marketing/add','Users\MarketingController@add')->name('users.marketing.add');
    Route::get('/marketing/show','Users\MarketingController@show')->name('users.marketing.show');
    Route::post('/marketing/store','Users\MarketingController@store')->name('users.marketing.store');
    Route::post('/marketing/update','Users\MarketingController@update')->name('users.marketing.update');
    Route::get('/marketing/view','Users\MarketingController@view')->name('users.marketing.view');
    Route::delete('/marketing/{id}','Users\MarketingController@delete')->name('users.marketing.delete');
    /// END ///

    /// Billing Module ///
    Route::get('/billing-history','Users\UserController@view_billing_history')->name('users.view_billing_history');
    /// END ///

    /// Assign IPs ///
    Route::get('/assignip','Users\AssignIpsController@assign_ip_data')->name('users.assignip.data');
    Route::get('/get-nas','Users\AssignIpsController@check_nas')->name('users.nas_assign.data');
    Route::post('/store-assignip','Users\AssignIpsController@assign_ip_store')->name('users.assignip.store');
    /// END ///

    /// Logout Module ///
    Route::post('/logout','Users\Auth\LoginController@logout')->name('users.logout');
    /// END ///

    Route::get('/dashboard','Users\DashboardController@index')->name('users.dashboard')->middleware('useraccessallow');
    Route::get('/getdata','Users\DashboardController@getdata')->name('users.getdata');
    Route::get('/getNumOfOnlineUsers','Users\DashboardController@getNumOfOnlineUsers')->name('users.getNumOfOnlineUsers');
    Route::get('/getDisabledUser','Users\DashboardController@getDisabledUser')->name('getDisabledUser');
    Route::get('/getErrorLog','Users\DashboardController@getErrorLog')->name('getErrorLog');
    Route::get('/login-logs-data', 'Users\DashboardController@getLoginLogs')->name('login.logs.data');


    Route::get('/users/index','Users\UserController@index')->name('users.user.index');
    Route::get('/users/expires/{status}','Users\UserController@epiredUser')->name('users.user.epiredUser');

    // Delete Old Consumers --------------- Start  ---------------
    Route::get('/delete-old-consumer',[DeleteOldConsumerController::class, 'index'])->name('show.users.index');
    Route::get('/get-delete-old-consumer',[DeleteOldConsumerController::class, 'show_delete_old_consumer'])->name('show.users');
    Route::post('/bulk-deletes', [DeleteOldConsumerController::class, 'bulkDeletes'])->name('users.bulk_deletes');
    Route::get('/get-contractors', [DeleteOldConsumerController::class, 'getContractors'])->name('get.contractors');
    // Delete Old Consumers --------------- End ---------------

    // Recreate User --------------- Start  ---------------
    Route::get('/recreate-user',[RecreateUserController::class, 'index'])->name('recreate.user');
    Route::post('/recreation-of-user',[RecreateUserController::class, 'recreation_of_user'])->name('recreation.of.user');
    // Recreate User --------------- End ---------------

    // Online View Consumer
    Route::get('/new-users/online/{status}','Users\UserController@onlineUsers')->name('users.user.onlineuser.view');
    Route::get('/users/online/datatable/view','Users\UserController@onlineUsers_get_table')->name('users.user.onlineuser.table');
    // End

    // offline View Consumer
    Route::get('/new-users/offline/{status}','Users\UserController@offlineUsers')->name('users.user.offlineuser.view');
    Route::get('/users/offline/datatable/view','Users\UserController@offlineUsers_get_table')->name('users.user.offlineuser.table');
    // End



    Route::get('/users/onlinePost','Users\UserController@onlinePost')->name('users.user.onlinePost');

    /// Online Consumers Detail ///
    Route::post('onlineUserDetails','Users\UserController@onlineUserDetails')->name('users.onlineUserDetails');
    /// END ///

    Route::get('/view-package','Users\UsersPackageController@index')->name('users.package.index');

    /// Offline Consumers Route ///
    Route::get('/users/offlineUserView','Users\UserController@offlineUserView')->name('users.offlineUserView');
    Route::get('/users/offlinePost','Users\UserController@offlinePost')->name('users.user.offlinePost');
    /// END ///

    /// Offline Consumers Details ///
    Route::post('offlineUserDetails','Users\UserController@offlineUserDetails')->name('users.offlineUserDetails');
    /// END ///

    /// Error Route ///
    Route::get('errorPage','Controller@errorMethod')->name('errorPage');
    /// END ///

    Route::get('/users/{status}','Users\UserController@index1')->name('users.user.index1')->middleware('useraccessallow');

    /// SMS VERIFICATION MODULE ///
    // Route::get('/verifyy/{username}','Users\UserController@verifyUser')->name('users.user.verifyUser');
    // Route::get('/smsverify/{username}','Users\UserController@verifySms')->name('users.user.smsverify');
    Route::get('/smsverify/{username}','Users\UserController@verifySms')->name('users.user.smsverify');
    Route::post('/smsverify','Users\SmsSenderController@verifySms')->name('users.user.smsverify');
    /// END ///

    Route::post('/users/{status}','Users\UserController@store')->name('users.user.post');
    Route::get('/users/{status}/{id}','Users\UserController@edit')->name('users.user.edit');
    Route::post('/users/{status}/{id}','Users\UserController@update')->name('users.user.update');
    Route::get('/user/{status}','Users\UserController@show')->name('users.user.show');

    /// TERMINATED CONSUMERS ///
    Route::post('/terminated','Users\UserController@userTerminated')->name('users.user.termination');
    /// END ///

    Route::get('/billing/{status}','Users\BillingController@index')->name('users.billing.index')->middleware('useraccessallow');
    Route::post('/billing/{status}','Users\BillingController@store')->name('users.billing.post');

    Route::get('/report/{status}','Users\ReportController@index')->name('users.report.index')->middleware('useraccessallow');
    Route::post('/billing/gen/report','Users\ReportController@generateBillingReport')->name('users.billing.report.generate');
    Route::post('/billing/gen/summary','Users\ReportController@billingSummary')->name('users.billing.summary.generate');

    Route::post('/billing/gen/report','Users\ReportController@reportSummary')->name('users.billing.report.generate');

    Route::get('/charge/{status}','Users\RechargeController@index')->name('users.charge.index')->middleware('useraccessallow');
    Route::post('/charge/single','Users\RechargeController@singleRecharge')->name('users.charge.single.post');

    // DELETED Route::post('/charge/bulk','Users\RechargeController@bulkRecharge')->name('users.charge.bulk.post');

    Route::post('ajax/charge/profileGroupWiseUsers','Users\RechargeController@profileWiseUsers')->name('users.ajax.charge.profileGroupWiseUsers');

    Route::get('/single/{id}','Users\RechargeController@single')->name('users.single');
    Route::post('/single/charge','Users\RechargeController@viewUserRecharge')->name('users.single.charge');


    Route::post('/single/recharge','Users\RechargeController@recharge_it')->name('users.single.recharge');

    // /////////// BULK RECHARGE ROUTES /////////////
    Route::get('/recharge/bulk','Users\BulkRechargeController@index')->name('users.bulk_recharge');
    Route::post('/recharge/bulk/show_consumer','Users\BulkRechargeController@show_consumer')->name('users.bulk_recharge.show_consumer');
    Route::post('/recharge/bulk/action','Users\BulkRechargeController@recharge')->name('users.bulk_recharge.action');
    Route::post('/recharge/bulk/logs','Users\BulkRechargeController@recharge_logs')->name('users.bulk_recharge.logs');
    Route::post('/recharge/bulk/errorlogs','Users\BulkRechargeController@error_logs')->name('users.bulk_recharge.errorlogs');
    ///////////////////////////////////////////////

    Route::get('/complaint','complain\ComplainController@index')->name('users.complain');
    Route::post('/complaint/give_feeback','complain\ComplainController@give_feedback')->name('users.complain.give_feedback');
    Route::post('/generate_complaint','complain\ComplainController@generate_complaint')->name('users.complain.generate_complaint');

    Route::get('/complaint/get_complain_nature','complain\ComplainController@get_complain_nature')->name('users.complain.get_complain_nature');

    Route::get('/password','Users\UpdatePasswordController@index')->name('users.billing.update_password')->middleware('useraccessallow');

    Route::post('/password','Users\UpdatePasswordController@changePass')->name('users.billing.update_password');

    Route::post('/reset','Users\UpdatePasswordController@reSetPass')->name('users.billing.reset');

    Route::get('/error_log','Users\LoginErrorController@index')->name('users.billing.error_log')->middleware('useraccessallow');
   Route::get('/get_error_log','Users\LoginErrorController@getErrorLogs')->name('admin.users.get_error_log');

    Route::get('/upcomingexpiry','Users\UpComingExpiryController@index')->name('users.billing.upcoming_expiry')->middleware('useraccessallow');
    Route::post('ajax/search','Users\SearchEngineController@index')->name('users.search.ajax.post');
    Route::get('users/billing/upcoming-expiry/data', 'Users\UpComingExpiryController@getUpcomingExpiryData')->name('users.billing.upcoming_expiry.data');

    Route::get('user/downloadsheet/{managerid}/{resellerid}','Users\DownloadSheetController@index')->name('users.my.sheetdownload');
    Route::get('user/downloadallusersheet/{resellerid}/{dealerid}','Users\DownloadSheetController@download_all_users')->name('users.all.sheetdownload');




    /// UTILIZATION INTERNET DATA ///
    Route::get('/maxdata','Users\MaxDataUsageController@index')->name('users.billing.max_data_usage')->middleware('useraccessallow');
    Route::post('/data_exceed_consumers_list','Users\MaxDataUsageController@data_exceed_consumers_list')->name('users.billing.data_exceed_consumers_list');
    /// END ///

    Route::post('ajax/checkunique','Users\CheckUniqueController@index')->name('users.checkunique.ajax.post');



    Route::get('/ledgereport','Users\LedgerReportController@index')->name('users.billing.ledger_report')->middleware('useraccessallow');

    Route::post('/pdfReport','Users\LedgerReportController@pdf')->name('users.billing.ledge_PDF');
    Route::post('/transferReport','Users\LedgerReportController@transferpdf')->name('users.billing.transfer_PDF');

    Route::post('/getReport','Users\ReportController@usersreport')->name('users.billing.usersreport');

    Route::get('/report_summary','Users\ReceiverAmountController@index')->name('users.billing.report_summary');

    Route::post('/billingPDF','Users\LedgerReportController@billingpdf')->name('users.billing.billingReport_PDF');

    Route::post('/invoicebillingPDF','Users\LedgerReportController@invoicepdf')->name('users.billing.invoiceReport_PDF');






    /// CLEAR MAC ADDRESS MODULE ///
    Route::post('/user/clearMac','Users\UserController@clearMac')->name('users.billing.user_detail');
    /// END ///

    Route::get('/singaluserdata','Users\UserController@singalUserData')->name('users.billing.singalUser');
    Route::get('/singalUserData1','Users\UserController@singalUserData1')->name('users.billing.singalUserData1');

    Route::post('/user/enableDisable','Users\UserController@enableDisable')->name('users.billing.enabledisable');


    Route::get('viewStatic','Users\ViewStaticController@index')->name('users.billing.view_static_ip')->middleware('useraccessallow');


    Route::get('/approve/{status}','Users\ApproveController@index')->name('users.approve.index')->middleware('useraccessallow');


    // Route::post('/freeze/freeze','Users\ApproveController@freezeDealers')->name('users.freeze.post');
    // Route::get('/freezeSubdealershow','Users\ApproveController@showSubDealer')->name('users.freeze.subdealershow');
    // Route::post('/freezeSubdealerPost','Users\ApproveController@freezeSubDealers')->name('users.freeze.subdealerpost');


    /// FREEZE MODULE ///
    Route::post('/freeze/freeze','Users\ApproveController@freezeDealers')->name('users.freeze.post');
    Route::get('/freezetradershow','Users\ApproveController@showTrader')->name('users.freeze.tradershow');
    Route::post('/freezetraderPost','Users\ApproveController@freezeTrader')->name('users.freeze.traderpost');
    Route::get('/freezeresellershow','Users\ApproveController@ShowResellers')->name('users.freeze.dealershow');
    Route::post('/freezeresellerpost','Users\ApproveController@freezeResellers')->name('users.freeze.dealerpost');
    Route::get('/freezeSubdealershow','Users\ApproveController@showSubDealer')->name('users.freeze.subdealershow');
    Route::post('/freezeSubdealerPost','Users\ApproveController@freezeSubDealers')->name('users.freeze.subdealerpost');
    /// END ///




    Route::get('/changePass','Users\UserController@changePass')->name('users.billing.changePass');
    Route::post('/change-user-pass','Users\UserController@change_user_pass')->name('users.billing.change.user.pass');

    Route::get('customerbilling/{username}/{date}','Users\LedgerReportController@customerBill')->name('users.billing.customer_bill_PDF');

    Route::get('/loadSubdealer','Users\ReportController@loadSubDealer')->name('users.report.loadSubDealer');
    Route::get('/loaddealer','Users\ReportController@loadDealer')->name('users.report.loadDealer');

    Route::get('/loadTrader','Users\ReportController@loadTrader')->name('users.report.loadTrader');

    Route::post('/pdfprofit','Users\LedgerReportController@reportSummerypdf')->name('users.billing.summeryDetail');

    Route::post('/pdfcommision','Users\LedgerReportController@commisionSummerypdf')->name('users.billing.commisionDetail');

    Route::post('/dealerpdfprofit','Users\LedgerReportController@dealerSummerypdf1')->name('users.billing.dealersummeryDetail');

    Route::post('/pdfreportprofit','Users\LedgerReportController@profitSummerypdf')->name('users.billing.profitSummary');

    Route::post('/billing/gen/details','Users\ReportController@reportdetails')->name('users.billing.report.details');

    Route::post('/billing/gen/dealerdetails','Users\ReportController@dealersubdealerdetails')->name('users.billing.report.dealerdetails');

    Route::post('/billing/gen/reseller_dealer_profit_detail','Users\ReportController@reseller_dealer_profit_detail')->name('users.billing.report.reseller_dealer_profit_detail');

    Route::post('/billing/gen/margin','Users\ReportController@marginReport')->name('users.billing.report.marginReport');

    Route::get('/allowPlan','Users\UserController@allowPlan')->name('users.billing.allowPlan');

    Route::get('/bill-register','Users\ReportController@bill_register')->name('users.billing.bill_register');

    Route::post('/bill-register-action','Users\ReportController@bill_register_action')->name('users.billing.bill_register_action');





    Route::get('/supportView','Users\SupportManagementController@index')->name('users.manage.supportView')->middleware('useraccessallow');
    Route::get('/edit','Users\SupportManagementController@edit')->name('users.manage.edit');
    Route::post('/store','Users\SupportManagementController@store')->name('users.manage.store');
    Route::get('/delete','Users\SupportManagementController@delete')->name('users.manage.delete');
    Route::get('/userUpdate/{id}','Users\SupportManagementController@update')->name('users.manage.userUpdate');
    Route::get('/allowAccess','Users\SupportManagementController@allow_Access')->name('users.manage.allowAccess')->middleware('useraccessallow');
    Route::post('/userAccess','Users\SupportManagementController@userAccess')->name('users.manage.userAccess');

    Route::get('/allowsubdealerAccess','Users\SubdealerManagementController@allow_Access')->name('users.manage.allowsubdealerAccess');
    Route::get('/subdealerAccess','Users\SubdealerManagementController@subdealerAccess')->name('users.manage.subdealerAccess');

    Route::post('/user/changeplan','Users\UserController@changePlan')->name('users.billing.changePlan');


    //  Tax Plan Route
    // Route::get('/viewplan','Users\TaxSectionController@profileData')->name('users.plan.viewplan');
    // Route::get('/viewprofilename','Users\TaxSectionController@profilename')->name('users.plan.viewprofilename');
    // Route::get('/groupName','Users\TaxSectionController@groupname')->name('users.plan.groupName');

    // User Panel Route
    Route::get('/showUser','Users\userPanelController@show')->name('users.userPanel.show');
    Route::get('/viewChangeUserPass','Users\userPanelController@viewChangeUserPass')->name('users.userPanel.viewChangeUserPass');
    Route::get('/changeUserPass','Users\userPanelController@changeUserPass')->name('users.userPanel.changeUserPass');

    Route::post('/checkExpire','Users\userPanelController@checkExpire')->name('users.userPanel.checkExpire');


    /// View Help Agent ///
    Route::get('/view/{id}','Users\SupportManagementController@view')->name('users.manage.view');

    Route::post('/agentaccount','Users\SupportManagementController@agent_account_disable')->name('users.access.disable');


    Route::post('/billing/gen/summary/advance','Users\ReportController@advanceSummary')->name('users.billing.summary.advance');
    Route::post('/billing/summary/invoice','Users\ReportController@invoiceSummary')->name('users.users.billing.summary.invoice');

    Route::post('/save','Users\BillingController@postReceive')->name('users.billing.save');

    Route::post('/cashreciept','Users\BillingController@recieptReceive')->name('users.billing.reciept');


    Route::post('/billpaid','Users\BillingController@ipBill')->name('users.billing.bill');

    Route::post('Supportsearch','Users\SupportSearchController@index')->name('users.Supportsearch');
    Route::post('searchResult','Users\SupportSearchController@searchResult')->name('users.SupportsearchResult');
    Route::post('SupportupdownGraph','Users\SupportSearchController@showupDownGraph')->name('users.SupportupdownGraph');
    Route::post('SupportclearMac','Users\SupportSearchController@SupportclearMac')->name('users.SupportSupportclearMac');


    /// Exceed Data Route ///
    Route::get('ExceecData','Users\ExceedDataController@index')->name('users.ExceecData')->middleware('useraccessallow');
    /// END ///

    /// Live Consumers Show ///
    Route::post('/liveusers','Users\liveUserController@live')->name('users.liveusers');
    Route::post('/showExceedData','Users\liveUserController@exceedData')->name('users.showExceedData');

    Route::post('/checkamount','Users\BillingController@CheckAmount')->name('users.check.dues');

    /// Consumers Sms Verify ///
    Route::get('/verifySms','Users\userPanelController@verifySms')->name('users.userPanel.verifySms');
    Route::get('/userMobileVerify','Users\userPanelController@userMobileVerify')->name('users.userPanelView.userMobileVerify');

    /// Consumers Cnic Route ///
    Route::get('/userNicView','Users\userPanelController@userNicView')->name('users.userPanel.userNicView');
    Route::get('/userNicData','Users\userPanelController@userNicData')->name('users.userPanel.userNicData');
    Route::post('/addNic','Users\userPanelController@addUserCnicData')->name('users.userPanel.addNic');

    /// Verification Route ///
    /// CNIC Verifications ///
    Route::post('/nicVerify','Users\NicController@verifyUser')->name('users.user.nicVerify');
    Route::post('/addNicToDB','Users\NicController@addCnicData')->name('users.billing.addNicToDB');
    Route::get('/nicData','Users\NicController@nicData')->name('users.billing.nicData');

    /// SMS Verifications ///
    Route::post('/smsverify','Users\SmsSenderController@verifySms')->name('users.user.smsverify');
    Route::post('/smsSend','Users\SmsSenderController@sendSms')->name('users.billing.smsSend');
    Route::post('/storeMobileCode','Users\SmsSenderController@addMobileData')->name('users.billing.addMobileCode');
    Route::get('/mobileData','Users\SmsSenderController@MobileData')->name('users.billing.mobileData');
    Route::post('/validCode','Users\SmsSenderController@fetchValidCode')->name('users.billing.validCode');

    /// Ticker Route ///
    Route::post('ticker','Users\TickerController@loadTicker')->name('users.ticker');

    Route::get('/setmargins','Users\MarginController@index')->name('users.billing.margin')->middleware('useraccessallow');
    Route::Post('/userMargin','Users\MarginController@loadUserStatus')->name('users.billing.MarginUserStatus');
    Route::Post('/updateMigrateData','Users\MarginController@updateMigrateData')->name('users.billing.updateMigrateData');
    Route::post('/updateMarginDB','Users\MarginController@updateMarginDB')->name('users.billing.updateMarginDB');


    /// DHCP IP Route Code Start ///
    Route::get('/dhcp','Users\DHCPController@DHCP_IP')->name('user.dhcp');

    /// Never Expire Route start ///
    Route::get('/never_expire/consumers','Users\NeverExpireController@index')->name('users.never_expire.consumers');
    Route::get('/never_expire/consumers/get-consumers','Users\NeverExpireController@getNeverExpireUsers')->name('users.never_expire.getConsumers');

    Route::post('/never_expire_update','Users\NeverExpireController@never_expire_update')->name('users.never_expire_update');
    Route::post('/never_expire/errorlogs','Users\NeverExpireController@error_logs')->name('users.never_expire.errorlogs');

    Route::get('/never_expire/get_modal_content','Users\NeverExpireController@get_modal_content')->name('users.never_expire.get_modal_content');


     /// Menus and Sub Menus Route -- Only Admin Access ///
    Route::get('menus/create', 'Users\MenusController@create')->name('menus.create');
    Route::post('menus/create', 'Users\MenusController@store')->name('menus.store');
    Route::get('menus/index', 'Users\MenusController@index')->name('menus.index');

     // Route::get('menus/show/{id}', 'Source\MenusController@show')->name('menus.show');
    Route::get('menus/update/{id}', 'Users\MenusController@edit')->name('menus.edit');
    Route::post('menus/update/{id}', 'Users\MenusController@update')->name('menus.update');
    Route::post('menus/delete/{id}', 'Users\MenusController@destroy')->name('menus.delete');
    Route::post('menus/checkroute', 'Users\MenusController@checkroute')->name('menus.checkroute');
    Route::post('submenus/delete', 'Users\MenusController@subMenuDelete')->name('submenus.delete');

     /// Consumer Menu Access ///
    Route::get('useraccess/index', 'Users\UserMenuAccessController@index')->name('useraccess.index')->middleware('useraccessallow');
    Route::get('useraccess/showold/', 'Users\UserMenuAccessController@show_old')->name('useraccess.show_old');
    Route::get('useraccess/show/{id}', 'Users\UserMenuAccessController@show')->name('useraccess.show');
    Route::post('useraccess/update/', 'Users\UserMenuAccessController@update')->name('useraccess.update');



    Route::get('useraccess/test/', 'Users\UserMenuAccessController@newtest');


     /// ABO Route ///
    Route::get('/abo', 'Users\AboController@index')->name('users.abo')->middleware('useraccessallow');
    Route::get('/fetch-abo-user', 'Users\AboController@fetchABOUsers')->name('fetch.abo.user')->middleware('useraccessallow');
    Route::get('/susUserDetails', 'Users\AboController@susUserDetails')->name('users.susUserDetails');

     ///  ApprovedNewUserController Code Route ///
    Route::get('/ApprovedNewUser', 'Users\ApprovedNewUserController@index')->name('users.ApprovedNewUser');
    Route::get('/ApprovedNewUserNotification', 'Users\ApprovedNewUserController@approvedNewUserNotification')->name('users.ApprovedNewUserNotification');
    Route::post('/approveNewUserPost', 'Users\ApprovedNewUserController@approveNewUserPost')->name('users.approveNewUserPost');
    Route::post('/rejectNewUserPost', 'Users\ApprovedNewUserController@rejectNewUserPost')->name('users.rejectNewUserPost');

     ///  Card Transfer Route Module ///
    Route::get('/viewTransferToken', 'Users\TransferCardController@index')->name('users.viewTransferToken');
    Route::get('/fetchProfile', 'Users\TransferCardController@fetchProfile')->name('users.fetchProfile');
    Route::post('/checkCard', 'Users\TransferCardController@checkCard')->name('users.checkCard');
    Route::post('/transferCard', 'Users\TransferCardController@transferCard')->name('users.transferCard');
    Route::get('/viewtransferCard', 'Users\TransferCardController@viewTransferCard')->name('users.viewtransferCard');

    Route::get('/billingcarddetailView','Users\TransferCardController@BillingDetailView')->name('users.BillingDetailView');
    Route::post('/billingCardDetail','Users\TransferCardController@BillingCardDetail')->name('users.BillingCardDetail');

     /// New Server side Data Table Code ///
    Route::get('/terminateServerSideUser','Users\UserController@terminateServerSideUser')->name('users.terminateServerSideUser');
    Route::get('/expireServerSideUser','Users\UserController@expireServerSideUser')->name('users.expireServerSideUser');
    Route::get('/viewCustomerServerSideUser','Users\UserController@viewCustomerServerSideUser')->name('users.viewCustomerServerSideUser');


     /// CIR PROFILE CHANGE ///
    Route::post('/cirProfile','Users\UserController@cirProfile')->name('users.cirProfile');
     /// END ///

     /// SMS Test Route ///
    Route::get('/smsTest','Users\UserController@smsTest');
     /// END ///

     /// Report Datatable Server Side Route ///
    Route::get('cashReceipt','Users\BillingController@serverSideCashReceipt')->name('userSideCashReceipt');
    Route::get('viewTransferserver','Users\BillingController@viewTransferServerSide')->name('viewTransferserver');
    Route::get('dealerviewtransfer','Users\DealerViewTransferController@dealerviewtransfer')->name('users.billing.dealerviewtransfer');
    Route::post('/getSubDealer','Users\DealerViewTransferController@getSubDealer')->name('users.getSubDealer');
    Route::post('dealerviewtransferpost','Users\DealerViewTransferController@dealerviewtransferPost')->name('users.billing.dealerviewtransferpost');
    Route::get('dealerclosingamount','Users\DealerViewTransferController@viewDealerClosingAmount')->name('users.billing.dealerclosingamount');

     // Reseller / contractor / trader / user delete Route
    Route::get('deletedata','Users\DealerViewTransferController@deletedata')->name('users.data-delete.deletedata');

    ///// change CGN IP
    Route::get('change-cgn-ip','Users\DealerViewTransferController@change_cgn_ip')->name('users.change_cgn_ip');
    Route::post('change-cgn-ip-action','Users\DealerViewTransferController@change_cgn_ip_action')->name('users.change_cgn_ip_action');

     /// Consumer Biling Download code Route ///
    Route::get('download','Users\DownloadMonthlyBillingController@index')->name('users.billing.download');
    Route::post('downloadPost','Users\DownloadMonthlyBillingController@downloadExcel')->name('users.billing.downloadPost');

     /// Delete Transfer Route ///
    Route::get('deleteTransferToDealer','Users\BillingDeleteController@deleteTransferToDealer')->name('user.deleteTransferToDealer');
    Route::post('deleteTransferPost','Users\BillingDeleteController@deleteTransferPost')->name('user.deleteTransferPost');
      //  Crons both DB Route for myself

     /// Billing 10 and 25 DATE Delete Consumer ID's Route ///
    Route::get('billingDeleteUserFetch','Users\BillingDeleteController@billingDeleteUserFetch')->name('billingDeleteUserFetch');
    Route::post('billingDeleteUserPost','Users\BillingDeleteController@billingDeleteUserPost')->name('billingDeleteUserPost');
     /// END ///

     /// DEALER/CONTRACTOR MATCHING BILLING ///
    Route::get('/ledgereport/dealer_matching', 'Users\LedgerReportController@dealer_matching')->name('users.dealer_matching.ledger_report')->middleware('useraccessallow');
    Route::post('/pdfReport/dealer_matching', 'Users\LedgerReportController@dealer_matching_pdf')->name('users.dealer_matching.ledger_PDF');
     /// END ///

    Route::get('/profit_calculator', 'Users\ProfitCalculatorController@index')->name('users.profit_calculator');
    Route::post('/profit_calculator_action', 'Users\ProfitCalculatorController@calculate_action')->name('users.calculate_action');


    ///// advance serach get users
    Route::get('/get-users', 'Users\GetUserController@index')->name('get-users');
    Route::get('/get-filtered-users', 'Users\GetUserController@getFilterdUser')->name('get-filtered-users');
    Route::get('/details/{id}', 'Users\GetUserController@getUserDetails');
//
    Route::post('/get/contractor-trader','Users\UserController@get_contractor_trader_profiles');


    Route::get('/checkDb','Users\CronsController@index');

    Route::get('/receipt_bill',function(){
     return view('users.billing.cash_reciept');
   });
   Route::get('/preview-login/{username}', 'Users\Auth\LoginController@previewLogin')->name('users.user.previewlogin');

   Route::get('/dummy-entries', 'Users\UserController@dummyDataInRadAcct')->name('dummy.data');

  });


});




