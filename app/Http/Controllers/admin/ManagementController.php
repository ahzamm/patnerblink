<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Menu;
use App\model\Users\SubMenu;
use App\model\admin\AdminMenu;
use App\model\admin\AdminSubMenu;
use App\model\Users\UserMenuAccess;
use App\model\admin\AdminMenuAccess;
use App\model\admin\Admin;
use App\model\admin\Access;
use App\model\admin\Ticker;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use Session;
use Validator;

class ManagementController extends Controller
{
    public function index()
    {
        $status = Auth::user()->status;
        if ($status != 'super') {
            return redirect()->route('admin.dashboard');
        } else {

            $allData = Admin::where('status', '!=', 'super')->where('active', '=', '1')->get();
            return view('admin.AccessManagement.viewUsers', [
                'allData' => $allData
            ]);
        }
    }

    public function admin_roles($id)
    {
        $status = Auth::user()->status;
        if ($status != 'super') {
            return redirect()->route('admin.dashboard');
        } else {

            $admins = Admin::where('status', '!=', 'super')->get();
            $menu_management = AdminMenu::join('admin_sub_menus', 'admin_menus.id', '=', 'admin_sub_menus.menu_id')->get(['admin_menus.menu', 'admin_sub_menus.submenu', 'admin_sub_menus.menu_id', 'admin_sub_menus.id']);
            $user = Admin::where('id', $id)->first();
            return view('admin.AdminRoles.index', [
                'admins' => $admins,
                'access' => $menu_management,
                'user_id' => $id,
                'user' => $user
            ]);
        }
    }

    public function store(Request $request)
    {
        $admin = new Admin();

        $admin->username = $request->get('username');
        $admin->password = Hash::make($request->get('password'));
        $admin->firstname = $request->get('fname');
        $admin->lastname = $request->get('lname');
        $admin->nic = $request->get('nic');
        $admin->email = $request->get('mail');
        $admin->address = $request->get('address');
        $admin->mobilephone = $request->get('mobile_number');
        $admin->status = $request->get('status');
        $admin->homephone = $request->get('land_number');
        $admin->save();

        DB::update('ALTER TABLE Access ADD COLUMN user' . $admin->id . ' INT NOT NULL DEFAULT 0 ;');
        return redirect()->route('admin.AccessManagement.viewUsers');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $admin = Admin::find($id);

        ?>
        <form action="userUpdate/<?php echo $admin->id ?>" method="get" autocomplete="off">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color: red">*</span></label>
                        <input type="text" value="<?php echo $admin->firstname ?>" name="fname" class="form-control"
                            placeholder="firstname" required readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color: red">*</span></label>
                        <input type="text" value="<?php echo $admin->lastname ?>" name="lname" class="form-control"
                            placeholder="lastname" required readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">CNIC Number<span style="color: red">*</span></label>
                        <input type="tel" value="<?php echo $admin->nic ?>" name="nic" class="form-control"
                            data-mask="99999-9999999-9" placeholder="99999-9999999-9" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address<span style="color: red">*</span></label>
                        <input type="email" value="<?php echo $admin->email ?>" name="mail" class="form-control"
                            placeholder="info@lbi.net.pk" required>
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="form-group">
                        <label class="form-label">Business Address <span style="color: red">*</span></label>
                        <input type="text" value="<?php echo $admin->address ?>" name="address" class="form-control"
                            placeholder="Address" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mobile Number <span style="color: red">*</span></label>
                        <input type="text" name="mobile_number" value="<?php echo $admin->mobilephone ?>" class="form-control"
                            placeholder="9999 9999999" data-mask="9999 9999999" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Landline Number</label>
                        <input type="text" value="<?php echo $admin->homephone ?>" name="land_number" class="form-control"
                            placeholder="(999)9999999" data-mask="(999)9999999">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign Department <span style="color: red">*</span></label>
                        <select name="status" id="" class="form-control" required>
                            <option value="">Assign Department</option>
                            <?php
                            if ($admin->status == "account") { ?>
                                <option value="admin">Adminstrator</option>
                                <option value="noc">NOC Department</option>
                                <option value="account">Account Department</option>
                                <option value="support">Support Department</option>
                                <option value="engineering">Engineering Department</option>
                                <option value="IT">IT Department</option>
                                <?php
                            } else {
                                ?>
                                <option value="admin">Adminstrator</option>
                                <option value="noc">Noc Department</option>
                                <option value="account">Account Department</option>
                                <option value="support">Support Department</option>
                                <option value="engineering">Engineering Department</option>
                                <option value="IT">IT Department</option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Username <span style="color: red">*</span></label>
                        <input type="text" value="<?php echo $admin->username ?>" class="form-control" placeholder="User Name"
                            name="username" readonly>
                    </div>
                    <div class="form-group" style="position: relative">
                        <label class="form-label">Update Password <span style="color: red">*</span></label>
                        <input type="Password" id="mgmt_upass" name="password" value="" class="form-control"
                            placeholder="Password must be 8 characters long" required>
                        <i class="fa fa-eye-slash mgmt_upass" style="position: absolute;bottom: 9px;right: 12px;"
                            onclick="togglePassword('mgmt_upass');"> </i>
                    </div>




                </div>
                <div class="col-md-12">
                    <div class="form-group pull-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button data-dismiss="modal" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            </div>
        </form>

        <?php

    }

    public function update($id, Request $request)
    {

        $admin = Admin::find($id);

        $admin->username = $request->get('username');
        $admin->password = Hash::make($request->get('password'));
        $admin->firstname = $request->get('fname');
        $admin->lastname = $request->get('lname');
        $admin->nic = $request->get('nic');
        $admin->email = $request->get('mail');
        $admin->address = $request->get('address');
        $admin->mobilephone = $request->get('mobile_number');
        $admin->status = $request->get('status');
        $admin->homephone = $request->get('land_number');

        $admin->save();
        return redirect()->route('admin.AccessManagement.viewUsers');
    }
    public function delete(Request $request)
    {
        $id = $request->get('id');
        Admin::find($id);
        Admin::where(['id' => $id])->update(['active' => '0']);
        return redirect()->route('admin.AccessManagement.viewUsers');
    }
    public function disable(Request $request)
    {
        $id = $request->get('id');
        Admin::find($id);
        Admin::where(['id' => $id])->update(['enable' => '0']);
        return redirect()->route('admin.AccessManagement.viewUsers');
    }
    public function enable(Request $request)
    {
        $id = $request->get('id');
        Admin::find($id);
        Admin::where(['id' => $id])->update(['enable' => '1']);
        return redirect()->route('admin.AccessManagement.viewUsers');
    }

    public function allow_Access()
    {
        $status = Auth::user()->status;
        if ($status != 'super') {
            return redirect()->route('admin.dashboard');
        } else {

            $allData = Admin::where('status', '!=', 'super')->select('id', 'username', 'status')->get();
            $access = Access::all();
            return view('admin.AccessManagement.allowAccess', [
                'allData' => $allData,
                'access' => $access
            ]);
        }
    }
    public function userAccess(Request $request)
    {
        $id = $request->get('id');
        $userColumn = 'user' . $id;
        $check = $request->get('isChecked');
        $child = $request->get('child');
        if ($check == 'true') {
            DB::update('update Access set ' . $userColumn . ' = ? where childModule = ?', [1, $child]);
        } else {
            DB::update('update Access set ' . $userColumn . ' = ? where childModule = ?', [0, $child]);
        }
        return $check;
    }
    public function ticker()
    {
        return view('admin.AccessManagement.viewTicker');
    }

    public function tickerToDb(Request $request)
    {

        $ticker = Ticker::find(1);
        $ticker->eng = $request->get('eng');
        $ticker->urdu = $request->get('urdu');

        $ticker->save();
        return redirect()->route('admin.AccessManagement.viewTicker');
    }

    public function sub_menu_list(Request $request)
    {
        $menu_management = Menu::join('sub_menus', 'menus.id', '=', 'sub_menus.menu_id')->get(['menus.menu', 'sub_menus.submenu', 'sub_menus.menu_id', 'sub_menus.manager', 'sub_menus.reseller', 'sub_menus.dealer', 'sub_menus.subdealer', 'sub_menus.trader', 'sub_menus.inhouse', 'sub_menus.id']);

        return view('admin.menu-management.index', compact('menu_management'));
    }

    public function sub_menu_update(Request $request)
    {
        $submenuid = $request->get('id');
        $column = $request->get('col');
        $status = $request->get('status');
        SubMenu::where('id', $submenuid)->update([$column => $status]);
    }

    public function admin_menu(Request $request)
    {
        if ($request->ajax()) {
            $menu_management = AdminMenu::select(['id', 'menu', 'has_submenu', 'icon', 'priority', 'sort_id']);
            return DataTables::of($menu_management)
                ->addColumn('action', function ($menu) {
                    return '<button class="btn btn-xs btn-info update-btn" data-id="' . $menu->id . '"><i class="fa fa-edit"></i> Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $menu_management = AdminMenu::orderBy('sort_id', 'asc')->get();
        $sub_menu_management = AdminSubMenu::with('menu')->get();
        return view('admin.AdminRoles.admin-menu', compact('menu_management', 'sub_menu_management'));
    }

    public function updateOrder(Request $request)
    {
        $menuIds = $request->input('menu_id');

        if (!is_array($menuIds)) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.']);
        }

        foreach ($menuIds as $index => $id) {
            AdminMenu::where('id', $id)->update(['sort_id' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
    }

    public function admin_submenu(Request $request)
    {
        if ($request->ajax()) {
            $submenu_management = AdminSubMenu::with('menu')->orderBy('sort_id', 'asc')->select(['id', 'submenu', 'menu_id', 'route_name']);

            return DataTables::of($submenu_management)
                ->addColumn('menu', function ($submenu) {
                    return $submenu->menu ? $submenu->menu->menu : 'N/A';
                })
                ->addColumn('action', function ($submenu) {
                    return '<button class="btn btn-xs btn-info update-s-btn" data-sub-id="' . $submenu->id . '"><i class="fa fa-edit"></i> Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function updateSubmenuOrder(Request $request)
    {
        $submenuIds = $request->input('submenu_id');

        if (!is_array($submenuIds)) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.']);
        }

        foreach ($submenuIds as $index => $id) {
            AdminSubMenu::where('id', $id)->update(['sort_id' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Submenu order updated successfully.']);
    }

    public function getSubmenusByMenu(Request $request)
    {
        $menuId = $request->input('menu_id');

        if (!$menuId) {
            return response()->json(['success' => false, 'message' => 'Invalid menu ID provided.']);
        }

        $submenus = AdminSubMenu::where('menu_id', $menuId)->orderBy('sort_id', 'asc')->get();

        return response()->json(['success' => true, 'data' => $submenus]);
    }




    public function store_admin_menu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required|unique:menus',
            'icon' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $has_menu = 0;
        if ($request->has('has_submenu')) {
            $has_menu = 1;
        }
        $admin = new AdminMenu();
        $admin->menu = $request->get('menu');
        $admin->has_submenu = $has_menu;
        $admin->icon = $request->get('icon');
        $admin->priority = $request->get('priority');

        $admin->save();
        return redirect()->route('admin.AdminRoles.admin-menu')->with('success', 'Successfully Add');
    }
    public function store_admin_submenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submenu' => 'required|unique:sub_menus',
            'menu_id' => 'required',
            'route' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $sub_menu = new AdminSubMenu();
        $sub_menu->submenu = $request->get('submenu');
        $sub_menu->menu_id = $request->get('menu_id');
        $sub_menu->route_name = $request->get('route');
        $sub_menu->save();


        $get_max = DB::table('admin_sub_menus')->select(DB::raw('max(id) as max'))->first();
        $admin_menu_access = new AdminMenuAccess();
        $admin_menu_access->user_id = 1;
        $admin_menu_access->sub_menu_id = $get_max->max;
        $admin_menu_access->status = 1;
        $admin_menu_access->save();
        return redirect()->route('admin.AdminRoles.admin-menu')->with('success', 'Successfully Add');
    }
    public function edit_admin_menu(Request $request)
    {
        $get_id = $request->get('get_id');
        $get_menu_data = AdminMenu::find($get_id);
        return $get_menu_data;
    }
    public function edit_admin_submenu(Request $request)
    {
        $get_sub_id = $request->get('get_id');
        $get_sub_menu_data = AdminSubMenu::find($get_sub_id);

        return $get_sub_menu_data;
    }
    public function update_admin_menu(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'menu' => 'required|sometimes',
            'priority' => 'required|sometimes',
            'icon' => 'required|sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $has_menu = 0;
        if ($request->has('has_submenu') == 'true') {
            $has_menu = 1;
        }

        $menu_update = ['menu' => $request->get('menu'), 'has_submenu' => $has_menu, 'icon' => $request->get('icon'), 'priority' => $request->get('priority')];
        $update_menu = AdminMenu::where('id', $request->get('menu_id'))->update($menu_update);
        return response()->json(['success' => 'Menu Has been Update Successfully']);
    }

    public function admin_submenu_update(Request $request)
    {
        $submenuid = $request->get('id');
        $column = $request->get('col');
        $status = $request->get('status');
        AdminSubMenu::where('id', $submenuid)->update([$column => $status]);
    }

    public function update_admin_submenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submenu' => 'required|sometimes',
            'menu_id' => 'required|sometimes',
            'priority' => 'required|sometimes',
            'route' => 'required|sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $menu_update = ['submenu' => $request->get('submenu'), 'priority' => $request->get('priority'), 'menu_id' => $request->get('menu_id'), 'route_name' => $request->get('route')];
        $update_menu = AdminSubMenu::where('id', $request->get('sub_menu_id'))->update($menu_update);
        return response()->json(['success' => 'Admin Sub-Menu Has been Update Successfully']);
    }
    public function admin_menu_update(Request $request)
    {
        $user_id = $request->get('user_id');
        $check = $request->get('isChecked');
        $submenu = $request->get('id');
        $loadData = AdminMenuAccess::where('sub_menu_id', $submenu)->where('user_id', $user_id)->select('status')->first();
        if (! empty($loadData)) {
            $dbcheck = $loadData->status;
            if ($dbcheck == 0) {
                DB::update('update admin_menu_accesses set status = ? where sub_menu_id = ? and user_id = ?', [1, $submenu, $user_id]);
            } elseif ($dbcheck == 1) {
                DB::update('update admin_menu_accesses set status = ? where sub_menu_id = ? and user_id = ?', [0, $submenu, $user_id]);
            }
        } else {
            $admin_menu_access = new AdminMenuAccess();
            $admin_menu_access->user_id = $user_id;
            $admin_menu_access->sub_menu_id = $submenu;
            $admin_menu_access->status = 1;
            $admin_menu_access->save();
        }
        return $check;
    }



    public function menu(Request $request)
    {
        if ($request->ajax()) {
            $menu_management = Menu::orderBy('sort_id', 'asc')->select(['id', 'menu', 'has_submenu', 'icon', 'priority']);
            return DataTables::of($menu_management)
                ->addColumn('action', function ($menu) {
                    return '<button class="btn btn-xs btn-info update-btn" data-id="' . $menu->id . '"><i class="fa fa-edit"></i> Edit</button>';
                })
                ->make(true);
        }

        $menu_management = Menu::orderBy('sort_id', 'asc')->get();
        $sub_menu_management = SubMenu::with('menu')->get();
        return view('admin.menu-management.menu', compact('menu_management', 'sub_menu_management'));
    }

    public function updateMenuOrder(Request $request)
    {
        $menuIds = $request->input('menu_id');

        if (!is_array($menuIds)) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.']);
        }

        foreach ($menuIds as $index => $id) {
            Menu::where('id', $id)->update(['sort_id' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
    }

    public function submenu(Request $request)
    {
        if ($request->ajax()) {
            $submenu_management = SubMenu::with('menu')
                ->orderBy('sort_id', 'asc')
                ->select(['id', 'submenu', 'menu_id', 'route_name', 'sort_id']);

            return DataTables::of($submenu_management)
                ->addColumn('menu', function ($submenu) {
                    return $submenu->menu ? $submenu->menu->menu : 'N/A';
                })
                ->addColumn('action', function ($submenu) {
                    return '<button class="btn btn-xs btn-info update-s-btn" data-sub-id="' . $submenu->id . '">
                                <i class="fa fa-edit"></i> Edit
                            </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function updateUserSubmenuOrder(Request $request)
    {
        $submenuIds = $request->input('submenu_id');

        if (!is_array($submenuIds)) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.']);
        }

        foreach ($submenuIds as $index => $id) {
            SubMenu::where('id', $id)->update(['sort_id' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Submenu order updated successfully.']);
    }

    public function getUserSubmenusByMenu(Request $request)
    {
        $menuId = $request->input('menu_id');

        if (!$menuId) {
            return response()->json(['success' => false, 'message' => 'Invalid menu ID provided.']);
        }

        $submenus = SubMenu::where('menu_id', $menuId)->orderBy('sort_id', 'asc')->get();

        return response()->json(['success' => true, 'data' => $submenus]);
    }


    public function store_menu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required|unique:menus',

            'icon' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $has_menu = 0;
        if ($request->has('has_submenu')) {
            $has_menu = 1;
        }

        $admin = new Menu();
        $admin->menu = $request->get('menu');
        $admin->has_submenu = $has_menu;
        $admin->icon = $request->get('icon');

        $admin->save();
        return redirect()->route('admin.Management.menu')->with('success', 'Successfully Add');
    }

    public function store_submenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submenu' => 'required|unique:sub_menus',
            'menu_id' => 'required',
            'route' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $sub_menu = new SubMenu();
        $sub_menu->submenu = $request->get('submenu');
        $sub_menu->menu_id = $request->get('menu_id');
        $sub_menu->route_name = $request->get('route');
        $sub_menu->save();

        $id = $sub_menu->id;

        $users = DB::table('user_info')->select('id')->where('status', '!=', 'user')->get();

        foreach ($users as $value) {
            $user_menu_access = new UserMenuAccess();
            $user_menu_access->user_id = $value->id;
            $user_menu_access->sub_menu_id = $id;
            if ($value->id == 1) {
                $user_menu_access->status = 1;
            }
            $user_menu_access->save();
        }

        return redirect()->route('admin.Management.menu')->with('success', 'Successfully Add');
    }
    public function edit_menu(Request $request)
    {
        $get_id = $request->get('get_id');
        $get_menu_data = Menu::find($get_id);
        return $get_menu_data;
    }
    public function edit_submenu(Request $request)
    {
        $get_sub_id = $request->get('get_id');
        $get_sub_menu_data = SubMenu::find($get_sub_id);
        return $get_sub_menu_data;
    }
    public function update_menu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required|sometimes',
            'priority' => 'required|sometimes',
            'icon' => 'required|sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $has_menu = 0;
        if ($request->has('has_submenu') == 'true') {
            $has_menu = 1;
        }

        $menu_update = ['menu' => $request->get('menu'), 'has_submenu' => $has_menu, 'icon' => $request->get('icon'), 'priority' => $request->get('priority')];
        $update_menu = Menu::where('id', $request->get('menu_id'))->update($menu_update);

        return response()->json(['success' => 'Menu Has been Update Successfully']);
    }
    public function update_submenu(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'submenu' => 'required|sometimes',
            'menu_id' => 'required|sometimes',
            'priority' => 'required|sometimes',
            'route' => 'required|sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        $menu_update = ['submenu' => $request->get('submenu'), 'priority' => $request->get('priority'), 'menu_id' => $request->get('menu_id'), 'route_name' => $request->get('route')];
        $update_menu = SubMenu::where('id', $request->get('sub_menu_id'))->update($menu_update);

        return response()->json(['success' => 'Sub-Menu Has been Update Successfully']);
    }
    public function view($id)
    {
        $data = Admin::find($id);
        return view('admin.AccessManagement.view-agent', compact('data'));
    }


}
