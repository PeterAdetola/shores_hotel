<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --dark: #1a1a2e;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .control-panel {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-top: 30px;
            margin-bottom: 30px;
        }

        /* Artisan Console Styles */
        .console-output {
            background: #1e1e1e;
            color: #d4d4d4;
            font-family: 'Consolas', 'Monaco', monospace;
            padding: 15px;
            border-radius: 8px;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .tab-content {
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Enhanced Log Viewer Styles */
        .log-content {
            background: #1e1e1e;
            color: #d4d4d4;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.5;
            padding: 20px;
            border-radius: 0;
            max-height: 600px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .log-content .log-timestamp {
            color: #6c757d;
            font-weight: bold;
        }

        .log-content .log-level {
            font-size: 0.7em;
            padding: 2px 6px;
            margin: 0 4px;
            border-radius: 3px;
        }

        .log-content .badge.bg-info {
            background-color: #17a2b8 !important;
        }

        .log-content .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000;
        }

        .log-content .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .log-content .badge.bg-dark {
            background-color: #343a40 !important;
        }

        .log-content .log-stack-trace {
            color: #ff6b6b;
            font-weight: bold;
        }

        .log-content .log-line-number {
            color: #28a745;
            font-weight: bold;
        }

        .log-content .log-file-path {
            color: #4ec9b0;
        }

        .log-content .log-error-message {
            color: #ffa07a;
            font-style: italic;
        }

        .log-content .log-sql {
            color: #d4d4d4;
            background: #2d2d2d;
            padding: 2px 5px;
            border-radius: 3px;
            border-left: 3px solid #007bff;
        }

        .log-content .log-url {
            color: #569cd6;
            text-decoration: underline;
        }

        .log-content .log-channel {
            color: #ce9178;
            font-weight: bold;
        }

        .log-content .log-line {
            padding: 2px 0;
            border-bottom: 1px solid #2d2d2d;
            transition: background-color 0.2s;
        }

        .log-content .log-line:hover {
            background-color: #2d2d2d;
        }

        /* Raw log view */
        .raw-log {
            background: #f8f9fa;
            color: #212529;
            font-family: monospace;
            white-space: pre;
            overflow-x: auto;
        }

        /* Pagination styles */
        .log-pagination {
            font-size: 0.9em;
        }

        .log-pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Log file list */
        .log-file-item {
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .log-file-item:hover {
            background-color: #f8f9fa;
            border-left-color: #4361ee;
        }

        .log-file-item.active {
            background-color: #e3f2fd;
            border-left-color: #4361ee;
            font-weight: bold;
        }

        .log-file-size {
            font-size: 0.8em;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="control-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-terminal"></i> Laravel Control Panel</h2>
                    <div class="alert alert-warning py-1 px-3 mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <small>Environment: {{ app()->environment() }}</small>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="artisan-tab" data-bs-toggle="tab" data-bs-target="#artisan">
                            <i class="fas fa-terminal me-1"></i> Artisan Console
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs">
                            <i class="fas fa-file-alt me-1"></i> Log Viewer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#tools">
                            <i class="fas fa-tools me-1"></i> Quick Tools
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">

                    <!-- Artisan Console -->
                    <div class="tab-pane fade show active" id="artisan">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Run Artisan Command</h5>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">$ php artisan</span>
                                    <input type="text" id="artisanCommand" class="form-control" placeholder="cache:clear">
                                    <button class="btn btn-primary" onclick="runCommand()">
                                        <i class="fas fa-play"></i> Execute
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Quick commands:</small>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('cache:clear')">
                                            Clear Cache
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('config:clear')">
                                            Clear Config
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('route:clear')">
                                            Clear Routes
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('view:clear')">
                                            Clear Views
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('optimize:clear')">
                                            Optimize Clear
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setCommand('email:test --send')">
                                            Test Email
                                        </button>
                                    </div>
                                </div>

                                <h6>Output:</h6>
                                <div id="commandOutput" class="console-output">
                                    <span class="text-muted">Command output will appear here...</span>
                                </div>
                                <div class="mt-3">
                                    <button id="copyOutputBtn" class="btn btn-outline-secondary btn-sm">
                                        <i class="far fa-copy me-1"></i> Copy
                                    </button>
                                    <button id="clearOutputBtn" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Viewer -->
                    <div class="tab-pane fade" id="logs">
                        <div class="row">
                            <!-- Log Files Sidebar -->
                            <div class="col-md-3">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-alt me-1"></i> Log Files
                                            <button class="btn btn-sm btn-outline-secondary float-end" onclick="refreshLogFiles()">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="logFilesList" style="max-height: 500px; overflow-y: auto;">
                                            <!-- Log files will be loaded here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Log Controls -->
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Log Settings</h6>

                                        <div class="mb-3">
                                            <label class="form-label">Lines per page:</label>
                                            <select id="logPerPage" class="form-select form-select-sm" onchange="changeLogPerPage()">
                                                <option value="50">50 lines</option>
                                                <option value="100" selected>100 lines</option>
                                                <option value="200">200 lines</option>
                                                <option value="500">500 lines</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Sort order:</label>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="logOrder" id="orderNewest"
                                                       autocomplete="off" checked onchange="changeLogOrder('newest-first')">
                                                <label class="btn btn-outline-primary btn-sm" for="orderNewest">
                                                    <i class="fas fa-arrow-down"></i> Newest First
                                                </label>

                                                <input type="radio" class="btn-check" name="logOrder" id="orderOldest"
                                                       autocomplete="off" onchange="changeLogOrder('oldest-first')">
                                                <label class="btn btn-outline-primary btn-sm" for="orderOldest">
                                                    <i class="fas fa-arrow-up"></i> Oldest First
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-sm btn-outline-success" onclick="downloadCurrentLog()">
                                                <i class="fas fa-download me-1"></i> Download Log
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearCurrentLog()">
                                                <i class="fas fa-trash me-1"></i> Clear Log
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Log File Info -->
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">File Information</h6>
                                        <div id="logFileInfo">
                                            <small class="text-muted">Select a log file</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Log Content Area -->
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0" id="logFileName">
                                                <i class="fas fa-file-code me-2"></i>
                                                <span id="currentLogFileName">Select a log file</span>
                                            </h5>
                                            <small id="logFileDetails" class="text-muted"></small>
                                        </div>

                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="refreshCurrentLog()">
                                                <i class="fas fa-sync-alt"></i> Refresh
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="copyLogContent()">
                                                <i class="far fa-copy"></i> Copy
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleRawLog()">
                                                <i class="fas fa-code"></i> Raw
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Pagination Top -->
                                    <div class="card-body py-2 border-bottom">
                                        <div id="logPaginationTop" class="d-flex justify-content-between align-items-center">
                                            <!-- Pagination will be loaded here -->
                                        </div>
                                    </div>

                                    <!-- Log Content -->
                                    <div class="card-body p-0">
                                        <div id="logContent" class="log-content">
                                            <div class="text-center py-5 text-muted">
                                                <i class="fas fa-file-alt fa-3x mb-3"></i><br>
                                                Select a log file from the left panel to view its content
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pagination Bottom -->
                                    <div class="card-footer">
                                        <div id="logPaginationBottom" class="d-flex justify-content-between align-items-center">
                                            <!-- Pagination will be loaded here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Log Statistics -->
                                <div class="card mt-3">
                                    <div class="card-body py-2">
                                        <div class="row text-center">
                                            <div class="col">
                                                <small>
                                                    <i class="fas fa-info-circle text-info"></i>
                                                    <span id="logStatsInfo" class="ms-1">0</span>
                                                </small>
                                            </div>
                                            <div class="col">
                                                <small>
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    <span id="logStatsWarning" class="ms-1">0</span>
                                                </small>
                                            </div>
                                            <div class="col">
                                                <small>
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    <span id="logStatsError" class="ms-1">0</span>
                                                </small>
                                            </div>
                                            <div class="col">
                                                <small>
                                                    <i class="fas fa-bug text-dark"></i>
                                                    <span id="logStatsCritical" class="ms-1">0</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tools -->
                    <div class="tab-pane fade" id="tools">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fas fa-broom fa-3x text-primary mb-3"></i>
                                        <h5>Clear All Caches</h5>
                                        <p class="text-muted">Clears config, route, view, and application caches</p>
                                        <button class="btn btn-primary" onclick="runCommandSequence([
                                                'cache:clear',
                                                'config:clear',
                                                'route:clear',
                                                'view:clear'
                                            ])">
                                            Run All
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fas fa-envelope fa-3x text-success mb-3"></i>
                                        <h5>Test Email</h5>
                                        <p class="text-muted">Test your email configuration</p>
                                        <button class="btn btn-success" onclick="runCommand('email:test --send')">
                                            Send Test Email
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 text-center">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt"></i>
                        Access Token: {{ request()->get('token') ? 'Present' : 'Missing' }} |
                        PHP: {{ PHP_VERSION }} |
                        Laravel: {{ app()->version() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global variables
    const token = '{{ $token }}';

    // Log Viewer State
    let logViewerState = {
        currentFile: null,
        currentPage: 1,
        perPage: 100,
        order: 'newest-first',
        rawView: false
    };

    // ========== ARTISAN CONSOLE FUNCTIONS ==========
    function setCommand(cmd) {
        document.getElementById('artisanCommand').value = cmd;
    }

    async function runCommand(command = null) {
        if (!command) {
            command = document.getElementById('artisanCommand').value.trim();
        }

        if (!command) {
            showAlert('Please enter a command', 'warning');
            return;
        }

        const outputDiv = document.getElementById('commandOutput');
        outputDiv.innerHTML = '<span class="text-info">⏳ Running command...</span>';

        try {
            const response = await fetch('/admin/control-panel/execute?token=' + token, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ command: command })
            });

            const result = await response.json();

            if (result.success) {
                outputDiv.innerHTML = '<span class="text-success">✅ Command executed successfully</span>\n' + result.output;
            } else {
                outputDiv.innerHTML = '<span class="text-danger">❌ Error: ' + result.error + '</span>';
            }
        } catch (error) {
            outputDiv.innerHTML = '<span class="text-danger">❌ Network error: ' + error.message + '</span>';
        }
    }

    async function runCommandSequence(commands) {
        const outputDiv = document.getElementById('commandOutput');
        outputDiv.innerHTML = '<span class="text-info">⏳ Running commands in sequence...</span>';

        let allOutput = '';

        for (const command of commands) {
            try {
                const response = await fetch('/admin/control-panel/execute?token=' + token, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ command: command })
                });

                const result = await response.json();

                if (result.success) {
                    allOutput += '$ php artisan ' + command + '\n';
                    allOutput += result.output + '\n\n';
                } else {
                    allOutput += '❌ Error in ' + command + ': ' + result.error + '\n\n';
                }
            } catch (error) {
                allOutput += '❌ Network error in ' + command + ': ' + error.message + '\n\n';
            }
        }

        outputDiv.innerHTML = allOutput;
    }

    // Copy output button
    document.getElementById('copyOutputBtn').addEventListener('click', function() {
        const text = document.getElementById('commandOutput').textContent;
        navigator.clipboard.writeText(text);
        showAlert('Output copied to clipboard', 'success');
    });

    // Clear output button
    document.getElementById('clearOutputBtn').addEventListener('click', function() {
        document.getElementById('commandOutput').innerHTML = '<span class="text-muted">Output cleared...</span>';
    });

    // ========== LOG VIEWER FUNCTIONS ==========
    // Load log files
    async function loadLogFiles() {
        try {
            const response = await fetch('/admin/control-panel/log-files?token=' + token);
            const result = await response.json();

            if (result.log_files) {
                renderLogFiles(result.log_files);
                if (result.log_files.length > 0 && !logViewerState.currentFile) {
                    viewLogFile(result.log_files[0].name);
                }
            } else if (result.error) {
                showAlert('Error loading log files: ' + result.error, 'danger');
            }
        } catch (error) {
            showAlert('Error loading log files: ' + error.message, 'danger');
        }
    }

    function renderLogFiles(files) {
        const container = document.getElementById('logFilesList');
        container.innerHTML = '';

        files.forEach(file => {
            const div = document.createElement('div');
            div.className = `log-file-item list-group-item list-group-item-action d-flex justify-content-between align-items-center ${file.name === logViewerState.currentFile ? 'active' : ''}`;
            div.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt me-2 ${file.name.includes('error') ? 'text-danger' : file.name.includes('debug') ? 'text-info' : 'text-primary'}"></i>
                    <div>
                        <div class="fw-bold">${file.name}</div>
                        <small class="text-muted">${file.modified}</small>
                    </div>
                </div>
                <span class="badge bg-secondary log-file-size">${file.size}</span>
            `;
            div.onclick = () => viewLogFile(file.name);
            container.appendChild(div);
        });
    }

    // View log file
    async function viewLogFile(filename, page = 1) {
        logViewerState.currentFile = filename;
        logViewerState.currentPage = page;

        // Update UI
        document.getElementById('currentLogFileName').textContent = filename;

        // Load log content
        await loadLogContent();
    }

    // Load log content
    async function loadLogContent() {
        if (!logViewerState.currentFile) return;

        const logContentDiv = document.getElementById('logContent');
        const logFileNameSpan = document.getElementById('currentLogFileName');
        const logFileDetailsSpan = document.getElementById('logFileDetails');

        logContentDiv.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading log file...</div>';
        logFileNameSpan.textContent = logViewerState.currentFile;

        try {
            const params = new URLSearchParams({
                token: token,
                filename: logViewerState.currentFile,
                page: logViewerState.currentPage,
                per_page: logViewerState.perPage,
                order: logViewerState.order
            });

            const response = await fetch(`/admin/control-panel/logs?${params}`);
            const result = await response.json();

            if (result.error) {
                logContentDiv.innerHTML = `<div class="alert alert-danger m-3">${result.error}</div>`;
                return;
            }

            // Update file info
            document.getElementById('logFileInfo').innerHTML = `
                <small>
                    <div><strong>Size:</strong> ${result.file_info.size_formatted}</div>
                    <div><strong>Modified:</strong> ${result.file_info.modified}</div>
                    <div><strong>Total Lines:</strong> ${result.file_info.lines.toLocaleString()}</div>
                </small>
            `;

            logFileDetailsSpan.textContent = `${result.file_info.size_formatted} • ${result.file_info.modified}`;

            // Display content
            if (logViewerState.rawView) {
                logContentDiv.innerHTML = `<div class="raw-log p-3">${result.raw_content}</div>`;
            } else {
                // Split into lines and wrap each line
                const lines = result.content.split('\n');
                const wrappedLines = lines.map(line => line.trim() ? `<div class="log-line">${line}</div>` : '').join('');
                logContentDiv.innerHTML = wrappedLines || '<div class="text-center py-5 text-muted">Log file is empty</div>';
            }

            // Update pagination
            renderPagination(result.pagination);

            // Update statistics
            updateLogStatistics(result.raw_content);

        } catch (error) {
            logContentDiv.innerHTML = `<div class="alert alert-danger m-3">Error: ${error.message}</div>`;
        }
    }

    // Render pagination
    function renderPagination(pagination) {
        const paginationHtml = `
            <div class="log-pagination">
                <nav aria-label="Log navigation">
                    <ul class="pagination pagination-sm mb-0">
                        ${pagination.has_previous ? `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="changeLogPage(${pagination.current_page - 1})">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        ` : ''}

                        <li class="page-item disabled">
                            <span class="page-link">
                                Page ${pagination.current_page} of ${pagination.total_pages}
                                <small class="d-block text-muted">(${pagination.total_lines.toLocaleString()} total lines)</small>
                            </span>
                        </li>

                        ${pagination.has_next ? `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="changeLogPage(${pagination.current_page + 1})">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        ` : ''}
                    </ul>
                </nav>
            </div>
        `;

        document.getElementById('logPaginationTop').innerHTML = paginationHtml;
        document.getElementById('logPaginationBottom').innerHTML = paginationHtml;
    }

    // Change log page
    function changeLogPage(page) {
        if (page < 1) return;
        viewLogFile(logViewerState.currentFile, page);
    }

    // Change log order
    function changeLogOrder(order) {
        logViewerState.order = order;
        logViewerState.currentPage = 1;
        loadLogContent();
    }

    // Change lines per page
    function changeLogPerPage() {
        const select = document.getElementById('logPerPage');
        logViewerState.perPage = parseInt(select.value);
        logViewerState.currentPage = 1;
        loadLogContent();
    }

    // Refresh log files list
    function refreshLogFiles() {
        loadLogFiles();
    }

    // Refresh current log
    function refreshCurrentLog() {
        loadLogContent();
    }

    // Toggle raw view
    function toggleRawLog() {
        logViewerState.rawView = !logViewerState.rawView;
        const btn = document.querySelector('[onclick="toggleRawLog()"]');
        btn.innerHTML = logViewerState.rawView ?
            '<i class="fas fa-eye"></i> Formatted' :
            '<i class="fas fa-code"></i> Raw';
        loadLogContent();
    }

    // Copy log content
    async function copyLogContent() {
        try {
            if (!logViewerState.currentFile) {
                showAlert('No log file selected', 'warning');
                return;
            }

            const params = new URLSearchParams({
                token: token,
                filename: logViewerState.currentFile,
                page: logViewerState.currentPage,
                per_page: logViewerState.perPage,
                order: logViewerState.order
            });

            const response = await fetch(`/admin/control-panel/logs?${params}`);
            const result = await response.json();

            if (result.raw_content) {
                await navigator.clipboard.writeText(result.raw_content);
                showAlert('Log content copied to clipboard!', 'success');
            } else {
                showAlert('No content to copy', 'warning');
            }
        } catch (error) {
            showAlert('Failed to copy log: ' + error.message, 'danger');
        }
    }

    // Update log statistics
    function updateLogStatistics(content) {
        if (!content) {
            content = '';
        }

        const stats = {
            info: (content.match(/\.INFO\./g) || []).length,
            warning: (content.match(/\.WARNING\./g) || []).length,
            error: (content.match(/\.ERROR\./g) || []).length,
            critical: (content.match(/\.(CRITICAL|ALERT|EMERGENCY)\./g) || []).length
        };

        document.getElementById('logStatsInfo').textContent = stats.info;
        document.getElementById('logStatsWarning').textContent = stats.warning;
        document.getElementById('logStatsError').textContent = stats.error;
        document.getElementById('logStatsCritical').textContent = stats.critical;
    }

    // Download current log
    function downloadCurrentLog() {
        if (!logViewerState.currentFile) {
            showAlert('No log file selected', 'warning');
            return;
        }

        // Create download link
        const downloadUrl = `/admin/control-panel/download-log?token=${token}&filename=${encodeURIComponent(logViewerState.currentFile)}`;

        // Create hidden iframe for download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);

        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 1000);

        showAlert('Download started for ' + logViewerState.currentFile, 'success');
    }

    // Clear current log
    async function clearCurrentLog() {
        if (!logViewerState.currentFile || !confirm('Are you sure you want to clear this log file?')) {
            return;
        }

        try {
            const response = await fetch('/admin/control-panel/clear-log?token=' + token, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ filename: logViewerState.currentFile })
            });

            const result = await response.json();

            if (result.success) {
                showAlert('Log file cleared successfully!', 'success');
                logViewerState.currentPage = 1;
                loadLogContent();
                loadLogFiles(); // Refresh file list
            } else {
                showAlert('Error: ' + result.error, 'danger');
            }
        } catch (error) {
            showAlert('Clear error: ' + error.message, 'danger');
        }
    }

    // ========== UTILITY FUNCTIONS ==========
    // Utility function to show alerts
    function showAlert(message, type) {
        // Remove existing alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }

    // ========== INITIALIZATION ==========
    // Initialize log viewer when tab is shown
    document.addEventListener('DOMContentLoaded', function() {
        const logsTab = document.getElementById('logs-tab');
        if (logsTab) {
            logsTab.addEventListener('shown.bs.tab', function() {
                if (!logViewerState.currentFile) {
                    loadLogFiles();
                }
            });
        }

        // Also load files if we're already on the logs tab
        if (document.getElementById('logs').classList.contains('active')) {
            loadLogFiles();
        }
    });
</script>
</body>
</html>
