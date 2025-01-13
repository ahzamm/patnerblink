<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clearAll()
    {
        try {
            // Run Artisan commands
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');
            Artisan::call('view:cache');

            // Run Composer dump-autoload
            $output = shell_exec('composer dump-autoload');

            return response()->json([
                'success' => true,
                'message' => 'All caches and configurations cleared successfully!',
                'composer_output' => $output, // Optional: Include Composer output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ]);
        }
    }
}
