@extends('mainframe')
@section('content')

    <!-- banner -->
    @include('index.partials.hero')
    <!-- banner end -->

    <!-- notice -->
    @include('index.partials.notice')
    <!-- notice end -->

    <!-- services -->
    @include('index.partials.services')
    <!-- services end -->

    <!-- features -->
    @include('index.partials.features')
    <!-- features end -->

    <!-- rooms -->
    @include('index.partials.rooms')
    <!-- rooms end -->

    <!-- call to action -->
    @include('index.partials.cta')
    <!-- call to action end -->

    <!-- about 1 -->
    @include('index.partials.about1')
    <!-- about 1 end -->

    <!-- about 2 -->
    @include('index.partials.about2')
    <!-- about 2 end -->

    <!-- reviews -->
{{--    @include('index.partials.reviews')--}}
    <!-- reviews end -->

    <!-- blog -->
{{--    @include('index.partials.blog')--}}
    <!-- blog end -->

@endsection
