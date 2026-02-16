@extends('admin.admin_master')
@section('admin')
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/data-tables.css') }}">
        <style>
            /* Style for disabled toggle */
            .switch label input[type=checkbox]:disabled + .lever {
                cursor: not-allowed;
                opacity: 0.5;
            }
            .switch label input[type=checkbox]:disabled + .lever:after {
                background-color: #BDBDBD;
            }
        </style>
    @endsection
    @php
        $pageTitle = 'Manage Announcements';
        $hasPublished = $announcements->where('is_published', true)->isNotEmpty();
        $publishedAnnouncement = $announcements->where('is_published', true)->first();
    @endphp

        <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{ $pageTitle }}</span></h5>
                            <ol class="breadcrumbs mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin Home</a></li>
                                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div><br>

            <div class="col s12">
                <div class="container">
                    <div class="section">

                        @if(session('success'))
                            <div class="card-panel green lighten-4 green-text text-darken-4">
                                <i class="material-icons left">check_circle</i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <p class="caption col s12 m12 l8">
                                        Here are the list of all notifications/announcements. Only one can be published at a time.
                                        @if(!$hasPublished)
                                            <br/><strong class="orange-text">Note:</strong> The global publication toggle will be enabled once you publish an announcement.
                                        @else
                                            <br/><strong>Note:</strong> Use the toggle below to quickly unpublish all announcements.
                                        @endif
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="collection col s12 m12 l6" style="padding: 1em">
                                        <div class="chip col s3">
                                            &nbsp;&nbsp;&nbsp;
                                            Publication
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="switch col s4">
                                            <label>
                                                Off
                                                <input type="checkbox"
                                                       id="globalPublicationToggle"
                                                    {{ $hasPublished ? 'checked' : '' }}
                                                    {{ !$hasPublished ? 'disabled' : '' }}>
                                                <span class="lever"></span>
                                                On
                                            </label>
                                        </div>
                                        @if(!$hasPublished)
                                            <p class="col s12 grey-text text-darken-1" style="font-size: 0.9em; margin-top: 10px;">
                                                <i class="material-icons tiny">info</i>
                                                Publish an announcement to enable this toggle
                                            </p>
                                        @else
                                            <p class="col s12 grey-text text-darken-1" style="font-size: 0.9em; margin-top: 10px;">
                                                <i class="material-icons tiny">info</i>
                                                Currently showing: <strong class="green-text">{{ $publishedAnnouncement->title }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="popout" class="row">
                            <div class="col s12 m12 l10">
                                @if (count($announcements) > 0)
                                    <ul class="collapsible popout mb-5">
                                        @foreach($announcements as $announcement)
                                            <li class="{{ $announcement->is_published ? 'active' : '' }}">
                                                <div class="collapsible-header" tabindex="0">
                                                    <i class="material-icons mr-1">notifications</i>
                                                    {{ $announcement->title }}
                                                    @if($announcement->is_published)
                                                        <span class="new badge green" data-badge-caption="Live"></span>
                                                    @else
                                                        <span class="new badge grey" data-badge-caption="Draft"></span>
                                                    @endif
                                                </div>
                                                <div class="collapsible-body" style="{{ $announcement->is_published ? 'display: block;' : '' }}">
                                                    <div class="row">
                                                        <div class="col s12">
                                                            @if($announcement->subtitle)
                                                                <p><strong>Subtitle:</strong> {{ $announcement->subtitle }}</p>
                                                            @endif

                                                            <p><strong>Content:</strong></p>
                                                            <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 4px; background: #fafafa; border-left: 4px solid {{ $announcement->border_color }};">
                                                                {!! $announcement->content !!}
                                                            </div>

                                                            <div class="divider mt-3 mb-3"></div>

                                                            <div class="row">
                                                                <div class="col s12 m6">
                                                                    <p><strong>Call to Action:</strong> {{ $announcement->cta_text }}</p>
                                                                    <p><strong>CTA Link:</strong> <a href="{{ $announcement->cta_link }}" target="_blank" class="blue-text">{{ Str::limit($announcement->cta_link, 50) }}</a></p>
                                                                </div>
                                                                <div class="col s12 m6">
                                                                    <p><strong>Border Color:</strong></p>
                                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                                        <span style="display: inline-block; width: 40px; height: 40px; background: {{ $announcement->border_color }}; border: 2px solid #ccc; border-radius: 4px;"></span>
                                                                        <code>{{ $announcement->border_color }}</code>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="divider mt-3 mb-3"></div>

                                                            <p class="grey-text">
                                                                <i class="material-icons tiny">schedule</i>
                                                                Created: {{ $announcement->created_at->format('M d, Y \a\t h:i A') }}
                                                                @if($announcement->updated_at != $announcement->created_at)
                                                                    <br>
                                                                    <i class="material-icons tiny">update</i>
                                                                    Last updated: {{ $announcement->updated_at->format('M d, Y \a\t h:i A') }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="divider mt-2 mb-2"></div>

                                                    <div class="mt-1">
                                                        <p class="mt-1">
                                                            <a href="{{ route('announcement.edit', $announcement->id) }}"
                                                               style="background-color:#cddc39"
                                                               class="btn-floating mb-1 waves-effect waves-light tooltipped"
                                                               data-position="top"
                                                               data-tooltip="Edit Announcement">
                                                                <i class="material-icons">edit</i>
                                                            </a>
                                                            &nbsp;&nbsp;&nbsp;

                                                            <a href="#!"
                                                               onclick="togglePublish({{ $announcement->id }}, {{ $announcement->is_published ? 'false' : 'true' }})"
                                                               style="background-color:{{ $announcement->is_published ? '#ff9800' : '#4caf50' }}"
                                                               class="btn-floating mb-1 waves-effect waves-light tooltipped"
                                                               data-position="top"
                                                               data-tooltip="{{ $announcement->is_published ? 'Unpublish' : 'Publish' }}">
                                                                <i class="material-icons">{{ $announcement->is_published ? 'visibility_off' : 'visibility' }}</i>
                                                            </a>
                                                            &nbsp;&nbsp;&nbsp;

                                                            <a href="#deleteModal{{ $announcement->id }}"
                                                               style="background-color:#f44336"
                                                               class="btn-floating mb-1 modal-trigger waves-effect waves-light tooltipped"
                                                               data-position="top"
                                                               data-tooltip="Delete Announcement">
                                                                <i class="material-icons">delete</i>
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>

                                            <!-- Delete Modal -->
                                            <div id="deleteModal{{ $announcement->id }}" class="modal" style="padding:1em;">
                                                <div class="modal-content">
                                                    <h5><i class="material-icons red-text">warning</i> Delete Announcement</h5>
                                                    <p>Are you sure you want to delete "{{ $announcement->title }}"?</p>
                                                    <p class="red-text">This action cannot be undone.</p>
                                                    <div class="row mt-4">
                                                        <div class="col s12">
                                                            <form method="POST" action="{{ route('announcement.destroy', $announcement->id) }}" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="modal-action waves-effect waves-light red btn-large">
                                                                    <i class="material-icons left">delete</i>
                                                                    Yes, Delete
                                                                </button>
                                                            </form>
                                                            <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="card mb-5">
                                        <div class="card-content center-align">
                                            <i class="material-icons grey-text" style="font-size: 100px;">notifications_none</i>
                                            <h5 class="grey-text">No announcements yet</h5>
                                            <p>Create your first announcement to engage your visitors!</p>
                                            <a href="{{ route('announcement.create') }}" class="btn-large lime accent-1 mt-2 waves-effect waves-light">
                                                <i class="material-icons left">add</i>
                                                Create First Announcement
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($announcements) > 0)
                    <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top">
                        <a href="{{ route('announcement.create') }}"
                           class="btn-floating btn-large gradient-45deg-black-grey gradient-shadow tooltipped pulse"
                           data-position="left"
                           data-tooltip="Create New Announcement">
                            <i class="material-icons">add</i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END: Page Main-->

    <!-- Unpublish All Modal -->
    <div id="unpublish-all-modal" class="modal" style="padding:1em;">
        <div class="modal-content">
            <h5><i class="material-icons orange-text">warning</i> Unpublish All Announcements</h5>
            <p>Are you sure you want to unpublish all announcements?</p>
            <p class="orange-text">This will hide all announcements from your website visitors.</p>
            <div class="row mt-4">
                <div class="col s12">
                    <a href="javascript:void(0)" id="confirm-unpublish-all-btn" class="modal-action waves-effect waves-light red btn-large">
                        <i class="material-icons left">visibility_off</i>
                        Yes, Unpublish All
                    </a>
                    <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/js/dataTables.select.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/data-tables.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals
            var elems = document.querySelectorAll('.modal');
            M.Modal.init(elems);

            // Initialize collapsible
            var collapsibleElems = document.querySelectorAll('.collapsible');
            M.Collapsible.init(collapsibleElems);

            // Initialize tooltips
            var tooltips = document.querySelectorAll('.tooltipped');
            M.Tooltip.init(tooltips);

            // Global publication toggle - only works if enabled (has published announcement)
            var globalToggle = document.getElementById('globalPublicationToggle');
            if (globalToggle && !globalToggle.disabled) {
                globalToggle.addEventListener('change', function() {
                    if (!this.checked) {
                        // Open modal for confirmation
                        var modal = M.Modal.getInstance(document.getElementById('unpublish-all-modal'));
                        modal.open();

                        // Store reference to toggle for later use
                        window.globalToggleElement = this;
                    }
                });
            }

            // Confirm unpublish all button
            var confirmUnpublishBtn = document.getElementById('confirm-unpublish-all-btn');
            if (confirmUnpublishBtn) {
                confirmUnpublishBtn.addEventListener('click', function() {
                    fetch('{{ route("announcements.unpublishAll") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            M.toast({html: '✅ All announcements unpublished', classes: 'green'});
                            location.reload();
                        })
                        .catch(error => {
                            M.toast({html: '❌ An error occurred', classes: 'red'});
                            if (window.globalToggleElement) {
                                window.globalToggleElement.checked = true; // Revert toggle
                            }
                        });

                    // Close modal
                    var modal = M.Modal.getInstance(document.getElementById('unpublish-all-modal'));
                    modal.close();
                });
            }

            // Handle modal cancel - revert toggle
            var unpublishModal = document.getElementById('unpublish-all-modal');
            if (unpublishModal) {
                unpublishModal.addEventListener('click', function(e) {
                    if (e.target.classList.contains('modal-close') || e.target.classList.contains('modal-overlay')) {
                        if (window.globalToggleElement) {
                            window.globalToggleElement.checked = true; // Revert toggle if cancelled
                        }
                    }
                });
            }
        });

        // Toggle publish status
        function togglePublish(id, shouldPublish) {
            fetch(`/admin/announcements/${id}/toggle-publish`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        M.toast({html: data.message, classes: 'green'});
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        M.toast({html: data.message || 'An error occurred', classes: 'red'});
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    M.toast({html: '❌ An error occurred', classes: 'red'});
                });
        }
    </script>
@endsection
