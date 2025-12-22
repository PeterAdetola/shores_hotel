<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    // View log file
    public function show($filename = 'laravel.log', Request $request)
    {
        // Security check
        if (!$this->isAuthorized()) {
            return $this->unauthorizedResponse();
        }

        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return response()->json([
                'error' => 'Log file not found: ' . $filename
            ], 404);
        }

        $logs = File::get($logPath);
        $logArray = explode("\n", $logs);

        // Reverse logs if requested
        $order = $request->get('order', 'newest-first');
        if ($order === 'newest-first') {
            $logArray = array_reverse($logArray);
        }

        // Remove empty lines
        $logArray = array_filter($logArray, function($line) {
            return !empty(trim($line));
        });
        $logArray = array_values($logArray);

        // Limit lines if requested
        $limit = $request->get('limit', 1000);
        if ($limit > 0 && count($logArray) > $limit) {
            $logArray = array_slice($logArray, 0, $limit);
        }

        return response()->json([
            'filename' => $filename,
            'file_size' => $this->formatBytes(File::size($logPath)),
            'last_modified' => date('Y-m-d H:i:s', File::lastModified($logPath)),
            'order' => $order,
            'total_lines' => count(explode("\n", $logs)),
            'displayed_lines' => count($logArray),
            'limit' => $limit,
            'content' => implode("\n", $logArray),
            'lines' => count($logArray)
        ]);
    }

    // Tail last N lines (like tail -f)
    public function tail($lines = 100, $filename = 'laravel.log')
    {
        if (!$this->isAuthorized()) {
            return $this->unauthorizedResponse();
        }

        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        $tail = $this->tailCustom($logPath, $lines);

        return response()->json([
            'filename' => $filename,
            'requested_lines' => (int)$lines,
            'returned_lines' => substr_count($tail, "\n"),
            'content' => $tail
        ]);
    }

    // List all log files
    public function index()
    {
        if (!$this->isAuthorized()) {
            return $this->unauthorizedResponse();
        }

        $logFiles = [];
        $logPath = storage_path('logs/');

        if (File::exists($logPath)) {
            $files = File::files($logPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'filename' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'last_modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        'path' => $file->getPathname()
                    ];
                }
            }
        }

        return response()->json([
            'log_files' => $logFiles,
            'total_files' => count($logFiles),
            'log_directory' => $logPath
        ]);
    }

    // Clear log file
    public function clear($filename = 'laravel.log')
    {
        if (!$this->isAuthorized()) {
            return $this->unauthorizedResponse();
        }

        $logPath = storage_path('logs/' . $filename);

        if (File::exists($logPath)) {
            File::put($logPath, '');

            return response()->json([
                'success' => true,
                'message' => 'Log file cleared: ' . $filename,
                'cleared_at' => now()->toDateTimeString()
            ]);
        }

        return response()->json([
            'error' => 'Log file not found'
        ], 404);
    }

    // HTML view for browser
    public function view($filename = 'laravel.log', Request $request)
    {
        if (!$this->isAuthorized()) {
            abort(403, 'Unauthorized');
        }

        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            abort(404, 'Log file not found');
        }

        // Get page from request or default to 1
        $page = $request->get('page', 1);
        $perPage = 100; // Lines per page
        $order = $request->get('order', 'newest-first'); // 'newest-first' or 'oldest-first'

        $logs = File::get($logPath);
        $logArray = explode("\n", $logs);
        $totalLines = count($logArray);

        // Remove empty lines
        $logArray = array_filter($logArray, function($line) {
            return !empty(trim($line));
        });

        // Re-index array
        $logArray = array_values($logArray);

        // Reverse if newest first
        if ($order === 'newest-first') {
            $logArray = array_reverse($logArray);
        }

        // Paginate
        $totalPages = ceil(count($logArray) / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedLogs = array_slice($logArray, $offset, $perPage);

        // Colorize logs
        $colorizedLogs = [];
        foreach ($paginatedLogs as $log) {
            $colorizedLogs[] = $this->colorizeLogs($log);
        }

        return view('logs.view-log', [
            'logs' => implode("\n", $colorizedLogs),
            'filename' => $filename,
            'fileSize' => $this->formatBytes(File::size($logPath)),
            'lastModified' => date('Y-m-d H:i:s', File::lastModified($logPath)),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_lines' => $totalLines,
                'per_page' => $perPage,
                'order' => $order,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
            ]
        ]);
    }

    // Download log file
    public function download($filename = 'laravel.log')
    {
        if (!$this->isAuthorized()) {
            abort(403, 'Unauthorized');
        }

        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            abort(404, 'Log file not found');
        }

        return response()->download($logPath, $filename . '-' . date('Y-m-d') . '.log');
    }

    // PRIVATE HELPER METHODS

    private function isAuthorized()
    {
        // Allow in local environment
        if (app()->environment('local')) {
            return true;
        }

        // Check for secret token
        $allowedToken = env('LOG_VIEWER_TOKEN', 'your-secret-log-token');
        if (request()->has('token') && request()->get('token') === $allowedToken) {
            return true;
        }

        // Check if user is authenticated and has permission
        if (auth()->check() && auth()->user()->can('view-logs')) {
            return true;
        }

        return false;
    }

    private function unauthorizedResponse()
    {
        return response()->json([
            'error' => 'Unauthorized access to logs',
            'environment' => app()->environment(),
            'hint' => app()->isLocal()
                ? 'You should be able to access this in local environment'
                : 'Use ?token=your-secret-token or login with proper permissions'
        ], 403);
    }

    private function tailCustom($filepath, $lines = 100)
    {
        // Open file
        $f = fopen($filepath, "rb");
        if ($f === false) return "";

        // Jump to last character
        fseek($f, -1, SEEK_END);

        // Read line by line backward
        $buffer = "";
        $count = 0;

        while ($count < $lines) {
            // Get current position
            $currentPos = ftell($f);

            // If we've reached the beginning, stop
            if ($currentPos == 0) break;

            // Move back one character
            fseek($f, -1, SEEK_CUR);

            // Read character
            $char = fgetc($f);

            // Move back again
            fseek($f, -1, SEEK_CUR);

            // Prepend character to buffer
            $buffer = $char . $buffer;

            // If we've found a new line, increment count
            if ($char === "\n") {
                $count++;
            }
        }

        fclose($f);

        return $buffer;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function colorizeLogs($logs)
    {
        // Add HTML formatting for better readability
        $logs = htmlspecialchars($logs);

        // Colorize different log levels
        $logs = preg_replace('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', '<span class="text-muted">[$1]</span>', $logs);
        $logs = preg_replace('/\.(\w+):/', '<span class="text-info">.$1</span>:', $logs);
        $logs = preg_replace('/(DEBUG|INFO|NOTICE)/', '<span class="badge bg-info">$1</span>', $logs);
        $logs = preg_replace('/WARNING/', '<span class="badge bg-warning">WARNING</span>', $logs);
        $logs = preg_replace('/ERROR/', '<span class="badge bg-danger">ERROR</span>', $logs);
        $logs = preg_replace('/CRITICAL|ALERT|EMERGENCY/', '<span class="badge bg-dark">$1</span>', $logs);
        $logs = preg_replace('/Stack trace:/', '<strong>Stack trace:</strong>', $logs);
        $logs = preg_replace('/#\d+/', '<span class="text-success">$0</span>', $logs);

        return nl2br($logs);
    }
}
