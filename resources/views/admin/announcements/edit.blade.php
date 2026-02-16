@extends('admin.admin_master')
@section('admin')

    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/katex.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/monokai-sub.min.css') }}">

        <style>
            #snow-container .editor {
                min-height: 100px;
                max-height: 400px;
                overflow-y: auto;
            }
            /* FORCE QUILL TOOLBAR BUTTONS TO BE CLICKABLE */
            .ql-toolbar button {
                opacity: 1 !important;
                cursor: pointer !important;
                pointer-events: auto !important;
            }

            .ql-toolbar button:hover {
                background-color: rgba(0,0,0,0.1) !important;
            }

            .ql-toolbar button.ql-active {
                background-color: rgba(0,0,0,0.2) !important;
            }

            /* Ensure SVG icons are visible */
            .ql-toolbar button svg,
            .ql-toolbar button svg .ql-stroke,
            .ql-toolbar button svg .ql-fill {
                opacity: 1 !important;
            }

            /* Remove any disabled styling */
            .ql-toolbar button[disabled] {
                opacity: 1 !important;
                cursor: pointer !important;
                pointer-events: auto !important;
            }
        </style>
    @endsection

    @php
        $pageTitle = 'Edit Announcement';
    @endphp

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
                                <li class="breadcrumb-item"><a href="{{ route('announcements') }}">Announcements</a></li>
                                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div><br>

            <div class="col s12">
                <div class="container">
                    <div class="section mb-5">

                        @if(session('success'))
                            <div class="card-panel green lighten-4 green-text text-darken-4">
                                <i class="material-icons left">check_circle</i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="card-panel red lighten-4 red-text text-darken-4">
                                <i class="material-icons left">error</i>
                                <strong>Please fix the following errors:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="formValidate0" method="POST" action="{{ route('announcement.update', $announcement->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Title & Subtitle -->
                            <div class="row">
                                <div class="col s12">
                                    <div class="card">
                                        <div class="card-content">
                                            <h4 class="card-title">
                                                <i class="material-icons left">edit</i>
                                                Edit Announcement
                                            </h4>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input class="validate" required id="title" name="title" type="text" value="{{ old('title', $announcement->title) }}">
                                                    <label for="title" class="active">Title *</label>
                                                </div>
                                                <div class="input-field col s12">
                                                    <input id="subtitle" name="subtitle" type="text" value="{{ old('subtitle', $announcement->subtitle) }}">
                                                    <label for="subtitle" class="active">Subtitle</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Editor -->
                            <section class="snow-editor">
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card">
                                            <div class="card-content">
                                                <h4 class="card-title">Main Content *</h4>
                                                <p class="mb-1">Use the toolbar to format your announcement</p>
                                                <div class="row">
                                                    <div class="col s12">
                                                        <div id="snow-wrapper">
                                                            <div id="snow-container">
                                                                <div class="quill-toolbar">
                                                                    <span class="ql-formats">
                                                                        <button class="ql-bold"></button>
                                                                        <button class="ql-italic"></button>
                                                                        <button class="ql-underline"></button>
                                                                    </span>
                                                                    <span class="ql-formats">
                                                                        <button class="ql-list" value="ordered"></button>
                                                                        <button class="ql-list" value="bullet"></button>
                                                                    </span>
                                                                    <span class="ql-formats">
                                                                        <button class="ql-align" value=""></button>
                                                                        <button class="ql-align" value="center"></button>
                                                                        <button class="ql-align" value="right"></button>
                                                                        <button class="ql-align" value="justify"></button>
                                                                    </span>
                                                                    <span class="ql-formats">
                                                                        <button class="ql-link"></button>
                                                                        <button class="ql-image"></button>
                                                                        <button class="ql-video"></button>
                                                                    </span>
                                                                    <span class="ql-formats">
                                                                        <button class="ql-formula"></button>
                                                                        <button class="ql-code-block"></button>
                                                                    </span>
                                                                    <span class="ql-formats">
                                                                        <button class="ql-clean"></button>
                                                                    </span>
                                                                </div>
                                                                <div class="editor">{!! old('content', $announcement->content) !!}</div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="content" id="content" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- CTA -->
                            <div class="row">
                                <div class="col s12">
                                    <div class="card">
                                        <div class="card-content">
                                            <h6>Call to Action</h6>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <input required id="cta_text" name="cta_text" type="text" value="{{ old('cta_text', $announcement->cta_text) }}">
                                                    <label for="cta_text" class="active">Button Text *</label>
                                                </div>
                                                <div class="col s12 m6">
                                                    <label for="cta_link">Button Link *</label>
                                                    <select class="browser-default" id="cta_link" name="cta_link" required>
                                                        <option value="">Choose destination</option>
                                                        <option value="{{ route('getRooms') }}" {{ old('cta_link', $announcement->cta_link) == route('getRooms') ? 'selected' : '' }}>Hotel Page</option>
                                                        <option value="{{ route('getApartments') }}" {{ old('cta_link', $announcement->cta_link) == route('getApartments') ? 'selected' : '' }}>Apartment Page</option>
                                                        <option value="{{ route('getLodged') }}" {{ old('cta_link', $announcement->cta_link) == route('getLodged') ? 'selected' : '' }}>Get Lodged</option>
                                                        <option value="{{ route('home') }}" {{ old('cta_link', $announcement->cta_link) == route('home') ? 'selected' : '' }}>Home Page</option>
                                                        <option value="custom" {{ !in_array(old('cta_link', $announcement->cta_link), [route('getRooms'), route('home')]) ? 'selected' : '' }}>Custom URL</option>
                                                    </select>
                                                </div>
                                                <div class="input-field col s12" id="customUrlContainer" style="display: {{ !in_array(old('cta_link', $announcement->cta_link), [route('getRooms'), route('home')]) ? 'block' : 'none' }};">
                                                    <input id="custom_cta_link" type="url" placeholder="https://example.com" value="{{ !in_array(old('cta_link', $announcement->cta_link), [route('getRooms'), route('home')]) ? old('cta_link', $announcement->cta_link) : '' }}">
                                                    <label for="custom_cta_link" class="active">Custom URL</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Border Color -->
                            <div class="row">
                                <div class="col s12">
                                    <div class="card">
                                        <div class="card-content">
                                            <h6>Visual Styling</h6>
                                            <div class="row">
                                                <div class="col s12 m6">
                                                    <label>Border Color</label>
                                                    <div style="display: flex; align-items: center; margin-top: 10px;">
                                                        <input type="color" id="border_color" name="border_color" value="{{ old('border_color', $announcement->border_color) }}" style="width: 60px; height: 40px;">
                                                        <input type="text" id="border_color_text" value="{{ old('border_color', $announcement->border_color) }}" style="margin-left: 10px; width: 120px; padding: 5px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Publication Status -->
                            <div class="row">
                                <div class="col s12">
                                    <div class="card">
                                        <div class="card-content">
                                            <h6>Publication Status</h6>
                                            <div class="switch">
                                                <label>
                                                    <span class="grey-text">Unpublished</span>
                                                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                                    <span class="lever"></span>
                                                    <span class="green-text">Published</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col s12">
                                    <button class="btn-large  accent-1 right" type="submit">
                                        <i class="material-icons left">save</i>
                                        Update Announcement
                                    </button>
                                    <a href="{{ route('announcements') }}" class="btn-large grey right mr-2">Cancel</a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/highlight.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/quill.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/form-editor.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var editorEl = document.querySelector('#snow-container .editor');
                if (editorEl && editorEl.__quill) {
                    var editor = editorEl.__quill;

                    // FORCE ENABLE
                    editor.enable(true);
                    console.log('✅ Editor enabled');

                    if (typeof M !== 'undefined') {
                        M.updateTextFields();
                    }

                    // Color picker
                    const borderColorPicker = document.getElementById('border_color');
                    const borderColorText = document.getElementById('border_color_text');
                    if (borderColorPicker && borderColorText) {
                        borderColorPicker.addEventListener('input', function() {
                            borderColorText.value = this.value;
                        });
                        borderColorText.addEventListener('input', function() {
                            if(/^#[0-9A-F]{6}$/i.test(this.value)) {
                                borderColorPicker.value = this.value;
                            }
                        });
                    }

                    // CTA Link
                    const ctaLinkSelect = document.getElementById('cta_link');
                    const customUrlContainer = document.getElementById('customUrlContainer');
                    const customCtaLink = document.getElementById('custom_cta_link');

                    function toggleCustomUrl() {
                        if(ctaLinkSelect && ctaLinkSelect.value === 'custom') {
                            customUrlContainer.style.display = 'block';
                        } else {
                            customUrlContainer.style.display = 'none';
                        }
                    }

                    if (ctaLinkSelect) {
                        ctaLinkSelect.addEventListener('change', toggleCustomUrl);
                        // Initial check on page load
                        toggleCustomUrl();
                    }

                    // Form submit
                    const form = document.getElementById('formValidate0');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            const content = document.querySelector('input[name=content]');
                            content.value = editor.root.innerHTML;

                            if (editor.getText().trim().length === 0) {
                                e.preventDefault();
                                if (typeof M !== 'undefined') {
                                    M.toast({html: 'Please add content', classes: 'red'});
                                } else {
                                    alert('Please add content');
                                }
                                return false;
                            }

                            // Handle custom URL
                            if(ctaLinkSelect && ctaLinkSelect.value === 'custom') {
                                ctaLinkSelect.removeAttribute('name');
                                if(customCtaLink) customCtaLink.setAttribute('name', 'cta_link');
                            } else {
                                if(customCtaLink) customCtaLink.removeAttribute('name');
                                if(ctaLinkSelect) ctaLinkSelect.setAttribute('name', 'cta_link');
                            }
                        });
                    }

                    console.log('✅ All handlers initialized');
                }
            }, 2000);
        });
    </script>
@endsection
