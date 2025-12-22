<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Log Viewer - {{ $filename }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .log-container { background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; }
        .log-line { font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.4; }
        .badge { font-size: 0.7em; }
        .text-muted { color: #6c757d !important; }
        .text-info { color: #17a2b8 !important; }
        .text-success { color: #28a745 !important; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
        .pagination-controls { margin: 15px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Log Viewer: {{ $filename }}</h5>
            <div>
                <!-- Order toggle button -->
                @php
                    $currentOrder = $pagination['order'] ?? 'newest-first';
                    $toggleOrder = $currentOrder === 'newest-first' ? 'oldest-first' : 'newest-first';
                @endphp
                <a href="?order={{ $toggleOrder }}&page={{ $pagination['current_page'] ?? 1 }}&token={{ request()->get('token') }}"
                   class="btn btn-sm btn-outline-secondary">
                    {{ $currentOrder === 'newest-first' ? '↑ Oldest First' : '↓ Newest First' }}
                </a>

                <a href="{{ url()->current() }}?order={{ $currentOrder }}&token={{ request()->get('token') }}"
                   class="btn btn-sm btn-secondary">Refresh</a>
                <a href="{{ route('logs.download', ['filename' => $filename, 'token' => request()->get('token')]) }}"
                   class="btn btn-sm btn-primary">Download</a>
                <a href="{{ route('logs.clear', ['filename' => $filename, 'token' => request()->get('token')]) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Clear this log file?')">Clear Log</a>
            </div>
        </div>
        <div class="card-body">
            <!-- File info -->
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <strong>File Size:</strong> {{ $fileSize }}
                    </div>
                    <div class="col-md-3">
                        <strong>Last Modified:</strong> {{ $lastModified }}
                    </div>
                    <div class="col-md-3">
                        <strong>Environment:</strong> {{ app()->environment() }}
                    </div>
                    <div class="col-md-3">
                        <strong>Order:</strong>
                        <span class="badge bg-info">
                                {{ $pagination['order'] ?? 'newest-first' }}
                            </span>
                    </div>
                </div>
            </div>

            <!-- Pagination controls -->
            @if(isset($pagination) && $pagination['total_pages'] > 1)
                <div class="pagination-controls">
                    <nav aria-label="Log navigation">
                        <ul class="pagination justify-content-center">
                            @if($pagination['has_previous'])
                                <li class="page-item">
                                    <a class="page-link"
                                       href="?page={{ $pagination['current_page'] - 1 }}&order={{ $pagination['order'] }}&token={{ request()->get('token') }}">
                                        Previous
                                    </a>
                                </li>
                            @endif

                            <li class="page-item disabled">
                                <span class="page-link">
                                    Page {{ $pagination['current_page'] }} of {{ $pagination['total_pages'] }}
                                    ({{ $pagination['total_lines'] }} total lines)
                                </span>
                            </li>

                            @if($pagination['has_next'])
                                <li class="page-item">
                                    <a class="page-link"
                                       href="?page={{ $pagination['current_page'] + 1 }}&order={{ $pagination['order'] }}&token={{ request()->get('token') }}">
                                        Next
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif

            <!-- Log content -->
            <div class="log-container">
                <div class="log-line">
                    {!! $logs !!}
                </div>
            </div>

            @if(empty(trim(strip_tags($logs))))
                <div class="alert alert-info mt-3">
                    Log file is empty
                </div>
            @endif

            <!-- Bottom pagination -->
            @if(isset($pagination) && $pagination['total_pages'] > 1)
                <div class="pagination-controls mt-3">
                    <nav aria-label="Log navigation">
                        <ul class="pagination justify-content-center">
                            @if($pagination['has_previous'])
                                <li class="page-item">
                                    <a class="page-link"
                                       href="?page={{ $pagination['current_page'] - 1 }}&order={{ $pagination['order'] }}&token={{ request()->get('token') }}">
                                        ← Previous Page
                                    </a>
                                </li>
                            @endif

                            @if($pagination['has_next'])
                                <li class="page-item ms-auto">
                                    <a class="page-link"
                                       href="?page={{ $pagination['current_page'] + 1 }}&order={{ $pagination['order'] }}&token={{ request()->get('token') }}">
                                        Next Page →
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('logs.index', ['token' => request()->get('token')]) }}" class="btn btn-link">
            ← Back to All Log Files
        </a>
    </div>
</div>
</body>
</html>
