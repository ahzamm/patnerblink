<?php

// ActionLogController.php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\model\ActionLog;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class ActionLogController extends Controller
{
    // Function to return the view
    public function index()
    {
        return view('admin.action_logs.index');
    }

    // Function for server-side DataTables
    public function getActionLogs(Request $request)
    {
        if ($request->ajax()) {
            $data = ActionLog::query();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary">View</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
