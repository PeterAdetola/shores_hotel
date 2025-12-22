<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheClearController extends Controller
{
    public function clearAll()
    {
        // Security check - only allow in local environment or with proper authentication
        if (app()->environment('local') || request()->has('secret_token')) {
            try {
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear'); // Optional: clear view cache too

                return response()->json([
                    'success' => true,
                    'message' => 'All caches cleared successfully!',
                    'environment' => app()->environment()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error clearing cache: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ], 403);
    }
}
