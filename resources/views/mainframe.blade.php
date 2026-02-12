<!DOCTYPE html>

<html lang="eng">
@php
    $contact = getContactContent();
    $socials = $contact['social'] ?? [];
    $route = Route::current()->getName();
    $rooms = getAllRooms();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">


    <link rel="icon" href="{{ asset('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />


    <!-- bootstrap grid css -->
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-grid.css') }}">
    <!-- swiper css -->
    <link rel="stylesheet" href="{{ asset('css/plugins/swiper.min.css') }}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ asset('css/plugins/datepicker.css') }}">
    <!-- aquarelle css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- page name -->
    <title>Shores Hotel</title>
<style>
    @media (max-width: 768px) {
        .space {
            margin-bottom: 5.6em;
        }
    }
</style>
</head>

<body>
<!-- wrapper -->
<div class="mil-wrapper">

    <!-- preloader -->
    <div class="mil-loader mil-active">
        <div class="mil-loader-content">
            <div class="mil-loader-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>
            <div class="mil-loader-progress">
                <div class="mil-loader-bar"></div>
                <div class="mil-loader-percent">0%</div>
            </div>
        </div>
    </div>
    <!-- preloader end -->

    <!-- top panel -->
    <div class="mil-top-panel">
        <div class="container">
            <div class="mil-top-panel-content">
                <a href="{{url('/')}}" class="mil-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="shores hotel">
                </a>
                <div class="mil-menu-btn">
                    <span></span>
                </div>
                <div class="mil-mobile-menu">
                    <nav class="mil-menu">
                        <ul>
                            <li class="{{ ($route == 'home')? 'mil-current' : '' }}"><a href="{{ url('/') }}">Home</a></li>

                            <li class="{{ ($route == 'aboutUs')? 'mil-current' : '' }}"><a href="{{ route('aboutUs') }}">About us</a></li>


                            <li class="{{ ($route == 'getRooms' || ($route == 'chosen_lodge' && isset($room) && $room->room_type == 0)) ? 'mil-current' : '' }}">
                                <a href="{{ route('getRooms') }}">Hotel</a>
                            </li>

                            <li class="{{ ($route == 'getApartments' || ($route == 'chosen_lodge' && isset($room) && $room->room_type == 1)) ? 'mil-current' : '' }}">
                                <a href="{{ route('getApartments') }}">Apartments</a>
                            </li>

                            <li class="{{ ($route == 'citibar') ? 'mil-current' : '' }}">
                                <a href="{{ route('citibar') }}">CitiB Lounge</a>
                            </li>

                        </ul>
                    </nav>
                    <a href="#." class="mil-button mil-open-book-popup mil-top-panel-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Book now</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- top panel end -->

    @yield('content')


    <!-- footer -->
    <footer>
        <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape mil-fade-up" style="width: 85%; top: -15%; left: -40%; transform: rotate(-50deg)" alt="shape">
        <div class="mil-footer-content mil-fade-up">
            <div class="container">
                <div class="row justify-content-between mil-p-100-40">
                    <div class="col-md-4 col-lg-4 mil-mb-60">

                        <a href="#." class="mil-logo mil-mb-40">
                            <img src="{{ asset('img/logo.png') }}" alt="aquarelle">
                        </a>

                        <p class="mil-mb-20">Subscribe to our newsletter:</p>

                        <form class="mil-subscribe-form">
                            <input type="text" placeholder="Enter your email">
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                        </form>

                    </div>
                    <div class="col-md-7 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-lg-7 mil-mb-60">

                                <nav class="mil-footer-menu">
                                    <ul class="grey-text">
                                        <li class="{{ ($route == 'home')? 'mil-active' : '' }}">
                                            <a href="{{ url('/') }}">Home</a>
                                        </li>

                                        <li class="{{ ($route == 'aboutUs')? 'mil-active' : '' }}">
                                            <a href="{{ route('aboutUs') }}">About</a>
                                        </li>

                                        <li class="{{ (request()->routeIs('getRooms') || (request()->routeIs('chosen_lodge') && isset($room) && $room->room_type == 0)) ? 'mil-active' : '' }}">
                                            <a href="{{ route('getRooms') }}">Hotel</a>
                                        </li>

                                        <li class="{{ (request()->routeIs('getApartments') || (request()->routeIs('chosen_lodge') && isset($room) && $room->room_type == 1)) ? 'mil-active' : '' }}">
                                            <a href="{{ route('getApartments') }}">Apartments</a>
                                        </li>

                                        <li class="{{ request()->routeIs('getlodged') ? 'mil-active' : '' }}">
                                            <a href="{{ route('getlodged') }}">Get Lodged</a>
                                        </li>

                                        <li class="{{ ($route == 'citibar')? 'mil-active' : '' }}">
                                            <a href="{{ route('citibar') }}">CitiB Lounge</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                            <div class="col-md-6 col-lg-5 mil-mb-60">

                                <ul class="mil-menu-list">
                                    <li><a href="{{ route('aboutUs') }}#gallery" class="mil-light-soft">Our Gallery</a></li>
                                    <li><a href="{{ route('contact') }}" class="mil-light-soft">Reach out to us</a></li>
{{--                                    <li><a href="#." class="mil-light-soft">Privacy Policy</a></li>--}}
{{--                                    <li><a href="#." class="mil-light-soft">Terms and conditions</a></li>--}}
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mil-divider"></div>

                <div class="row justify-content-between flex-sm-row-reverse mil-p-100-40">
                    <div class="col-md-7 col-lg-6">

                        <div class="row justify-content-between">

{{--                            <div class="col-md-6 col-lg-5 mil-mb-40">--}}

{{--                                <h5 class="mil-mb-20">Spain</h5>--}}
{{--                                <p>71 South Los Carneros Road, California +51 174 705 812</p>--}}

{{--                            </div>--}}
                            <div class="col-md-6 col-lg-5 mil-mb-40" style="float: right">

                                <h5 class="mil-mb-20">Lagos, Nigeria</h5>
                                <p>{{ $contact['info'][2]['title'] ?? 'No address set' }}</p>

                            </div>
                            <div class="col-md-6 col-lg-6 mil-mb-40">

{{--                                <h5 class="mil-mb-20">Lagos, Nigeria</h5>--}}

                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 col-lg-6 mil-mb-60">

                        <div class="mil-mb-20">
                            <ul class="mil-social-icons">
                                @foreach($socials as $platform => $url)
                                    <li>
                                        <a href="{{ $url }}" target="_blank" class="social-icon inline-block">
                                            @switch($platform)
                                                @case('facebook')
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#6B6B6A" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22 12a10 10 0 1 0-11.6 9.9v-7H8v-3h2.4V9.5c0-2.4 1.4-3.7 3.5-3.7 1 0 2 .2 2 .2v2.2H15c-1.2 0-1.6.8-1.6 1.6V12H16l-.5 3h-2v7A10 10 0 0 0 22 12z"/>
                                                    </svg>
                                                    @break

                                                @case('instagram')
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#6B6B6A" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5z"/>
                                                        <path fill="#fff" d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm5-2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                                    </svg>
                                                    @break

                                                @case('x')
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#6B6B6A" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18 2h3l-7 8 8 10h-6l-5-6-5 6H2l7-8-8-10h6l5 6 5-6z"/>
                                                    </svg>
                                                    @break

                                                @case('linkedin')
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#6B6B6A" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4.98 3.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM3 8.98h4v12H3v-12zm7 0h3.6v1.7h.1c.5-.9 1.7-1.8 3.4-1.8 3.6 0 4.2 2.4 4.2 5.5V21h-4v-5.3c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9V21h-4v-12z"/>
                                                    </svg>
                                                    @break

                                                @default
                                                    {{-- Fallback: generic icon --}}
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#6B6B6A" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="12" cy="12" r="10"/>
                                                    </svg>
                                            @endswitch
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="mil-light-soft">© Copyright 2025 - Shores Hotel. All Rights Reserved.</p><br>
                        <p>Developed by {!! signature() !!}</p>

{{--                        <p class="mil-light-soft">© Copyright 2025 - Shores Hotel. All Rights Reserved.</p><br>--}}
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->

    <!-- notice popup -->
    <div class="mil-book-popup-frame" style="background-color: transparent">
        <div class="mil-book-popup" style="background-color: whitesmoke; border:solid #ff1493 2px;">
            <div class="mil-popup-head mil-mb-20">
                <h3 class="mil-h3-lg" style="padding-left: 0.5em">💖 Valentine Season Special</h3>
                <div class="mil-close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>
            <div style="margin: 0.5em; margin-bottom: 1em; padding: 2em; border:solid #ff69b4 1px; border-radius: 20px; background-color: white">
                <p class="mil-modal">
                    Love is in the air! Enjoy exclusive Valentine discounts at Shores Hotel & Shores Apartment
                </p>
                <p style="margin-top: 1em"><strong>✨ 10% OFF Weekday bookings</strong></p>
                <p><strong>✨ 5% OFF Weekend bookings</strong></p>
                <p style="margin-top: 1em">💌 Celebrate love with comfort, luxury, and relaxation</p>
                <p>⏰ Limited time offer</p>
            </div>
            <a href="{{ route('getRooms') }}" class="mil-button">
                <span>Book Your Romantic Stay Now!</span>
            </a>
        </div>
    </div>
    <!-- notice popup end -->


    <!-- book popup -->
    <div class="mil-book-popup-frame">
        <div class="mil-book-popup">
            <div class="mil-popup-head mil-mb-40">
                <h3 class="mil-h3-lg">Make Reservation</h3>
                <div class="mil-close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>
            <div class="mil-modal-form">
                <form method="POST" action="{{ route('make.booking') }}">
                    @csrf

                    <div class="mil-field-frame mil-mb-20">
                        <label>Check-in</label>
                        <input name="check_in" id="check-in-2" type="text" class="datepicker-here"
                               data-position="bottom left" placeholder="Select date" autocomplete="off" readonly>
                    </div>

                    <div class="mil-field-frame mil-mb-20">
                        <label>Check-out</label>
                        <input name="check_out" id="check-out-2" type="text" class="datepicker-here"
                               data-position="bottom left" placeholder="Select date" autocomplete="off" readonly>
                    </div>

                    <div class="mil-field-frame mil-mb-20">
                        <label>Lodging type</label>
                        <select name="room_id" required>
                            <option value="">Select Lodging</option>

                            {{-- Rooms group --}}
                            @if($rooms->where('room_type', 0)->count() > 0)
                                <optgroup label="Rooms">
                                    @foreach($rooms->where('room_type', 0) as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->category->name }} - ₦{{ number_format($room->price_per_night) }}/night
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            {{-- Apartments group --}}
                            @if($rooms->where('room_type', 1)->count() > 0)
                                <optgroup label="Apartments">
                                    @foreach($rooms->where('room_type', 1) as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->category->name }} - ₦{{ number_format($room->price_per_night) }}/night
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>

                    </div>

                    <div class="mil-field-row mil-mb-20">
                        <div class="mil-field-frame mil-field-col">
                            <label>Adults</label>
                            <input name="adults" type="number" placeholder="Enter quantity" value="1" min="1">
                        </div>
                        <div class="mil-field-frame mil-field-col">
                            <label>Children</label>
                            <input name="children" type="number" placeholder="Enter quantity" value="0" min="0">
                        </div>
                    </div>

                    <button type="submit" class="mil-button mil-accent-1">
                        <span>Make Booking</span>
                    </button>
                </form>

            </div>

        </div>
    </div>
    <!-- book popup end -->

    <div class="mil-progressbar"></div>

</div>
<!-- wrapper end -->

<!-- jQuery js -->
<script src="{{ asset('js/plugins/jquery.min.js') }}"></script>
<!-- smooth scroll js -->
<script src="{{ asset('js/plugins/smooth-scroll.js') }}"></script>
<!-- swiper js -->
<script src="{{ asset('js/plugins/swiper.min.js') }}"></script>
<!-- datepicker js -->
<script src="{{ asset('js/plugins/datepicker.js') }}"></script>
<!-- aquarelle js -->
<script src="{{ asset('js/main.js') }}"></script>

<script src="{{ asset('js/exit-intent_popup.js') }}"></script>
@stack('scripts')

@if (config('cookie-consent.enabled') && ! request()->hasCookie(config('cookie-consent.cookie_name')))
    @include('vendor.cookie-consent.dialogContents')
@endif
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toast = document.getElementById("toast");
        if (toast) {
            // Optional: remove element from DOM after fadeout animation
            setTimeout(() => {
                toast.remove(); // or toast.style.display = "none";
            }, 5000);
        }
    });
</script>

</body>

</html>
