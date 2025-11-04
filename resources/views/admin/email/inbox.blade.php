@extends('admin.admin_master')

@section('vendor_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-sidebar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-email.css') }}">

    <style>
        .email-brief-info.unread {
            background-color: #f5f5f5;
            font-weight: 600;
        }

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

        .email-collection {
            max-height: calc(100vh - 300px);
            overflow-y: auto !important;
            overflow-x: hidden;
        }

        .card.scrollspy {
            overflow: visible;
        }

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

        .edit-email-item {
            overflow: visible;
        }

        .compose-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .compose-backdrop.show {
            display: block;
        }

        @media only screen and (max-width: 768px) {
            .email-collection {
                max-height: calc(100vh - 250px);
            }

            .compose-editor {
                max-height: 200px;
            }
        }
    </style>
@endsection

@section('admin')
    @php
        $pageTitle = 'Email Inbox';
        $folder = $folder ?? 'INBOX';
        $emails = $emails ?? [];
    @endphp

    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="col s12">
                <div class="container">

                    @if(session('error'))
                        <div class="card-panel red lighten-4 red-text">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Sidebar -->
                    <div class="email-overlay"></div>
                    @include('admin.email.partials.email_sidebar')

                    <!-- Content Area -->
                    <div class="app-email">
                        <div class="content-area content-right">
                            <div class="app-wrapper">
{{--                                <div class="app-search">--}}
{{--                                    <i class="material-icons mr-2 search-icon">search</i>--}}
{{--                                    <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter">--}}
{{--                                </div>--}}

{{--                                <div class="app-search">--}}
{{--                                    <i class="material-icons mr-2 search-icon">search</i>--}}
{{--                                    <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter">--}}

{{--                                    --}}{{-- Show active account --}}
{{--
{{--                                </div>--}}

                                {{-- Email header with search and active account --}}
                                <div class="app-search" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center; flex: 1;">
                                        <i class="material-icons mr-2 search-icon">search</i>
                                        <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter" style="flex: 1;">
                                    </div>
                                </div>



                                <div class="card card-default scrollspy border-radius-6 fixed-width">
                                    <div class="card-content p-0 pb-2">
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

                                        <div class="collection email-collection">
                                            @forelse($emails as $email)
                                                <div
                                                    class="email-brief-info collection-item animate fadeUp {{ $email['is_seen'] ? '' : 'unread' }}"
                                                    data-uid="{{ $email['uid'] }}">
                                                    <div class="list-left">
                                                        <label>
                                                            <input type="checkbox" name="email_check"
                                                                   value="{{ $email['uid'] }}"/>
                                                            <span></span>
                                                        </label>
                                                        <div class="favorite" onclick="toggleFlag({{ $email['uid'] }})">
                                                            <i class="material-icons">{{ $email['is_flagged'] ? 'star' : 'star_border' }}</i>
                                                        </div>
                                                    </div>

                                                    @if($folder == 'DRAFT')
                                                        <a class="list-content" href="javascript:void(0)"
                                                           onclick="loadDraft({{ $email['uid'] }})">
                                                            @else
                                                                <a class="list-content"
                                                                   href="{{ route('admin.email.show', ['uid' => $email['uid'], 'folder' => $folder]) }}">
                                                                    @endif
                                                                    <div class="list-title-area">
{{--                                                                        <div class="user-media">--}}
{{--                                                                            <img--}}
{{--                                                                                src="{{ asset('admin/assets/images/user/default.jpg') }}"--}}
{{--                                                                                alt=""--}}
{{--                                                                                class="circle z-depth-2 responsive-img avtar">--}}
{{--                                                                            <div--}}
{{--                                                                                class="list-title">{{ $email['from_name'] }}</div>--}}
{{--                                                                        </div>--}}
                                                                        <div class="title-right">
                                                                            @if($email['has_attachments'])
                                                                                <span class="attach-file">
                                                                        <i class="material-icons">attach_file</i>
                                                                    </span>
                                                                            @endif
                                                                            @if(!$email['is_seen'])
                                                                                <span
                                                                                    class="badge blue lighten-3">New</span>
                                                                            @endif
                                                                            @if($folder == 'DRAFT')
                                                                                <span class="badge orange lighten-3">Draft</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="list-subject" style="margin-top: -0.8em">
                                                                        <strong>{{ $email['subject'] }}</strong>
                                                                    </div>
                                                                    <div class="list-desc">{{ $email['preview'] }}</div>
                                                                </a>

                                                                <div class="list-right">
                                                                    <div
                                                                        class="list-date">{{ \Carbon\Carbon::parse($email['date'])->diffForHumans() }}</div>
                                                                </div>
                                                        </a>
                                                </div>
                                            @empty
                                                <div class="no-data-found collection-item">
                                                    <h6 class="center-align font-weight-500">No Emails Found</h6>
                                                    @if(session('error'))
                                                        <p class="center-align red-text">{{ session('error') }}</p>
                                                    @endif
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compose Button -->
                    <div style="bottom: 54px; right: 19px;" class="fixed-action-btn direction-top">
                        <a class="btn-floating btn-large primary-text gradient-shadow compose-email-trigger" href="#">
                            <i class="material-icons">add</i>
                        </a>
                    </div>

                    <!-- Email Compose Sidebar -->
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

                                <form id="compose-email-form" enctype="multipart/form-data"
                                      class="edit-email-item mt-10 mb-10">
                                    @csrf
                                    <div class="input-field">
                                        <input type="email" id="email-from" value="{{ config('mail.from.address') }}"
                                               disabled>
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
                                    <button type="button"
                                            class="btn-small waves-effect waves-light cancel-email-item mr-1">
                                        <i class="material-icons left">close</i>
                                        <span>Cancel</span>
                                    </button>
                                    <button type="button" class="btn-small waves-effect waves-light send-email-item"
                                            id="send-email-btn">
                                        <i class="material-icons left">send</i>
                                        <span>Send</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="compose-backdrop"></div>
                </div>
            </div>
        </div>
    </div>
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
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            ['link', 'image']
                        ]
                    },
                    theme: 'snow',
                    placeholder: 'Write your message here...'
                });

                // Track changes for auto-save
                quillEditor.on('text-change', function () {
                    hasUnsavedChanges = true;
                    clearTimeout(autoSaveDraftTimeout);
                    autoSaveDraftTimeout = setTimeout(saveDraft, 30000);
                });

                // Track input changes
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
                composeTrigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    openCompose();
                });
            }

            const sendBtn = document.getElementById('send-email-btn');
            if (sendBtn) {
                sendBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    sendEmail();
                });
            }

            document.querySelectorAll('.cancel-email-item, .close-icon').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeCompose();
                });
            });

            const backdrop = document.querySelector('.compose-backdrop');
            if (backdrop) backdrop.addEventListener('click', closeCompose);

            const deleteBtn = document.querySelector('.delete-selected');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function () {
                    const checked = document.querySelectorAll('input[name="email_check"]:checked');
                    if (checked.length === 0) {
                        M.toast({html: 'Please select emails to delete'});
                        return;
                    }

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
                    const searchTerm = e.target.value.toLowerCase();
                    document.querySelectorAll('.email-brief-info').forEach(email => {
                        email.style.display = email.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
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
            document.querySelectorAll('input[name="email_check"]').forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        function toggleFlag(uid) {
            fetch(`/admin/email/${uid}/toggle-flag`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                });
        }

        function saveDraft() {
            const to = document.getElementById('email-to').value.trim();
            const subject = document.getElementById('email-subject').value.trim();
            const message = quillEditor.root.innerHTML;
            const plainText = quillEditor.getText().trim();

            if (!to && !subject && plainText.length === 0) return;

            const formData = new FormData();
            formData.append('to', to);
            formData.append('subject', subject || '(No Subject)');
            formData.append('message', message);
            formData.append('cc', document.getElementById('email-cc').value.trim());
            formData.append('bcc', document.getElementById('email-bcc').value.trim());
            formData.append('is_draft', true);

            if (currentDraftUid) formData.append('draft_uid', currentDraftUid);

            fetch('{{ route("admin.email.save-draft") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentDraftUid = data.draft_uid;
                        hasUnsavedChanges = false;
                        M.toast({html: 'Draft saved', classes: 'grey', displayLength: 2000});
                    }
                })
                .catch(error => console.error('Draft save failed:', error));
        }

        function loadDraft(uid) {
            fetch(`{{ url('admin/email/draft') }}/${uid}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        openCompose();
                        document.getElementById('email-to').value = data.draft.to || '';
                        document.getElementById('email-subject').value = data.draft.subject || '';
                        document.getElementById('email-cc').value = data.draft.cc || '';
                        document.getElementById('email-bcc').value = data.draft.bcc || '';
                        quillEditor.root.innerHTML = data.draft.message || '';
                        currentDraftUid = uid;
                        hasUnsavedChanges = false;
                        M.updateTextFields();
                    }
                })
                .catch(error => {
                    console.error('Failed to load draft:', error);
                    M.toast({html: 'Failed to load draft', classes: 'red'});
                });
        }

        function sendEmail() {
            if (!quillEditor) {
                M.toast({html: 'Editor not initialized', classes: 'red'});
                return;
            }

            const form = document.getElementById('compose-email-form');
            const toEmail = document.getElementById('email-to').value.trim();
            const subject = document.getElementById('email-subject').value.trim();
            const plainText = quillEditor.getText().trim();

            if (!toEmail) {
                M.toast({html: 'Please enter recipient email', classes: 'red'});
                return;
            }

            if (!subject) {
                M.toast({html: 'Please enter subject', classes: 'red'});
                return;
            }

            if (!plainText || plainText.length === 0) {
                M.toast({html: 'Please write a message', classes: 'red'});
                return;
            }

            const formData = new FormData(form);
            formData.set('message', quillEditor.root.innerHTML);

            const sendBtn = document.getElementById('send-email-btn');
            const originalHTML = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="material-icons left">hourglass_empty</i><span>Sending...</span>';
            sendBtn.disabled = true;

            fetch('{{ route("admin.email.compose") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (currentDraftUid) deleteDraft(currentDraftUid);
                        M.toast({html: data.message || 'Email sent successfully!', classes: 'green'});
                        form.reset();
                        quillEditor.setContents([]);
                        hasUnsavedChanges = false;
                        currentDraftUid = null;
                        actuallyCloseCompose();
                    } else {
                        M.toast({html: data.message || 'Failed to send email', classes: 'red'});
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    M.toast({html: 'Network error: ' + error.message, classes: 'red'});
                })
                .finally(() => {
                    sendBtn.innerHTML = originalHTML;
                    sendBtn.disabled = false;
                });
        }

        function deleteDraft(uid) {
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
            const to = document.getElementById('email-to').value.trim();
            const subject = document.getElementById('email-subject').value.trim();
            const plainText = quillEditor.getText().trim();
            const hasContent = to || subject || plainText.length > 0;

            if (hasUnsavedChanges && hasContent) {
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
            currentDraftUid = null;
        }
    </script>
@endsection
