<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    {{-- Dynamic description and keywords --}}
    <meta name="description" content="@yield('meta_description', 'Pacmedia - Tactical Digital Solutions. Brand Strategy, Development & Intelligent Automation.')">
    <meta name="keywords" content="@yield('meta_keywords', 'brand strategy, digital experience design, web development, AI automation systems, brand identity systems, conversion-focused design, custom development, intelligent customer operations, digital presence strategy, tactical digital solutions')">
    <meta name="author" content="Pacmedia Creatives">

    {{-- Favicons - using specific ones from login/register --}}
    <link rel="apple-touch-icon" href="{{ asset('admin/assets/images/favicon/icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/assets/images/favicon/icon_bg.png') }}">

    {{-- Title - dynamic based on page --}}
    <title>@yield('title', 'Authentication') | Pacmedia Creatives</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/themes/vertical-modern-menu-template/style.css') }}">
    {{-- Specific page CSS, yielded here (CORRECTED) --}}
    @hasSection('page_css')
        <link rel="stylesheet" type="text/css" href="@yield('page_css')">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/custom/custom.css') }}">
    {{-- Inline styles - yielded here --}}
    <style type="text/css">
        form:invalid button {
            pointer-events: none;
            /*opacity: .8;*/
        }
        @yield('inline_styles')
    </style>
</head>
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 1-column blank-page blank-page @yield('body_class')" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
<div class="row">
    <div class="col s12">
        <div class="container">
            @yield('content')
        </div>
        <div class="content-overlay"></div>
    </div>
</div>

<script src="{{ asset('admin/assets/js/vendors.min.js') }}"></script>
@yield('page_vendor_js')
<script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
<script src="{{ asset('admin/assets/js/search.js') }}"></script>
<script src="{{ asset('admin/assets/js/custom/custom-script.js') }}"></script>
@yield('page_level_js')
{{-- Inline scripts - yielded here --}}
<script type='text/javascript'>
    function ShowPreloader() {
        document.getElementById('preloader').style.display = "block";
    }
    @yield('inline_scripts')
</script>
</body>
</html>
