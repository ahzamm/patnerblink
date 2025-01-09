<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\model\Users\Ticker;
class DashboardController extends Controller
{
    public function index()
    {
        $activeUser = 0;
        $disable_user = 0;
        $onlineUser = 0;
        $onlinePercentage = 0;

        return view('admin.dashboards', [
            'onlineUser' => $onlineUser,

            'disable_user' => $disable_user,
            'activeUser' => $activeUser,
            'onlinePercentage' => $onlinePercentage,
        ]);
    }

    public function create_headline()
    {
        $ticker = Ticker::first();

        return view('admin.tickers.create', compact('ticker'));
    }
    public function store_headline(Request $request)
    {
        $get_ticker = Ticker::first();

        $update = [
            'english_content' => $request['english_content'],
            'urdu_content' => $request['urdu_content'],
            'announcement_content' => $request['announcement_content'],
            'creation_by' => Auth::user()->username,
        ];

        Ticker::where('id', $get_ticker->id)->update($update);
        return redirect()->route('admin.headline')->with('success', 'Headline Updated Successfully');
    }
}
