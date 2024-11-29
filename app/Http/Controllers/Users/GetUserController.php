<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\model\Users\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetUserController extends Controller
{

    public function index()
    {
        return view('users.GetUsers.viewUsers');
    }


    public function getFilterdUser(Request $request)
    {
        $query = UserInfo::query()->where('user_info.status', 'user');

        $query->leftJoin('user_status_info', 'user_info.username', '=', 'user_status_info.username')
            ->leftJoin('user_ip_status', 'user_info.username', '=', 'user_ip_status.username')
            ->leftJoin('user_verification', 'user_info.username', '=', 'user_verification.username')
            ->leftJoin('disabled_users', 'user_info.username', '=', 'disabled_users.username')
            ->leftJoin('manager_profile_rate', 'user_info.manager_id', '=', 'manager_profile_rate.manager_id');

        $user = Auth::user();
        if ($user->status === 'reseller') {
            $query->where('user_info.resellerid', $user->resellerid);
        } elseif ($user->status === 'dealer') {
            $query->where('user_info.dealerid', $user->dealerid);
        } elseif ($user->status === 'subdealer') {
            $query->where('user_info.sub_dealer_id', $user->sub_dealer_id);
        }

        if ($request->filled('resellerId')) {
            $query->where('user_info.resellerid', $request->resellerId);
        }
        if ($request->filled('contractorId')) {
            $query->where('user_info.dealerid', $request->contractorId);
        }
        if ($request->filled('traderId')) {
            $query->where('user_info.sub_dealer_id', $request->traderId);
        }
        if ($request->filled('managerProfile')) {
            // dd($request->managerProfile);
            $query->where('user_info.name', $request->managerProfile);
        }
        if ($request->filled('chargeOnRange')) {
            $chargeOnRange = explode(' - ', $request->chargeOnRange);
            $query->whereBetween('user_status_info.card_charge_on', [$chargeOnRange[0], $chargeOnRange[1]]);
        }
        if ($request->filled('expireOnRange')) {
            $expireOnRange = explode(' - ', $request->expireOnRange);
            $query->whereBetween('user_status_info.card_expire_on', [$expireOnRange[0], $expireOnRange[1]]);
        }
        if ($request->filled('searchIP')) {
            $query->where('user_ip_status.ip', 'like', '%' . $request->searchIP . '%');
        }
        if ($request->filled('verifiedBy')) {
            $verifiedBy = $request->verifiedBy;

            if ($verifiedBy === 'CNIC') {
                $query->whereNotNull('user_verification.cnic');
            } elseif ($verifiedBy === 'Mobile') {
                $query->whereNotNull('user_verification.mobile');
            } elseif ($verifiedBy === 'All') {
                $query->whereNotNull('user_verification.cnic')
                      ->whereNotNull('user_verification.mobile');
            }
        }
        if ($request->filled('userStatus')) {
            if ($request->userStatus === 'enable') {
                $query->where(function ($subQuery) {
                    $subQuery->where('disabled_users.status', 'enable')
                             ->orWhereNull('disabled_users.status');
                });
            } elseif ($request->userStatus === 'disable') {
                $query->where('disabled_users.status', 'disable');
            }
        }
        if ($request->filled('cardStatus')) {
            $today = now()->format('Y-m-d');
            if ($request->cardStatus === 'active') {
                $query->where('user_status_info.card_expire_on', '>', $today);
            } elseif ($request->cardStatus === 'deactive') {
                $query->where('user_status_info.card_expire_on', '<=', $today);
            }
        }
        if ($request->filled('searchPhone')) {
            $query->where('user_info.mobilephone', 'like', '%' . $request->searchPhone . '%');
        }
        if ($request->filled('searchCNIC')) {
            $query->where('user_info.nic', 'like', '%' . $request->searchCNIC . '%');
        }


        $totalRecords = $query->count();
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($subQuery) use ($searchValue) {
                $subQuery->where('user_info.username', 'like', '%' . $searchValue . '%')
                    ->orWhere('user_info.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('user_info.status', 'like', '%' . $searchValue . '%');
            });
        }

        $totalFiltered = $query->count();
        if ($request->has('order')) {
            $columns        = ['user_info.id', 'user_info.username', 'user_info.email', 'user_info.status'];
            $orderColumn    = $columns[$request->input('order.0.column')];
            $orderDirection = $request->input('order.0.dir');
            $query->orderBy($orderColumn, $orderDirection);
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get([
            'user_info.id',
            'user_info.username',
            'user_info.email',
            'user_info.status',
            'user_ip_status.ip as ip_address',
        ]);

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ]);
    }


}
