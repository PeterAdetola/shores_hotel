@extends('mainframe')
@section('content')
    @include('index.partials.about1')
    <div id="gallery" class="container">
        <div class="mil-text-center">
            <div class="mil-suptitle mil-mb-20 mil-fade-up">Gallery</div>
            <h2 class="mil-mb-100 mil-fade-up">Shores Hotel at a glance</h2>
        </div>
    </div>
    <div class="mil-slider-frame mil-mb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="swiper-container mil-room-slider">
                        <div class="swiper-wrapper">
                            @foreach($citibarContent['slider'] as $slide)
                                <div class="swiper-slide">
                                    <div class="mil-image-frame">
                                        <img src="{{ asset($slide) }}" alt="room" data-swiper-parallax="0" data-swiper-parallax-scale="1.2">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mil-room-nav">
                        <div class="mil-slider-btn mil-room-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>
                        <div class="mil-slider-btn mil-room-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="mil-room-pagination"></div>
                </div>
            </div>
        </div>
    </div>
    @include('index.partials.about2')
@endsection
