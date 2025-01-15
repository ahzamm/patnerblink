<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\model\ActionLog;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class ActionLogController extends Controller
{
    public function index()
    {
        return view('admin.action_logs.index');
    }

    public function getActionLogs(Request $request)
    {
        if ($request->ajax()) {
            $query = ActionLog::query();

            if ($request->filled('performed_by')) {
                $query->where('performed_by', 'like', '%' . $request->performed_by . '%');
            }

            if ($request->filled('date_range')) {
                [$start, $end] = explode(' to ', $request->date_range);
                $query->whereBetween('created_at', [$start, $end]);
            }

            if ($request->filled('operation')) {
                $query->where('operation', $request->operation);
            }

            $data = $query->select(['id', 'model', 'beforeupdate', 'afterupdate', 'operation', 'performed_by', 'created_at'])->get();

            return DataTables::of($data)
                ->editColumn('operation', function ($row) {
                    return '<strong>' . ucfirst($row->operation) . '</strong><br><small>' . \Carbon\Carbon::parse($row->created_at)->format('F j, Y, g:i a') . '</small>';
                })
                ->editColumn('beforeupdate', function ($row) {
                    $before = json_decode($row->beforeupdate, true);
                    return $this->formatUpdateData($before);
                })
                ->editColumn('afterupdate', function ($row) {
                    $after = json_decode($row->afterupdate, true);
                    return $this->formatUpdateData($after);
                })
                ->rawColumns(['beforeupdate', 'afterupdate', 'operation'])
                ->make(true);
        }
    }

    private function formatUpdateData($data)
    {
        if (is_array($data)) {
            $output = '<ul>';
            foreach ($data as $key => $value) {
                $output .= '<li><strong>' . ucfirst($key) . ':</strong> ' . htmlspecialchars($value) . '</li>';
            }
            $output .= '</ul>';
            return $output;
        }
        return '<em>No Data</em>';
    }
}
