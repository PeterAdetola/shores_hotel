@extends('admin.admin_master')

@section('vendor_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-sidebar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-email.css') }}">

    <style>
        /* ── Unread row highlight ─────────────────────────── */
        .email-brief-info.unread {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        /* ── Compose editor ──────────────────────────────── */
        .snow-container {
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
        }
        .compose-editor {
            min-height: 150px;
            max-height: 250px;
            overflow-y: auto;
        }

        .list-subject {
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* ── KEY FIX: let the card grow naturally with its content.
              No overflow, no fixed height on the collection.
              The pagination sits naturally at the bottom of the card. ── */
        .card.scrollspy {
            overflow: visible;
            height: auto !important;
        }
        .email-collection {
            overflow: visible !important;
            height: auto !important;
            max-height: none !important;
        }

        /* ── Compose sidebar ─────────────────────────────── */
        .email-compose-sidebar {
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
        }
        .email-compose-sidebar .card {
            height: auto;
            max-height: none;
        }
        .email-compose-sidebar .card-content {
            overflow: visible;
            height: auto;
        }
        .edit-email-item { overflow: visible; }

        .compose-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .compose-backdrop.show { display: block; }

        /* ── Pagination bar ──────────────────────────────── */
        .email-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-top: 1px solid #e0e0e0;
            background: #fafafa;
            border-radius: 0 0 6px 6px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .email-pagination .page-info {
            font-size: 13px;
            color: #757575;
            white-space: nowrap;
        }
        .email-pagination .page-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .email-pagination .page-nav a,
        .email-pagination .page-nav span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 6px;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.15s;
        }
        .email-pagination .page-nav a {
            color: #424242;
            border: 1px solid #e0e0e0;
            background: #fff;
        }
        .email-pagination .page-nav a:hover { background: #f5f5f5; }
        .email-pagination .page-nav a.active {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
            font-weight: 600;
        }
        .email-pagination .page-nav a.disabled,
        .email-pagination .page-nav span.disabled {
            color: #bdbdbd;
            border: 1px solid #e0e0e0;
            background: #fafafa;
            pointer-events: none;
            cursor: default;
        }
        .email-pagination .page-nav .ellipsis {
            border: none;
            background: none;
            color: #9e9e9e;
            min-width: 24px;
        }

        @media only screen and (max-width: 768px) {
            .compose-editor { max-height: 200px; }
            .email-pagination { justify-content: center; }
            .email-pagination .page-info { width: 100%; text-align: center; }
        }
    </style>
@endsection

@section('admin')
    @php
        $pageTitle  = 'Email Inbox';
        $folder     = $folder  ?? 'INBOX';
        $emails     = $emails  ?? [];
        $total      = $total   ?? count($emails);
        $page       = $page    ?? 1;
        $perPage    = $perPage ?? 15;
        $totalPages = ($perPage > 0 && $total > 0) ? (int) ceil($total / $perPage) : 1;
        $from       = $total > 0 ? (($page - 1) * $perPage) + 1 : 0;
        $to         = min($page * $perPage, $total);
    @endphp

    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="col s12">
                <div class="container">

                    @if(session('error') || isset($error))
                        <div class="card-panel red lighten-4 red-text">
                            {{ session('error') ?? $error }}
                        </div>
                    @endif

                    <!-- Sidebar -->
                    <div class="email-overlay"></div>
                    @include('admin.email.partials.email_sidebar')

                    <!-- Content Area -->
                    <div class="app-email">
                        <div class="content-area content-right">
                            <div class="app-wrapper">

                                {{-- Search bar --}}
                                <div class="app-search" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;">
                                    <div style="display:flex; align-items:center; flex:1;">
                                        <i class="material-icons mr-2 search-icon">search</i>
                                        <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter" style="flex:1;">
                                    </div>
                                </div>

                                <div class="card card-default scrollspy border-radius-6 fixed-width">
                                    <div class="card-content p-0 pb-0">

                                        {{-- Email list header --}}
                                        <div class="email-header">
                                            <div class="left-icons">
                                                <span class="header-checkbox">
                                                    <label>
                                                        <input type="checkbox" onClick="toggleAll(this)"/>
                                                        <span></span>
                                                    </label>
                                                </span>
                                                <span class="action-icons">
                                                    <i class="material-icons" onclick="location.reload()">refresh</i>
                                                    <i class="material-icons delete-selected">delete</i>
                                                </span>
                                            </div>
                                            <div class="list-content"></div>
                                            <div class="email-action">
                                                <span class="email-options">
                                                    <i class="material-icons grey-text">more_vert</i>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Email rows --}}
                                        <div class="collection email-collection">
                                            @forelse($emails as $email)
                                                <div class="email-brief-info collection-item animate fadeUp {{ $email['is_seen'] ? '' : 'unread' }}"
                                                     data-uid="{{ $email['uid'] }}">

                                                    <div class="list-left">
                                                        <label>
                                                            <input type="checkbox" name="email_check" value="{{ $email['uid'] }}"/>
                                                            <span></span>
                                                        </label>
                                                        <div class="favorite" onclick="toggleFlag({{ $email['uid'] }})">
                                                            <i class="material-icons">{{ $email['is_flagged'] ? 'star' : 'star_border' }}</i>
                                                        </div>
                                                    </div>

                                                    @if($folder == 'DRAFT')
                                                        <a class="list-content" href="javascript:void(0)" onclick="loadDraft({{ $email['uid'] }})">
                                                            @else
                                                                <a class="list-content" href="{{ route('admin.email.show', ['uid' => $email['uid'], 'folder' => $folder]) }}">
                                                                    @endif
                                                                    <div class="list-title-area">
                                                                        <div class="title-right">
                                                                            @if($email['has_attachments'])
                                                                                <span class="attach-file"><i class="material-icons">attach_file</i></span>
                                                                            @endif
                                                                            @if(!$email['is_seen'])
                                                                                <span class="badge blue lighten-3">New</span>
                                                                            @endif
                                                                            @if($folder == 'DRAFT')
                                                                                <span class="badge orange lighten-3">Draft</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="list-subject" style="margin-top:-0.8em">
                                                                        <strong>{{ $email['subject'] }}</strong>
                                                                    </div>
                                                                    <div class="list-desc">{{ $email['preview'] }}</div>
                                                                </a>

                                                                <div class="list-right">
                                                                    <div class="list-date">{{ \Carbon\Carbon::parse($email['date'])->diffForHumans() }}</div>
                                                                </div>
                                                </div>
                                            @empty
                                                <div class="no-data-found collection-item">
                                                    <h6 class="center-align font-weight-500">No Emails Found</h6>
                                                </div>
                                            @endforelse
                                        </div>

                                        {{-- Pagination bar — only shown when there is more than one page --}}
                                        @if($totalPages > 1)
                                            <div class="email-pagination">

                                            <span class="page-info">
                                                Showing {{ $from }}–{{ $to }} of {{ $total }} emails
                                            </span>

                                                <div class="page-nav">

                                                    {{-- Previous --}}
                                                    @if($page > 1)
                                                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" title="Previous">
                                                            <i class="material-icons" style="font-size:16px;">chevron_left</i>
                                                        </a>
                                                    @else
                                                        <span class="disabled">
                                                        <i class="material-icons" style="font-size:16px;">chevron_left</i>
                                                    </span>
                                                    @endif

                                                    {{-- Page pills --}}
                                                    @php
                                                        $window = 2;
                                                        $start  = max(1, $page - $window);
                                                        $end    = min($totalPages, $page + $window);
                                                    @endphp

                                                    @if($start > 1)
                                                        <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">1</a>
                                                        @if($start > 2)<span class="ellipsis">…</span>@endif
                                                    @endif

                                                    @for($p = $start; $p <= $end; $p++)
                                                        <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                                                           class="{{ $p === $page ? 'active' : '' }}">{{ $p }}</a>
                                                    @endfor

                                                    @if($end < $totalPages)
                                                        @if($end < $totalPages - 1)<span class="ellipsis">…</span>@endif
                                                        <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">{{ $totalPages }}</a>
                                                    @endif

                                                    {{-- Next --}}
                                                    @if($page < $totalPages)
                                                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" title="Next">
                                                            <i class="material-icons" style="font-size:16px;">chevron_right</i>
                                                        </a>
                                                    @else
                                                        <span class="disabled">
                                                        <i class="material-icons" style="font-size:16px;">chevron_right</i>
                                                    </span>
                                                    @endif

                                                </div>
                                            </div>
                                        @endif

                                    </div>{{-- /.card-content --}}
                                </div>{{-- /.card --}}
                            </div>{{-- /.app-wrapper --}}
                        </div>{{-- /.content-area --}}
                    </div>{{-- /.app-email --}}

                    <!-- Compose FAB -->
                    <div style="bottom:54px; right:19px;" class="fixed-action-btn direction-top">
                        <a class="btn-floating btn-large primary-text gradient-shadow compose-email-trigger" href="#">
                            <i class="material-icons">add</i>
                        </a>
                    </div>

                    <!-- Compose Sidebar -->
                    <div class="email-compose-sidebar">
                        <div class="card quill-wrapper">
                            <div class="card-content pt-0">
                                <div class="card-header display-flex pb-2">
                                    <h3 class="card-title">NEW MESSAGE</h3>
                                    <div class="close close-icon">
                                        <i class="material-icons">close</i>
                                    </div>
                                </div>
                                <div class="divider"></div>

                                <form id="compose-email-form" enctype="multipart/form-data" class="edit-email-item mt-10 mb-10">
                                    @csrf
                                    <div class="input-field">
                                        <input type="email" id="email-from" value="{{ config('mail.from.address') }}" disabled>
                                        <label for="email-from">From</label>
                                    </div>
                                    <div class="input-field">
                                        <input type="email" name="to" id="email-to" required>
                                        <label for="email-to">To</label>
                                    </div>
                                    <div class="input-field">
                                        <input type="text" name="subject" id="email-subject" required>
                                        <label for="email-subject">Subject</label>
                                    </div>
                                    <div class="input-field">
                                        <input type="email" name="cc" id="email-cc">
                                        <label for="email-cc">CC</label>
                                    </div>
                                    <div class="input-field">
                                        <input type="email" name="bcc" id="email-bcc">
                                        <label for="email-bcc">BCC</label>
                                    </div>
                                    <div class="input-field">
                                        <div class="snow-container mt-2">
                                            <div class="compose-editor"></div>
                                        </div>
                                        <input type="hidden" name="message" id="email-message">
                                    </div>
                                    <div class="file-field input-field">
                                        <div class="btn btn-file">
                                            <span>Attach File</span>
                                            <input type="file" name="attachments[]" multiple>
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Upload files">
                                        </div>
                                    </div>
                                </form>

                                <div class="card-action pl-0 pr-0 right-align">
                                    <button type="button" class="btn-small waves-effect waves-light cancel-email-item mr-1">
                                        <i class="material-icons left">close</i><span>Cancel</span>
                                    </button>
                                    <button type="button" class="btn-small waves-effect waves-light send-email-item" id="send-email-btn">
                                        <i class="material-icons left">send</i><span>Send</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="compose-backdrop"></div>

                </div>{{-- /.container --}}
            </div>{{-- /.col --}}
        </div>{{-- /.row --}}
    </div>{{-- /#main --}}
@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/sortable/jquery-sortable-min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/quill.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin/assets/js/search.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/app-email.js') }}"></script>

    <script>
        let quillEditor;
        let autoSaveDraftTimeout;
        let currentDraftUid = null;
        let hasUnsavedChanges = false;

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                quillEditor = new Quill('.compose-editor', {
                    modules: { toolbar: [['bold', 'italic', 'underline'], ['link', 'image']] },
                    theme: 'snow',
                    placeholder: 'Write your message here...'
                });

                quillEditor.on('text-change', function () {
                    hasUnsavedChanges = true;
                    clearTimeout(autoSaveDraftTimeout);
                    autoSaveDraftTimeout = setTimeout(saveDraft, 30000);
                });

                ['email-to', 'email-subject', 'email-cc', 'email-bcc'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', () => hasUnsavedChanges = true);
                });

                initializeEventListeners();
            }, 200);
        });

        function initializeEventListeners() {
            const composeTrigger = document.querySelector('.compose-email-trigger');
            if (composeTrigger) {
                composeTrigger.addEventListener('click', function (e) { e.preventDefault(); openCompose(); });
            }

            const sendBtn = document.getElementById('send-email-btn');
            if (sendBtn) sendBtn.addEventListener('click', function (e) { e.preventDefault(); sendEmail(); });

            document.querySelectorAll('.cancel-email-item, .close-icon').forEach(el => {
                el.addEventListener('click', function (e) { e.preventDefault(); closeCompose(); });
            });

            const backdrop = document.querySelector('.compose-backdrop');
            if (backdrop) backdrop.addEventListener('click', closeCompose);

            const deleteBtn = document.querySelector('.delete-selected');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function () {
                    const checked = document.querySelectorAll('input[name="email_check"]:checked');
                    if (checked.length === 0) { M.toast({html: 'Please select emails to delete'}); return; }
                    if (confirm('Are you sure you want to delete selected emails?')) {
                        checked.forEach(checkbox => {
                            fetch(`/admin/email/${checkbox.value}`, {
                                method: 'DELETE',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                            }).then(() => location.reload());
                        });
                    }
                });
            }

            const searchInput = document.getElementById('email_filter');
            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();
                    document.querySelectorAll('.email-brief-info').forEach(row => {
                        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
                    });
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && document.querySelector('.email-compose-sidebar').classList.contains('show')) {
                    closeCompose();
                }
            });
        }

        function toggleAll(source) {
            document.querySelectorAll('input[name="email_check"]').forEach(cb => cb.checked = source.checked);
        }

        function toggleFlag(uid) {
            fetch(`/admin/email/${uid}/toggle-flag`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(r => r.json()).then(data => { if (data.success) location.reload(); });
        }

        function saveDraft() {
            const to    = document.getElementById('email-to').value.trim();
            const sub   = document.getElementById('email-subject').value.trim();
            const plain = quillEditor.getText().trim();
            if (!to && !sub && plain.length === 0) return;

            const fd = new FormData();
            fd.append('to',       to);
            fd.append('subject',  sub || '(No Subject)');
            fd.append('message',  quillEditor.root.innerHTML);
            fd.append('cc',       document.getElementById('email-cc').value.trim());
            fd.append('bcc',      document.getElementById('email-bcc').value.trim());
            fd.append('is_draft', true);
            if (currentDraftUid) fd.append('draft_uid', currentDraftUid);

            fetch('{{ route("admin.email.save-draft") }}', {
                method: 'POST', body: fd,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    currentDraftUid = data.draft_uid;
                    hasUnsavedChanges = false;
                    M.toast({html: 'Draft saved', classes: 'grey', displayLength: 2000});
                }
            }).catch(err => console.error('Draft save failed:', err));
        }

        function loadDraft(uid) {
            fetch(`{{ url('admin/email/draft') }}/${uid}`, {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    openCompose();
                    document.getElementById('email-to').value      = data.draft.to      || '';
                    document.getElementById('email-subject').value = data.draft.subject  || '';
                    document.getElementById('email-cc').value      = data.draft.cc       || '';
                    document.getElementById('email-bcc').value     = data.draft.bcc      || '';
                    quillEditor.root.innerHTML = data.draft.message || '';
                    currentDraftUid   = uid;
                    hasUnsavedChanges = false;
                    M.updateTextFields();
                }
            }).catch(err => { console.error(err); M.toast({html: 'Failed to load draft', classes: 'red'}); });
        }

        function sendEmail() {
            if (!quillEditor) { M.toast({html: 'Editor not initialized', classes: 'red'}); return; }

            const to    = document.getElementById('email-to').value.trim();
            const sub   = document.getElementById('email-subject').value.trim();
            const plain = quillEditor.getText().trim();

            if (!to)    { M.toast({html: 'Please enter recipient email', classes: 'red'}); return; }
            if (!sub)   { M.toast({html: 'Please enter subject',          classes: 'red'}); return; }
            if (!plain) { M.toast({html: 'Please write a message',        classes: 'red'}); return; }

            const form = document.getElementById('compose-email-form');
            const fd   = new FormData(form);
            fd.set('message', quillEditor.root.innerHTML);

            const btn      = document.getElementById('send-email-btn');
            const origHTML = btn.innerHTML;
            btn.innerHTML  = '<i class="material-icons left">hourglass_empty</i><span>Sending...</span>';
            btn.disabled   = true;

            fetch('{{ route("admin.email.compose") }}', {
                method: 'POST', body: fd,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    if (currentDraftUid) deleteDraftSilent(currentDraftUid);
                    M.toast({html: data.message || 'Email sent successfully!', classes: 'green'});
                    form.reset();
                    quillEditor.setContents([]);
                    hasUnsavedChanges = false;
                    currentDraftUid   = null;
                    actuallyCloseCompose();
                } else {
                    M.toast({html: data.message || 'Failed to send email', classes: 'red'});
                }
            }).catch(err => {
                console.error(err);
                M.toast({html: 'Network error: ' + err.message, classes: 'red'});
            }).finally(() => { btn.innerHTML = origHTML; btn.disabled = false; });
        }

        function deleteDraftSilent(uid) {
            fetch(`{{ url('admin/email/draft') }}/${uid}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });
        }

        function openCompose() {
            document.querySelector('.email-compose-sidebar').classList.add('show');
            document.querySelector('.compose-backdrop').classList.add('show');
        }

        function closeCompose() {
            const to    = document.getElementById('email-to').value.trim();
            const sub   = document.getElementById('email-subject').value.trim();
            const plain = quillEditor.getText().trim();

            if (hasUnsavedChanges && (to || sub || plain.length > 0)) {
                if (confirm('Save this email as a draft?')) {
                    saveDraft();
                    setTimeout(actuallyCloseCompose, 500);
                } else {
                    actuallyCloseCompose();
                }
            } else {
                actuallyCloseCompose();
            }
        }

        function actuallyCloseCompose() {
            document.querySelector('.email-compose-sidebar').classList.remove('show');
            document.querySelector('.compose-backdrop').classList.remove('show');
            document.getElementById('compose-email-form').reset();
            quillEditor.setContents([]);
            hasUnsavedChanges = false;
            currentDraftUid   = null;
        }
    </script>
@endsection
