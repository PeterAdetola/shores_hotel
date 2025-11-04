@extends('admin.admin_master')
@section('admin')

    @section('vendor_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
    @endsection
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-sidebar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-email-content.css') }}">
    @endsection
    <style>
        .email-body-content {
            /* Isolate email styles from your admin template */
            all: initial;
            display: block;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background: #fff;
        }

        .email-body-content * {
            max-width: 100%;
        }

        .email-body-content img {
            max-width: 100%;
            height: auto;
        }

        .email-body-content table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
    @php
        $pageTitle = 'Email - ' . ($subject ?? 'No Subject');
    @endphp
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="col s12">
                <div class="container">
                    <!-- Sidebar Area Starts -->

                    @include('admin.email.partials.email_sidebar')
                    <!-- Sidebar Area Ends -->

                    <!-- Content Area Starts -->
                    <div class="app-email-content">
                        <div class="content-area content-right">
                            <div class="app-wrapper">
                                <div class="app-search">
                                    <i class="material-icons mr-2 search-icon">search</i>
                                    <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter">
                                </div>
                                <div class="card card-default scrollspy border-radius-6 fixed-width">
                                    <div class="card-content pt-0">
                                        <div class="row">
                                            <div class="col s12">
                                                <!-- Email Header -->
                                                <div class="email-header">
                                                    <div class="subject">
                                                        <div class="back-to-mails">
                                                            <a href="{{ route('admin.email.inbox') }}"><i class="material-icons">arrow_back</i></a>
                                                        </div>
                                                        <div class="email-title">{{ $subject ?? 'No Subject' }}</div>
                                                    </div>
                                                    <div class="header-action">
                                                        @php
                                                            // Simple flag checking using string conversion
                                                            $isFlagged = false;
                                                            try {
                                                                $flags = $message->getFlags();
                                                                $flagsString = (string) $flags;
                                                                $isFlagged = strpos($flagsString, 'Flagged') !== false;
                                                            } catch (\Exception $e) {
                                                                // Ignore flag errors
                                                            }
                                                        @endphp

                                                        @if($isFlagged)
                                                            <span class="badge amber lighten-2"><i class="amber-text material-icons small-icons mr-2">
                                                            fiber_manual_record </i>Flagged</span>
                                                        @endif

                                                        <div class="favorite">
                                                            <i class="material-icons">{{ $isFlagged ? 'star' : 'star_border' }}</i>
                                                        </div>
                                                        <div class="email-label">
                                                            <i class="material-icons">label_outline</i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Email Header Ends -->
                                                <hr>

                                                <!-- Email Content -->
                                                <div class="email-content">
                                                    <div class="list-title-area">
                                                        <div class="user-media">
                                                            <img src="{{ asset('admin/assets/images/user/default.jpg') }}" alt=""
                                                                 class="circle z-depth-2 responsive-img avtar">
                                                            <div class="list-title">
                                                                <span class="name">{{ $fromName ?? 'Unknown Sender' }}</span>
                                                                <span class="to-person">to me</span>
                                                            </div>
                                                        </div>
                                                        <div class="title-right">
                                                            <span class="mail-time">{{ $dateString ?? 'No date' }}</span>
                                                            <i class="material-icons" onclick="showReplyForm()">reply</i>
                                                            <i class="material-icons">more_vert</i>
                                                        </div>
                                                    </div><div class="email-desc email-body-content">
                                                        @if(!empty($body))
                                                            {!! $body !!}
                                                        @else
                                                            <div class="center-align grey-text">
                                                                <p>No content available for this email.</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Email Content Ends -->

                                                @if($message->hasAttachments())
                                                    <hr>
                                                    <!-- Email Footer -->
                                                    <div class="email-footer">
                                                        <h6 class="footer-title">Attachments ({{ $message->getAttachments()->count() }})</h6>
                                                        <div class="footer-action">
                                                            <div class="attachment-list">
                                                                @foreach($message->getAttachments() as $attachment)
                                                                    <div class="attachment">
                                                                        <img src="{{ asset('admin/assets/images/icon/file.png') }}" alt="" class="responsive-img attached-image">
                                                                        <div class="size">
                                                                            <span class="grey-text">({{ round($attachment->getSize() / 1024, 2) }} KB)</span>
                                                                        </div>
                                                                        <div class="links">
                                                                            <a href="{{ route('admin.email.attachment', ['uid' => $message->getUid(), 'attachmentId' => $loop->index]) }}" class="left">
                                                                                <i class="material-icons">remove_red_eye</i>
                                                                            </a>
                                                                            <a href="{{ route('admin.email.attachment', ['uid' => $message->getUid(), 'attachmentId' => $loop->index]) }}" class="Right">
                                                                                <i class="material-icons">file_download</i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Email Footer Ends -->
                                                @endif

                                                <!-- Action Buttons -->
                                                <div class="footer-buttons mt-3">
                                                    <a class="btn reply mb-1" onclick="showReplyForm()">
                                                        <i class="material-icons left">reply</i>
                                                        <span>Reply</span>
                                                    </a>
                                                    <a class="btn forward mb-1" onclick="showForwardForm()">
                                                        <i class="material-icons left">forward</i>
                                                        <span>Forward</span>
                                                    </a>
                                                    <a href="{{ route('admin.email.delete', $message->getUid()) }}"
                                                       class="btn delete mb-1 red"
                                                       onclick="return confirm('Are you sure you want to delete this email?')">
                                                        <i class="material-icons left">delete</i>
                                                        <span>Delete</span>
                                                    </a>
                                                </div>

                                                <!-- Reply Box -->
                                                <div class="reply-box mt-3" style="display: none;" id="reply-box">
                                                    <form id="reply-form">
                                                        @csrf
                                                        <div class="input-field col s12">
                                                            <div class="snow-container mt-2">
                                                                <div class="compose-editor" id="reply-editor"></div>
                                                                <div class="compose-quill-toolbar">
                                                                    <span class="ql-formats mr-0">
                                                                        <button class="ql-bold"></button>
                                                                        <button class="ql-italic"></button>
                                                                        <button class="ql-underline"></button>
                                                                        <button class="ql-link"></button>
                                                                        <button class="ql-image"></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="input-field col s12">
                                                            <a class="btn reply-btn right" onclick="replyToEmail()">Reply</a>
                                                            <a class="btn grey right mr-2" onclick="hideReplyForm()">Cancel</a>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Forward Box -->
                                                <div class="forward-box mt-3" style="display: none;" id="forward-box">
                                                    <hr>
                                                    <form id="forward-form">
                                                        @csrf
                                                        <div class="input-field col s12">
                                                            <i class="material-icons prefix">person_outline</i>
                                                            <input id="forward-to" type="email" class="validate" name="to" required>
                                                            <label for="forward-to">To</label>
                                                        </div>
                                                        <div class="input-field col s12">
                                                            <i class="material-icons prefix">title</i>
                                                            <input id="forward-subject" type="text" class="validate" name="subject" value="Fwd: {{ $subject }}" required>
                                                            <label for="forward-subject">Subject</label>
                                                        </div>
                                                        <div class="input-field col s12">
                                                            <div class="snow-container mt-2">
                                                                <div class="forward-email" id="forward-editor"></div>
                                                                <div class="forward-email-toolbar">
                                                                    <span class="ql-formats mr-0">
                                                                        <button class="ql-bold"></button>
                                                                        <button class="ql-italic"></button>
                                                                        <button class="ql-underline"></button>
                                                                        <button class="ql-link"></button>
                                                                        <button class="ql-image"></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="input-field col s12">
                                                            <a class="btn forward-btn right" onclick="forwardEmail()">Forward</a>
                                                            <a class="btn grey right mr-2" onclick="hideForwardForm()">Cancel</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Area Ends -->
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide reply form
        function showReplyForm() {
            document.getElementById('reply-box').style.display = 'block';
            document.getElementById('forward-box').style.display = 'none';
            // Initialize Quill editor for reply
            if (typeof replyQuill === 'undefined') {
                window.replyQuill = new Quill('#reply-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: '.compose-quill-toolbar'
                    }
                });
            }
        }

        function hideReplyForm() {
            document.getElementById('reply-box').style.display = 'none';
        }

        // Show/hide forward form
        function showForwardForm() {
            document.getElementById('forward-box').style.display = 'block';
            document.getElementById('reply-box').style.display = 'none';
            // Initialize Quill editor for forward
            if (typeof forwardQuill === 'undefined') {
                window.forwardQuill = new Quill('#forward-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: '.forward-email-toolbar'
                    }
                });
                // Pre-populate with original message
                forwardQuill.root.innerHTML = '<br><br>---------- Forwarded message ---------<br>From: {{ $fromName }} ({{ $fromAddress }})<br>Date: {{ $dateString }}<br>Subject: {{ $subject }}<br><br>' + `{!! addslashes($body) !!}`;
            }
        }

        function hideForwardForm() {
            document.getElementById('forward-box').style.display = 'none';
        }

        // Reply function - UPDATED
        function replyToEmail() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('message', window.replyQuill ? window.replyQuill.root.innerHTML : '');

            fetch('{{ route("admin.email.reply", $message->getUid()) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        M.toast({html: data.message || 'Reply sent successfully!'});
                        hideReplyForm();
                        if (window.replyQuill) {
                            window.replyQuill.root.innerHTML = '';
                        }
                    } else {
                        M.toast({html: 'Error: ' + (data.message || 'Unknown error')});
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    M.toast({html: 'Error sending reply. Please try again.'});
                });
        }

        // Forward function - UPDATED
        function forwardEmail() {
            const formData = new FormData(document.getElementById('forward-form'));
            formData.append('message', window.forwardQuill ? window.forwardQuill.root.innerHTML : '');

            fetch('{{ route("admin.email.forward", $message->getUid()) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        M.toast({html: data.message || 'Email forwarded successfully!'});
                        hideForwardForm();
                        document.getElementById('forward-form').reset();
                    } else {
                        M.toast({html: 'Error: ' + (data.message || 'Unknown error')});
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    M.toast({html: 'Error forwarding email. Please try again.'});
                });
        }

        // Initialize Materialize components
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any Materialize components if needed
        });

        // Redirect to inbox with search query
        document.getElementById('email_filter').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                window.location.href = '{{ route("admin.email.inbox") }}?search=' + encodeURIComponent(searchTerm);
            }
        });
    </script>
@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/sortable/jquery-sortable-min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/quill.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin/assets/js/search.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/app-email-content.js') }}"></script>
@endsection
