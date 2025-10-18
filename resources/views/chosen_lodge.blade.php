@extends('mainframe')
@section('content')

    <!-- banner -->
    <div class="mil-p-100-60">
        <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape" style="width: 70%; top: 0; right: -12%; transform: rotate(180deg)" alt="shape">
        <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape" style="width: 80%; bottom: -12%; right: -22%; transform: rotate(0deg) scaleX(-1);" alt="shape">
        <div class="container">
            <div class="mil-banner-head">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-xl-6">
                        <h1 class="mil-h2-lg mil-mb-40">{{ $category->name ?? 'Room Category' }}</h1>
                    </div>
                    <div class="col-lg-6 col-xl-6">
                        <div class="mil-desctop-right mil-right-no-m mil-fade-up mil-mb-40">
                            <div class="mil-suptitle mil-breadcrumbs mil-light">
                                <ul>
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ route('chosen_lodge', ['categorySlug' => $category->slug ?? 'room', 'roomId' => $room->id ?? 1]) }}">{{ $category->name ?? 'Room' }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- room info -->
    <div class="mil-info">
        <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape mil-fade-up" style="width: 110%; bottom: 15%; left: -30%; opacity: .2" alt="shape">
        <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape mil-fade-up" style="width: 85%; bottom: -20%; right: -30%; transform: rotate(-30deg) scaleX(-1);" alt="shape">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-8">

                    <!-- room slider -->
                    <div class="mil-slider-frame mil-frame-2 mil-mb-100">
                        <div class="swiper-container mil-room-slider" style="overflow: hidden">
                            <div class="swiper-wrapper">
                                @forelse($room->galleryImages ?? [] as $image)
                                    <div class="swiper-slide">
                                        <div class="mil-image-frame">
                                            <img src="{{ asset('uploads/' . $image->image_path) }}" alt="room" data-swiper-parallax="0" data-swiper-parallax-scale="1.2">
                                        </div>
                                    </div>
                                @empty
                                    <!-- Fallback images if no gallery images -->
                                    <div class="swiper-slide">
                                        <div class="mil-image-frame">
                                            <img src="{{ asset('img/rooms/default.jpg') }}" alt="room" data-swiper-parallax="0" data-swiper-parallax-scale="1.2">
                                        </div>
                                    </div>
                                @endforelse
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
                        <div class="mil-room-pagination" style="bottom: 8px"></div>
                    </div>
                    <!-- room slider end -->

                    <!-- features -->
                    <div class="row mil-mb-60-adapt">
                        <div class="col-12">
                            <h3 class="mil-fade-up mil-mb-40">Key features</h3>
                        </div>
                        @forelse($room->facilities ?? [] as $facility)
                            <div class="col-xl-4">
                                <div class="mil-iconbox mil-iconbox-sm mil-mb-40-adapt mil-fade-up">
                                    <div class="mil-bg-icon"></div>
                                    <div class="mil-icon mil-icon-fix">
                                        <span class="material-symbols-outlined light-text">{{ $facility->icon }}</span>
                                    </div>
                                    <h5 class="grey-text">{{ $facility->name ?? 'Facility' }}</h5>
                                </div>
                            </div>
                        @empty
                            <!-- Default facilities if none exist -->
                            <div class="col-xl-4">
                                <div class="mil-iconbox mil-iconbox-sm mil-mb-40-adapt mil-fade-up">
                                    <div class="mil-bg-icon"></div>
                                    <div class="mil-icon mil-icon-fix">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="15" cy="15" r="14" stroke="#22BCEC" stroke-width="2" />
                                        </svg>
                                    </div>
                                    <h5>Basic Amenities</h5>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- features end -->

                    <!-- description -->
                    <div class="row">
                        <div class="col-xl-11">
                            <div class="mil-dercription mil-mb-100">
                                <h3 class="mil-fade-up mil-mb-40">Description of the room</h3>
                                @if($room->description ?? false)
                                    <p class="mil-fade-up mil-fade-up mil-mb-20">{{ $room->description }}</p>
                                @else
                                    <p class="mil-fade-up mil-fade-up mil-mb-20">Vestibulum lectus mauris ultrices eros. Pharetra massa massa ultricies mi quis hendrerit dolor magna eget. Sit amet nisl purus in mollis nunc sed. Aenean euismod elementum nisi quis eleifend.</p>
                                    <p class="mil-fade-up mil-mb-20">Pulvinar neque laoreet suspendisse interdum consectetur libero. Urna id volutpat lacus laoreet non curabitur gravida arcu ac. Varius morbi enim nunc faucibus. Ornare suspendisse sed nisi lacus sed viverra tellus in.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- description end -->

                    <!-- amenity -->
                    <div class="row mil-mb-60-adapt">
                        <div class="col-12">
                            <h3 class="mil-fade-up mil-mb-40">Amenity</h3>
                        </div>


                        <!-- Default amenities if none exist -->
                        <div class="col-xl-6">
                            <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                                <div class="mil-bg-icon"></div>
                                <div class="mil-icon mil-icon-fix">
                                    <span class="material-symbols-outlined light-text">fluorescent</span>
                                </div>
                                <h3 class="mil-mb-20 grey-text">Enchanting Atmosphere</h3>
                                <p>Escape into an alluring, vibrant, and elegant ambiance.</p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                                <div class="mil-bg-icon"></div>
                                <div class="mil-icon mil-icon-fix">
                                    <span class="material-symbols-outlined light-text">electric_bolt</span>
                                </div>
                                <h3 class="mil-mb-20 grey-text">24hrs Electricity</h3>
                                <p>Enjoy uninterrupted comfort and convenience with our reliable power.</p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                                <div class="mil-bg-icon"></div>
                                <div class="mil-icon mil-icon-fix">
                                    <span class="material-symbols-outlined light-text">garage</span>
                                </div>
                                <h3 class="mil-mb-20 grey-text">Ample Parking Space</h3>
                                <p>Effortless parking is always available for our guests.</p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                                <div class="mil-bg-icon"></div>
                                <div class="mil-icon mil-icon-fix">
                                    <span class="material-symbols-outlined light-text">speed_camera</span>
                                </div>
                                <h3 class="mil-mb-20 grey-text">CCTV</h3>
                                <p>We ensure your safety and security with 24/7 surveillance.</p>
                            </div>
                        </div>

                    </div>
                    <!-- amenity end -->

                    <!-- map -->
                    {{--                    <div>--}}
                    {{--                        <h3 class="mil-fade-up mil-mb-40">Location</h3>--}}
                    {{--                        <div class="mil-map-frame mil-fade-up mil-mb-100">--}}
                    {{--                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6393.16599479736!2d-2.430872248702666!3d36.81379446199894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7a9db1a1de493f%3A0xc6d411d11ab69d33!2sParque%20de%20Bici!5e0!3m2!1suk!2suk!4v1701096541420!5m2!1suk!2suk" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <!-- map end -->

                </div>
                <!-- sidebar -->
                <div class="col-xl-4 space" data-sticky-container>
                    <div class="mil-sticky mil-stycky-right mil-p-0-100" data-margin-top="140">
                        <div class="mil-price-frame mil-mb-20">
                            <div class="mil-price">
                                <span class="mil-symbol">NGN</span>
                                <span class="mil-number">{{ number_format($room->price_per_night ?? 100000) }}</span>/per night
                            </div>
                        </div>

                        <ul class="mil-parameters mil-mb-20">
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
                                <div>Adults: {{ $room->adult_max ?? 4 }}</div>
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
                                <div>Children: {{ $room->children_max ?? 2 }}</div>
                            </li>
                        </ul>

                        <div class="mil-book-window">
                            <form method="POST" action="{{ route('make.booking') }}">
                                @csrf

                                {{-- Hidden fields to carry room info --}}
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="lodging_type" value="{{ $room->category->name }}">

                                <div class="mil-field-frame mil-mb-20">
                                    <label>Check-in</label>
                                    <input name="check_in" id="check-in" type="text"
                                           class="datepicker-here"
                                           data-position="bottom left"
                                           placeholder="Select date"
                                           autocomplete="off"
                                           readonly required>
                                </div>

                                <div class="mil-field-frame mil-mb-20">
                                    <label>Check-out</label>
                                    <input name="check_out" id="check-out" type="text"
                                           class="datepicker-here"
                                           data-position="bottom left"
                                           placeholder="Select date"
                                           autocomplete="off"
                                           readonly required>
                                </div>

                                <div class="mil-field-frame mil-mb-20">
                                    <label>Adults</label>
                                    <input name="adults" type="number"
                                           placeholder="Enter quantity"
                                           value="1"
                                           min="1"
                                           max="{{ $room->adult_max ?? 4 }}" required>
                                </div>

                                <div class="mil-field-frame mil-mb-20">
                                    <label>Children</label>
                                    <input name="children" type="number"
                                           placeholder="Enter quantity"
                                           value="0"
                                           min="0"
                                           max="{{ $room->children_max ?? 2 }}" required>
                                </div>

                                <button type="submit" class="mil-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-bookmark">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span>Book now</span>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- sidebar end -->
            </div>
        </div>
    </div>
    <!-- room info end -->



@endsection
