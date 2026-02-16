@extends('admin.admin_master')
@section('admin')

    @section('styles')
        {{--        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/flag-icon/css/flag-icon.min.css') }}">--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/katex.min.css') }}">
{{--        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/monokai-sublime.min.css') }}">--}}

    @endsection
    @php
        $pageTitle = 'Create Announcement';
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
                                <li class="breadcrumb-item"><a href="{{ route('announcements') }}">Announcements</a></li>
                                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div><br>
        <!-- BEGIN: Page Main-->

    <div class="col s12">
        <div class="container">
            <div class="section">
    <!-- Snow Editor start -->

    <section class="snow-editor">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12">
                                <div id="snow-wrapper">
                                    <div id="snow-container">
                                        <div class="quill-toolbar">
                      <span class="ql-formats">
                        <select class="ql-header browser-default">
                          <option value="1">Heading</option>
                          <option value="2">Subheading</option>
                          <option selected>Normal</option>
                        </select>
                        <select class="ql-font browser-default">
                          <option selected>Sailec Light</option>
                          <option value="sofia">Sofia Pro</option>
                          <option value="slabo">Slabo 27px</option>
                          <option value="roboto">Roboto Slab</option>
                          <option value="inconsolata">Inconsolata</option>
                          <option value="ubuntu">Ubuntu Mono</option>
                        </select>
                      </span>
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
                                        <div class="editor">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Snow Editor end -->
            </div>
        </div>
    </div>
        </div>
    </div>
    <!-- END: Page Main-->

@endsection




@section('vendor_scripts')
    <!-- Load Quill dependencies -->
    <script src="{{ asset('admin/assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/highlight.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/quill.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/form-editor.js') }}"></script>
@endsection
