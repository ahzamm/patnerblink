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
        $query = UserInfo::query()->where('user_info.status','user');

        $query->leftJoin('user_status_info', 'user_info.username', '=', 'user_status_info.username')
            ->leftJoin('user_ip_status', 'user_info.username', '=', 'user_ip_status.username')
            ->leftJoin('user_verification', 'user_info.username', '=', 'user_verification.username')
            ->leftJoin('disabled_users', 'user_info.username', '=', 'disabled_users.username');

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
        if ($request->filled('lastChargeOn')) {
            $query->whereDate('user_status_info.card_charge_on', '=', $request->lastChargeOn);
        }
        if ($request->filled('lastExpireOn')) {
            $query->whereDate('user_status_info.card_expire_on', '=', $request->lastExpireOn);
        }
        if ($request->filled('searchIP')) {
            $query->where('user_ip_status.ip', 'like', '%' . $request->searchIP . '%');
        }
        if ($request->filled('verifiedBy')) {
            $verifiedBy = $request->verifiedBy;
            if ($verifiedBy === 'CNIC') {
                $query->where('user_verification.cnic', 'verified');
            } elseif ($verifiedBy === 'Mobile') {
                $query->where('user_verification.mobile', 'verified');
            } elseif ($verifiedBy === 'All') {
                $query->where('user_verification.cnic', 'verified')
                    ->where('user_verification.mobile', 'verified');
            }
        }
        if ($request->filled('userStatus')) {
            $query->where('disabled_users.status', $request->userStatus);
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
