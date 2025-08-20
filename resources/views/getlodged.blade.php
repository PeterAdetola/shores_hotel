@extends('mainframe')
@section('content')
    @push('style')
        <style>
            /* Add this CSS to fix image loading placeholder issues */
            .mil-card-cover {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 250px; /* Adjust height as needed */
                background: #f5f5f5; /* Light background instead of placeholder image */
                border: none !important;
                outline: none !important;
                aspect-ratio: 16/9;
            }

            .mil-card-cover img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover;
                object-position: center;
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                display: block;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 2;
            }

            /* Loading state for images */
            .mil-card-cover::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 40px;
                height: 40px;
                margin: -20px 0 0 -20px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #ccc;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                z-index: 1;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Hide loading spinner when image is loaded */
            .mil-card-cover img[style*="opacity: 1"] ~ .mil-card-cover::before,
            .mil-card-cover.loaded::before {
                display: none;
            }

            /* Ensure swiper slides don't have borders */
            .mil-card-slider .swiper-slide {
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            .mil-card-slider .swiper-wrapper {
                border: none !important;
                outline: none !important;
            }

            .mil-card-slider {
                border: none !important;
                outline: none !important;
                overflow: hidden;
            }

            /* Remove any default borders from the card */
            .mil-card {
                border: none !important;
                outline: none !important;
            }

            /* Alternative approach: if you want to completely hide the container until loaded */
            .mil-card-cover.loading {
                opacity: 0.7;
            }

            .mil-card-cover.loaded {
                opacity: 1;
                transition: opacity 0.3s ease;
            }
        </style>
    @endpush

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
                            <h1 class="mil-mb-40">Choose the lodging <br>of your dreams</h1>
                            <div class="mil-suptitle mil-breadcrumbs">
                                <ul>
                                    <li><a href="home-1.html">Home</a></li>
                                    <li><a href="search.html">Search room</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- banner end -->

    <!-- search panel -->
    <div class="mil-content-pad mil-search-window">
        <div class="container">
            @include('index.partials.search_panel')
        </div>
    </div>
    <!-- search panel end -->

    <!-- rooms -->
    <div class="mil-rooms mil-p-100-100">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 110%; bottom: 15%; left: -30%; opacity: .2" alt="shape">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 85%; bottom: -20%; right: -25%; transform: rotate(-30deg) scaleX(-1);" alt="shape">
        <div class="container">

            <div class="row allign-items-center mil-mb-100">
                <div class="col-xl-4">

{{--                    <h2 class="mil-row-title mil-fade-up">Filter by <span class="mil-badge">24</span></h2>--}}
                    <h2 class="mil-row-title mil-fade-up">Filter by</h2>

                </div>
                <div class="col-xl-8">

                    <div class="mil-desctop-right mil-fade-up">
                        <div class="mil-filter">
                            <a href="#." class="mil-active">All</a>
                            <a href="#.">Rooms</a>
                            <a href="#.">Apartments</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mil-mb-40">
                @forelse (getAllAccommodation() as $room)
                    <div class="col-md-6 col-xl-4">
                        <div class="mil-card mil-mb-40-adapt mil-fade-up">
                            <div class="swiper-container mil-card-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($room->galleryImages as $img)
                                        <div class="swiper-slide">
                                            <div class="mil-card-cover">
                                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="cover"
                                                     data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mil-card-nav">
                                    <div class="mil-slider-btn mil-card-prev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-arrow-right">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </div>
                                    <div class="mil-slider-btn mil-card-next">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-arrow-right">
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
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z"
                                                    stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path
                                                    d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z"
                                                    stroke="black" stroke-width="1.00189" stroke-miterlimit="10"/>
                                                <path
                                                    d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z"
                                                    stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path
                                                    d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045"
                                                    stroke="black" stroke-width="1.00189" stroke-miterlimit="10"
                                                    stroke-linecap="round"/>
                                            </g>
                                            <defs>
                                                <clipPath>
                                                    <rect width="16.0035" height="16.0035" fill="white"
                                                          transform="translate(0.176514 0.504028)"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <div>Adults: {{ $room->adult_max }}</div>
                                </li>
                                <li>
                                    <div class="mil-icon">
                                        <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                             width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000"
                                             preserveAspectRatio="xMidYMid meet">

                                            <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                               fill="#000000" stroke="none">
                                                <path d="M1030 4891 c0 -5 5 -44 11 -87 22 -159 101 -316 215 -428 63 -61 174
-135 237 -157 20 -7 37 -16 37 -19 0 -4 -19 -17 -42 -30 -136 -75 -298 -201
-434 -337 -206 -207 -370 -459 -476 -735 -32 -86 -45 -108 -60 -108 -34 0
-151 -51 -220 -97 -295 -194 -384 -592 -201 -898 79 -130 229 -246 378 -291
33 -10 61 -19 63 -20 2 -1 18 -43 37 -92 46 -124 146 -318 223 -431 89 -133
163 -220 288 -342 316 -307 694 -496 1149 -576 143 -24 500 -25 646 0 311 52
592 161 839 324 145 96 237 172 361 299 218 225 370 468 482 775 16 42 21 46
66 58 112 29 249 114 326 203 56 66 101 145 131 233 24 70 28 96 28 200 1 99
-3 133 -22 195 -47 152 -142 279 -270 364 -69 45 -186 96 -220 96 -15 0 -27
21 -56 99 -186 489 -553 900 -1020 1140 -290 149 -627 231 -950 231 -220 0
-309 27 -416 127 -71 65 -116 149 -132 246 l-11 67 -148 0 c-171 0 -158 10
-138 -109 14 -89 44 -174 87 -251 20 -36 38 -68 40 -72 5 -12 -96 -10 -162 3
-89 16 -164 56 -232 124 -70 69 -108 142 -124 238 l-12 67 -149 0 c-85 0 -149
-4 -149 -9z m1672 -751 c355 -109 465 -553 203 -815 -153 -153 -374 -187 -575
-87 -137 68 -259 256 -260 400 l0 32 -150 0 -150 0 0 -40 c0 -118 60 -295 139
-407 101 -143 239 -246 411 -305 90 -30 102 -32 240 -32 138 0 150 2 240 32
121 42 214 97 301 179 160 151 249 356 249 573 0 90 -21 207 -51 283 -11 27
-18 52 -15 54 9 10 243 -119 336 -185 123 -87 336 -300 422 -422 73 -104 181
-304 223 -415 14 -38 39 -115 54 -170 l28 -100 97 -7 c148 -11 245 -61 311
-161 102 -156 79 -346 -56 -471 -72 -67 -135 -92 -259 -103 l-93 -8 -37 -125
c-43 -147 -62 -195 -129 -329 -302 -602 -942 -992 -1624 -991 -679 1 -1317
392 -1618 991 -67 134 -86 182 -129 329 l-37 125 -93 8 c-124 11 -187 36 -259
103 -85 78 -116 151 -116 269 1 77 5 98 28 145 41 86 89 135 170 175 62 31 86
37 171 43 l99 7 28 100 c48 172 102 301 184 440 324 551 913 898 1531 904 96
1 136 -3 186 -19z"/>
                                                <path d="M1640 2769 c-109 -12 -196 -53 -282 -135 -86 -82 -148 -211 -148
-310 l0 -45 147 3 147 3 9 44 c12 60 78 126 138 138 58 12 124 -1 166 -33 38
-29 73 -91 73 -129 l0 -25 150 0 150 0 0 38 c0 89 -56 217 -128 295 -114 121
-257 174 -422 156z"/>
                                                <path d="M3360 2769 c-116 -12 -214 -63 -302 -156 -74 -80 -128 -204 -128
-295 l0 -38 150 0 150 0 0 25 c0 38 35 100 73 129 42 32 108 45 166 33 60 -12
126 -78 138 -138 l9 -44 147 -3 147 -3 0 44 c0 167 -148 361 -323 423 -59 21
-151 30 -227 23z"/>
                                                <path d="M1973 1596 c-67 -39 -125 -75 -129 -79 -13 -12 106 -156 179 -217 86
-72 222 -142 332 -171 112 -30 298 -30 409 0 178 47 340 153 452 295 35 44 63
84 61 89 -1 4 -58 41 -126 82 l-124 74 -20 -27 c-216 -288 -599 -318 -838 -66
-25 27 -52 59 -60 71 -14 21 -15 21 -136 -51z"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <div>Children: {{ $room->children_max }}</div>
                                </li>
                            </ul>
                            <div class="mil-descr">
                                <h3 class="mil-mb-20">{{ $room->category->name ?? '—' }}</h3>
                                {{--<p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta sunt, explicabo</p>--}}
                                <div class="mil-divider"></div>
                                <div class="mil-card-bottom">
                                        <?php
                                        $price = $room->price_per_night;
                                        $formatted_price = number_format($price, 2, '.', ',');
                                        ?>
                                    <div class="mil-price"><span class="mil-symbol">₦</span><span class="mil-number" style="font-size: 1.2em">{{ $formatted_price }}</span>/per night
                                    </div>
                                    <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-bookmark">
                                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-6 col-xl-4">

                        <div class="mil-card mil-mb-40-adapt mil-fade-up">
                            <div class="swiper-container mil-card-slider">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="mil-card-cover">
                                            <img src="{{ asset('img/rooms/1.1.jpg') }}" alt="cover"
                                                 data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="mil-card-cover">
                                            <img src="{{ asset('img/rooms/1.2.jpg') }}" alt="cover"
                                                 data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="mil-card-cover">
                                            <img src="{{ asset('img/rooms/1.3.jpg') }}" alt="cover"
                                                 data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                                        </div>
                                    </div>
                                </div>
                                <div class="mil-card-nav">
                                    <div class="mil-slider-btn mil-card-prev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-arrow-right">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </div>
                                    <div class="mil-slider-btn mil-card-next">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-arrow-right">
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
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z"
                                                    stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path
                                                    d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z"
                                                    stroke="black" stroke-width="1.00189" stroke-miterlimit="10"/>
                                                <path
                                                    d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z"
                                                    stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path
                                                    d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045"
                                                    stroke="black" stroke-width="1.00189" stroke-miterlimit="10"
                                                    stroke-linecap="round"/>
                                            </g>
                                            <defs>
                                                <clipPath>
                                                    <rect width="16.0035" height="16.0035" fill="white"
                                                          transform="translate(0.176514 0.504028)"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <div>Adults: 4</div>
                                </li>
                                <li>
                                    <div class="mil-icon">
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062"
                                                stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578"
                                                stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062"
                                                stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path
                                                d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898"
                                                stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div>Size: 95ft²</div>
                                </li>
                            </ul>
                            <div class="mil-descr">
                                <h3 class="mil-mb-20">Ocean View</h3>
                                <p class="mil-mb-40">Accusantium doloremque laudantium, totam rem aperiam beatae vitae dicta
                                    sunt, explicabo</p>
                                <div class="mil-divider"></div>
                                <div class="mil-card-bottom">
                                    <div class="mil-price"><span class="mil-symbol">$</span><span
                                            class="mil-number">49</span>/per night
                                    </div>
                                    <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-bookmark">
                                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- rooms end -->


@endsection
@push('scripts')
    <script src="js/filter.js"></script>
@endpush
