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
        $query = UserInfo::query()
                    ->select([
                        'user_info.id',
                        'user_info.username',
                        'user_info.firstname',
                        'user_info.lastname',
                        'user_info.name',
                        'user_info.email',
                        'user_info.status',
                        'user_info.mobilephone',
                        'user_info.nic',
                        'user_info.mac_address',
                        'user_info.address',
                        'user_info.city',
                        'user_info.state',
                        'user_info.passport',
                        'user_ip_status.ip as ip_address',
                        'user_status_info.card_charge_on',
                        'user_status_info.card_expire_on',
                        'user_verification.cnic as verified_cnic',
                        'user_verification.mobile as verified_mobile',
                        'disabled_users.status as disabled_status',
                    ])
                    ->distinct()
                    ->where('user_info.status', 'user')
                    ->leftJoin('user_status_info', 'user_info.username', '=', 'user_status_info.username')
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
        if ($request->filled('managerProfile')) {
            $query->where('user_info.name', $request->managerProfile);
        }
        if ($request->filled('resellerProfile')) {
            $query->where('user_info.name', $request->resellerProfile);
        }
        if ($request->filled('contractorProfile')) {
            $query->where('user_info.name', $request->contractorProfile);
        }
        if ($request->filled('subdealerProfile')) {
            $query->where('user_info.name', $request->subdealerProfile);
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
        if ($request->filled('searchMAC')) {
            $query->where('user_info.mac_address', 'like', '%' . $request->searchMAC . '%');
        }
        if ($request->filled('searchEmail')) {
            $query->where('user_info.email', $request->searchEmail);
        }
        if ($request->filled('searchPassport')) {
            $query->where('user_info.passport', $request->searchPassport);
        }
        if ($request->filled('searchAddress')) {
            $query->where('user_info.address', 'like', '%' . $request->searchAddress . '%');
        }
        if ($request->filled('searchCityState')) {
            $query->where('user_info.city', $request->searchCityState)->orWhere('user_info.state', $request->searchCityState);
        }
        if ($request->filled('searchCreation')) {
            $query->whereDate('user_info.creationdate', $request->searchCreation);
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
        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ]);
    }

    public function getUserDetails($id)
    {
        $user = UserInfo::query()
            ->select([
                'user_info.id',
                'user_info.username',
                'user_info.firstname',
                'user_info.lastname',
                'user_info.email',
                'user_info.status',
                'user_info.address',
                'user_info.city',
                'user_info.state',
                'user_info.mobilephone',
                'user_info.mac_address',
                'user_info.nic',
                'user_info.passport',
                'user_info.creationdate',
                'user_ip_status.ip as ip_address',
                'user_status_info.card_charge_on',
                'user_status_info.card_expire_on',
                'user_verification.cnic as verified_cnic',
                'user_verification.mobile as verified_mobile',
                'disabled_users.status as disabled_status',
            ])
            ->leftJoin('user_ip_status', 'user_info.username', '=', 'user_ip_status.username')
            ->leftJoin('user_status_info', 'user_info.username', '=', 'user_status_info.username')
            ->leftJoin('user_verification', 'user_info.username', '=', 'user_verification.username')
            ->leftJoin('disabled_users', 'user_info.username', '=', 'disabled_users.username')
            ->where('user_info.id', $id)
            ->firstOrFail();

        return response()->json($user);
    }
}
