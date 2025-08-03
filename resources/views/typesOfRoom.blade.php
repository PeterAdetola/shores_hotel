@extends('mainframe')
@section('content')
    <!-- banner -->
    <div class="mil-banner-sm">
        <img src="img/shapes/4.png" class="mil-shape" style="width: 70%; top: 0; right: -35%; transform: rotate(190deg)" alt="shape">
        <img src="img/shapes/4.png" class="mil-shape" style="width: 70%; bottom: -12%; left: -30%; transform: rotate(40deg)" alt="shape">
        <img src="img/shapes/4.png" class="mil-shape" style="width: 110%; top: -5%; left: -30%; opacity: .3" alt="shape">
        <div class="container">
            <div class="mil-banner-img-4">
                <img src="img/shapes/1.png" alt="object" class="mil-figure mil-1">
                <img src="img/shapes/2.png" alt="object" class="mil-figure mil-2">
                <img src="img/shapes/3.png" alt="object" class="mil-figure mil-3">
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-6">

                    <div class="mil-banner-content-frame">
                        <div class="mil-banner-content mil-text-center">
                            <h1 class="mil-mb-40">Choose the room <br>of your dreams</h1>
                            <div class="mil-suptitle mil-breadcrumbs">
                                <ul>
                                    <li><a href="{{ url('/')  }}">Home</a></li>
                                    <li><a href="search.html">Our Rooms</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- banner end -->



    <!-- rooms -->
    <div class="mil-rooms mil-p-100-100">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 110%; bottom: 15%; left: -30%; opacity: .2" alt="shape">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 85%; bottom: -20%; right: -25%; transform: rotate(-30deg) scaleX(-1);" alt="shape">
        <div class="container">

            <div class="row allign-items-center mil-mb-100">
                <div class="col-xl-4">

                    <h2 class="mil-row-title mil-fade-up grey-text">Our Rooms <span class="mil-badge">24</span></h2>

                </div>
{{--                <div class="col-xl-8">--}}

{{--                    <div class="mil-desctop-right mil-fade-up">--}}
{{--                        <div class="mil-filter">--}}
{{--                            <a href="#." class="mil-active">All</a>--}}
{{--                            <a href="#.">Economy</a>--}}
{{--                            <a href="#.">Standard</a>--}}
{{--                            <a href="#.">Luxe</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
            </div>

            <div class="row mil-mb-40">
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Ocean View</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/2.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/2.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Sunset Suite</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.4.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Beachfront Bliss</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/4.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/4.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/4.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/4.4.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Coastal Retreat</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/5.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/5.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/5.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Seaside Haven</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover"></div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Tropical Escape</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/2.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/2.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Sunset Suite</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/3.4.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Beachfront Bliss</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-4">

                    <div class="mil-card mil-mb-40-adapt mil-fade-up">
                        <div class="swiper-container mil-card-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.1.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.2.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="mil-card-cover">
                                        <img src="img/rooms/1.3.jpg" alt="cover" data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mil-card-nav">
                                <div class="mil-slider-btn mil-card-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                                <div class="mil-slider-btn mil-card-next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="mil-card-pagination"></div>
                        </div>
                        <ul class="mil-parameters">
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" />
                                            <path d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045" stroke="black" stroke-width="1.00189" stroke-miterlimit="10" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath>
                                                <rect width="16.0035" height="16.0035" fill="white" transform="translate(0.176514 0.504028)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div>Adults: 4</div>
                            </li>
                            <li>
                                <div class="mil-icon">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Size: 95ft²</div>
                            </li>
                        </ul>
                        <div class="mil-descr">
                            <h3 class="mil-mb-20">Ocean View</h3>
                            <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>
                            <div class="mil-divider"></div>
                            <div class="mil-card-bottom">
                                <div class="mil-price"><span class="mil-symbol">$</span><span class="mil-number">49</span>/per night</div>
                                <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-lg-7">
                    <p class="mil-fade-up">Accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo.</p>
                </div>
                <div class="col-lg-5">
                    <div class="mil-desctop-right mil-fade-up">

                        <div class="mil-pagination">
                            <div class="mil-page-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                            <div class="mil-page-dot mil-active">1</div>
                            <div class="mil-page-dot">2</div>
                            <div class="mil-page-dot">3</div>
                            <div class="mil-page-dot">4</div>
                            <div class="mil-page-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- rooms end -->
@endsection
