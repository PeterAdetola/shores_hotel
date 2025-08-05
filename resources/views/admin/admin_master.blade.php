<!DOCTYPE html>

@if (Auth::guest())
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif
@php
    $route = Route::current()->getName()
@endphp

<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Pacmedia - Tactical Digital Solutions. Brand Strategy, Development & Intelligent Automation.">
    <meta name="keywords" content="brand strategy, digital experience design, web development, AI automation systems, brand identity systems, conversion-focused design, custom development, intelligent customer operations, digital presence strategy, tactical digital solutions">
    <meta name="author" content="Pacmedia Creatives">
    <title>Web Editor</title>
    <link rel="apple-touch-icon" href="{{ asset('admin/assets/images/favicon/pacmediac_logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/assets/images/favicon/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/animate-css/animate.css') }}">
    @yield('vendor_styles')
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/themes/vertical-modern-menu-template/style.css') }}">
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/dashboard-modern.css') }}">--}}
    @yield('styles')
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-invoice.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/page-users.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/page-account-settings.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/advance-ui-media.css') }}">--}}

    @yield('headScript')
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/custom/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/custom/toast.css') }}">
    <!-- END: Custom CSS-->
    <style type="text/css">
        form:invalid button {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>
<!-- END: Head-->
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">


@include('admin.component.header')



@include('admin.component.sidebar')

<!-- BEGIN: Page Main-->

@yield('admin')



<!-- BEGIN: Footer-->

@include('admin.component.footer')

<!-- END: Footer-->
{{--<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>--}}

<!-- BEGIN VENDOR JS-->
<script src="{{ asset('admin/assets/js/vendors.min.js') }}"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
@yield('vendor_scripts')
<!-- END PAGE VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
{{--<script src="{{ asset('admin/assets/js/search.js') }}"></script>--}}
{{--<script src="{{ asset('admin/assets/js/custom/custom-script.js') }}"></script>--}}
{{--<script src="{{ asset('admin/assets/js/custom/sweetalert.min.js') }}"></script>--}}
{{--<script src="{{ asset('admin/assets/js/custom/sweetalert_init.js') }}"></script>--}}
<!-- END THEME  JS-->
<!-- BEGIN PAGE LEVEL JS-->
{{--<script src="{{ asset('admin/assets/js/scripts/dashboard-modern.js') }}"></script>--}}
<script src="{{ asset('admin/assets/js/scripts/advance-ui-modals.js') }}"></script>
{{--<script src="{{ asset('admin/assets/js/scripts/ui-alerts.js') }}"></script>--}}
{{--<script src="{{ asset('admin/assets/js/scripts/app-invoice.js') }}"></script>--}}
{{--<script src="{{ asset('admin/assets/js/scripts/intro.js') }}"></script>--}}
@yield('scripts')

<!-- END PAGE LEVEL JS-->






    @if(Session::has('message'))
        <script>
    setTimeout(function () {
        var toastHTML = "{{ Session::get('message') }}";
        M.toast({html: toastHTML})
    }, 2000);
        </script>
    @endif


</body>
</html>
