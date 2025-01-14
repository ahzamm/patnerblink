<?php

namespace App\Observers;

use App\model\ActionLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Auth;

class GlobalModelObserver
{
    public function __construct()
    {
        \Log::info('GlobalModelObserver has been instantiated.');
    }

    public function created(Model $model)
    {
        if ($model instanceof ActionLog) {
            return;
        }
        ActionLog::Create([
            'model' => get_class($model),
            'afterupdate' => json_encode($model->getDirty()),
            'operation' => 'Create',
            'performed_by' => Auth::user()->username ?? 'N/A',
        ]);
    }
    public function updating(Model $model)
    {
        \Log::info("Observer Triggered: updated event for " . get_class($model), [
            'dirty' => $model->getDirty(),
            'original' => $model->getOriginal(),
        ]);
        ActionLog::Create([
            'model' => get_class($model),
            'beforeupdate' => json_encode($model->getOriginal()),
            'afterupdate' => json_encode($model->getDirty()),
            'operation' => 'Update',
            'performed_by' => Auth::user()->username ?? 'N/A',
        ]);
    }

    public function deleting(Model $model)
    {
        ActionLog::Create([
            'model' => get_class($model),
            'beforeupdate' => json_encode($model->getOriginal()),
            'operation' => 'Delete',
            'performed_by' => Auth::id(),
        ]);
    }
}
