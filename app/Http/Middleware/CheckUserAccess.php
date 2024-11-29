<?php

namespace App\Http\Middleware;
use App\model\Users\SubMenu;
use App\model\Users\UserMenuAccess;
use Closure;
use Auth;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $status = UserMenuAccess::join('sub_menus', 'user_menu_accesses.sub_menu_id', '=', 'sub_menus.id')
        ->where('sub_menus.route_name',$request->route()->getName())->where('sub_menus.flag','cp')->where('user_menu_accesses.user_id',Auth::id());
        if($request->route('status') != null)
        {
            $status = $status->where('sub_menus.paramvalue',$request->route('status'));
        }
        
        $status = $status->select('user_menu_accesses.status','sub_menus.param','sub_menus.paramvalue')->first();
        // dd($status->status);
        // $d = $request->route($status->param);
        // dd($status); 

        if(isset($status->status)) {
            if($status->status == 0)
            {
                return redirect('/401');
            }
        }
        return $next($request);
    }
}
