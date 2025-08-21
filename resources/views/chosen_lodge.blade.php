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
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="room" data-swiper-parallax="0" data-swiper-parallax-scale="1.2">
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
                <div class="col-xl-4" data-sticky-container>
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
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9578 14.6084H12.7089C13.1733 14.6084 13.6187 14.4239 13.9471 14.0955C14.2755 13.7671 14.46 13.3217 14.46 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.46 6.10644V4.35534C14.46 3.89092 14.2755 3.44553 13.9471 3.11713C13.6187 2.78874 13.1733 2.60425 12.7089 2.60425H10.9578" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.95898 14.6084H4.20788C3.74346 14.6084 3.29806 14.4239 2.96967 14.0955C2.64128 13.7671 2.45679 13.3217 2.45679 12.8573V11.1062" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.45679 6.10644V4.35534C2.45679 3.89092 2.64128 3.44553 2.96967 3.11713C3.29806 2.78874 3.74346 2.60425 4.20788 2.60425H5.95898" stroke="black" stroke-width="1.00189" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>Children: {{ $room->children_max ?? 2 }}</div>
                            </li>
                        </ul>

                        <div class="mil-book-window">
                            <form>
                                <div class="mil-field-frame mil-mb-20">
                                    <label>Check-in</label>
                                    <input id="check-in" type="text" class="datepicker-here" data-position="bottom left" placeholder="Select date" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="mil-field-frame mil-mb-20">
                                    <label>Check-out</label>
                                    <input id="check-out" type="text" class="datepicker-here" data-position="bottom left" placeholder="Select date" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="mil-field-frame mil-mb-20">
                                    <label>Adults</label>
                                    <input type="text" placeholder="Enter quantity" value="1" max="{{ $room->adult_max ?? 4 }}">
                                </div>
                                <div class="mil-field-frame mil-mb-20">
                                    <label>Children</label>
                                    <input type="text" placeholder="Enter quantity" value="0" max="{{ $room->children_max ?? 2 }}">
                                </div>
                                <button type="submit" class="mil-button mil-accent-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
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

    {{-- Debug information (remove in production) --}}
    @if(config('app.debug'))
        <script>
            console.log('Room data:', @json($room ?? null));
            console.log('Category data:', @json($category ?? null));
        </script>
    @endif

@endsection
