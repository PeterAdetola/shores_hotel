<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CacheClearController extends Controller
{
    public function clearAll()
    {
        $results = [];
        $errors  = [];

        // Clear config cache
        try {
            $configPath = bootstrap_path('cache/config.php');
            if (File::exists($configPath)) {
                File::delete($configPath);
                $results[] = 'Config cache cleared';
            } else {
                $results[] = 'Config cache: nothing to clear';
            }
        } catch (\Exception $e) {
            $errors[] = 'Config cache error: ' . $e->getMessage();
        }

        // Clear route cache
        try {
            $routePath = bootstrap_path('cache/routes-v7.php');
            if (File::exists($routePath)) {
                File::delete($routePath);
                $results[] = 'Route cache cleared';
            } else {
                // Try older Laravel route cache filename
                $routePathOld = bootstrap_path('cache/routes.php');
                if (File::exists($routePathOld)) {
                    File::delete($routePathOld);
                    $results[] = 'Route cache cleared';
                } else {
                    $results[] = 'Route cache: nothing to clear';
                }
            }
        } catch (\Exception $e) {
            $errors[] = 'Route cache error: ' . $e->getMessage();
        }

        // Clear compiled views
        try {
            $viewPath = storage_path('framework/views');
            $files    = File::files($viewPath);
            $count    = 0;
            foreach ($files as $file) {
                File::delete($file);
                $count++;
            }
            $results[] = "View cache cleared ({$count} files)";
        } catch (\Exception $e) {
            $errors[] = 'View cache error: ' . $e->getMessage();
        }

        // Clear application cache (file-based)
        try {
            $cachePath = storage_path('framework/cache/data');
            if (File::isDirectory($cachePath)) {
                $files = File::allFiles($cachePath);
                $count = 0;
                foreach ($files as $file) {
                    File::delete($file);
                    $count++;
                }
                $results[] = "App cache cleared ({$count} files)";
            } else {
                $results[] = 'App cache: nothing to clear';
            }
        } catch (\Exception $e) {
            $errors[] = 'App cache error: ' . $e->getMessage();
        }

        // Clear sessions (file-based)
        try {
            $sessionPath = storage_path('framework/sessions');
            if (File::isDirectory($sessionPath)) {
                $files = File::files($sessionPath);
                $count = 0;
                foreach ($files as $file) {
                    File::delete($file);
                    $count++;
                }
                $results[] = "Sessions cleared ({$count} files)";
            }
        } catch (\Exception $e) {
            $errors[] = 'Session clear error: ' . $e->getMessage();
        }

        return response()->json([
            'success'  => empty($errors),
            'cleared'  => $results,
            'errors'   => $errors,
            'message'  => empty($errors) ? 'All caches cleared successfully!' : 'Completed with some errors',
        ]);
    }
}
