<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ControlPanelController extends Controller
{
    // List of allowed commands (for security)
    private $allowedCommands = [
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'optimize:clear',
        'email:test',
        'migrate:status',
        'queue:failed',
    ];

    // Show control panel
    public function index(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.control-panel', [
            'token' => $request->get('token')
        ]);
    }

    // Execute artisan command
    public function execute(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $command = $request->input('command');

        if (empty($command)) {
            return response()->json(['error' => 'No command provided'], 400);
        }

        $baseCommand = explode(' ', $command)[0];
        if (!in_array($baseCommand, $this->allowedCommands)) {
            return response()->json(['error' => 'Command not allowed: ' . $baseCommand], 403);
        }

        try {
            Artisan::call($command);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // View logs with pagination
    public function logs(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filename = $request->input('filename', 'laravel.log');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 100);
        $order = $request->input('order', 'newest-first');

        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        // Handle download request
        if ($request->has('download')) {
            return response()->download($logPath, $filename . '-' . date('Y-m-d') . '.log');
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);

        // Remove empty lines
        $lines = array_filter($lines, function($line) {
            return !empty(trim($line));
        });

        $lines = array_values($lines);
        $totalLines = count($lines);

        if ($order === 'newest-first') {
            $lines = array_reverse($lines);
        }

        $totalPages = ceil(count($lines) / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedLines = array_slice($lines, $offset, $perPage);

        $colorizedLines = array_map(function($line) {
            return $this->colorizeLogLine($line);
        }, $paginatedLines);

        return response()->json([
            'filename' => $filename,
            'content' => implode("\n", $colorizedLines),
            'raw_content' => implode("\n", $paginatedLines),
            'pagination' => [
                'current_page' => (int)$page,
                'total_pages' => $totalPages,
                'total_lines' => $totalLines,
                'per_page' => $perPage,
                'order' => $order,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
            ],
            'file_info' => [
                'size' => File::size($logPath),
                'size_formatted' => $this->formatBytes(File::size($logPath)),
                'modified' => date('Y-m-d H:i:s', File::lastModified($logPath)),
                'lines' => $totalLines
            ]
        ]);
    }

    // Get list of log files
    public function logFiles(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logFiles = $this->getLogFiles();
        return response()->json(['log_files' => $logFiles]);
    }

    // Clear log file
    public function clearLog(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filename = $request->input('filename', 'laravel.log');
        $logPath = storage_path('logs/' . $filename);

        if (File::exists($logPath)) {
            File::put($logPath, '');
            return response()->json(['success' => true, 'message' => 'Log cleared']);
        }

        return response()->json(['error' => 'Log file not found'], 404);
    }

    // Download log file
    public function downloadLog(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filename = $request->input('filename', 'laravel.log');
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        return response()->download($logPath, $filename . '-' . date('Y-m-d') . '.log');
    }

    // Private helper methods
    private function isAuthorized(Request $request)
    {
        if (app()->environment('local')) {
            return true;
        }

        $allowedToken = config('app.control_panel_token', env('CONTROL_PANEL_TOKEN'));
        if ($request->get('token') === $allowedToken) {
            return true;
        }

        return false;
    }

    private function getLogFiles()
    {
        $logPath = storage_path('logs/');
        $files = [];

        if (File::exists($logPath)) {
            foreach (File::files($logPath) as $file) {
                if ($file->getExtension() === 'log') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }
        }

        usort($files, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });

        return $files;
    }

    private function colorizeLogLine($line)
    {
        $line = htmlspecialchars($line);

        $patterns = [
            '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/' => '<span class="log-timestamp">[$1]</span>',
            '/\.(DEBUG|INFO|NOTICE)\./' => '<span class="log-level badge bg-info">$1</span>',
            '/\.WARNING\./' => '<span class="log-level badge bg-warning">WARNING</span>',
            '/\.ERROR\./' => '<span class="log-level badge bg-danger">ERROR</span>',
            '/\.(CRITICAL|ALERT|EMERGENCY)\./' => '<span class="log-level badge bg-dark">$1</span>',
            '/(Stack trace:)/' => '<strong class="log-stack-trace">$1</strong>',
            '/#(\d+)/' => '<span class="log-line-number">#$1</span>',
            '/(\/[^\s]+\.php)/' => '<span class="log-file-path">$1</span>',
            '/(with message \'[^\']+\')/' => '<span class="log-error-message">$1</span>',
            '/(SQL:.+)/' => '<span class="log-sql">$1</span>',
            '/(https?:\/\/[^\s]+)/' => '<a href="$1" class="log-url" target="_blank">$1</a>',
            '/\] (\w+)\./' => '] <span class="log-channel">$1</span>.',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $line = preg_replace($pattern, $replacement, $line);
        }

        return $line;
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
}
