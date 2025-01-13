<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $modelsNamespace = 'App\\model\\';
        // $models = File::allFiles(app_path('model'));

        // foreach ($models as $model) {
        //     $relativePath = str_replace(app_path('model') . DIRECTORY_SEPARATOR, '', $model->getRealPath());
        //     $modelClass = $modelsNamespace . rtrim(str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath), '.php');

        //     if (class_exists($modelClass) && is_subclass_of($modelClass, Model::class)) {
        //         // Log model registration
        //         \Log::info("Registering observer for model: {$modelClass}");

        //         $modelClass::observe(GlobalModelObserver::class);

        //         \Log::info("Registered observer for model: {$modelClass}");
        //     }
        // }
    }
}
