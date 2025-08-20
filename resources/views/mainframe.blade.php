<!DOCTYPE html>

<html lang="eng">
@php
    $contact = getContactContent();
    $socials = $contact['social'] ?? [];
    $route = Route::current()->getName();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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
    <title>Shores Hotels</title>
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
                            <li class="{{ ($route == 'getlodged')? 'mil-current' : '' }}"><a href="{{ route('getlodged') }}">Get Lodged</a></li>

{{--                            <li class="">--}}
{{--                                <a href="#.">Get Lodged</a>--}}
{{--                                <ul>--}}
{{--                                    <li class="{{ ($route == 'getlodged')? 'mil-current' : '' }}"><a href="{{ route('getlodged') }}">Get Lodged</a></li>--}}
{{--                                    <li class="{{ ($route == 'typesOfRoom')? 'mil-current' : '' }}"><a href="{{ route('typesOfRoom') }}">Rooms</a></li>--}}
{{--                                    <li class="{{ ($route == 'typesOfApartment')? 'mil-current' : '' }}"><a href="{{ route('typesOfApartment') }}">Apartments</a></li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            <li><a href="#.">Apartments</a></li>--}}
                            <li class="{{ ($route == 'citibar')? 'mil-current' : '' }}"><a href="{{ route('citibar') }}">Citibar</a></li>
                            <li class="{{ ($route == 'contact')? 'mil-current' : '' }}"><a href="{{ route('contact') }}">Contact</a></li>
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

                        <p class="mil-mb-20">Subscribe our newsletter:</p>

                        <form class="mil-subscribe-form">
                            <input type="text" placeholder="Enter our email">
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
                                        <li class="{{ ($route == 'getlodged')? 'mil-active' : '' }}">
                                            <a href="{{ route('getlodged') }}">Get Lodged</a>
                                        </li>
                                        <li class="{{ ($route == 'citibar')? 'mil-active' : '' }}">
                                            <a href="{{ route('citibar') }}">Citibar</a>
                                        </li>
                                        <li class="{{ ($route == 'contact')? 'mil-active' : '' }}">
                                            <a href="{{ route('contact') }}">Contact</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                            <div class="col-md-6 col-lg-5 mil-mb-60">

                                <ul class="mil-menu-list">
                                    <li><a href="#." class="mil-light-soft">Privacy Policy</a></li>
                                    <li><a href="#." class="mil-light-soft">Terms and conditions</a></li>
{{--                                    <li><a href="#." class="mil-light-soft">Cookie Policy</a></li>--}}
{{--                                    <li><a href="#." class="mil-light-soft">Careers</a></li>--}}
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
                        <p>Developed by Pacmedia Creatives</p>

                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->

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
                <form>
                    <div class="mil-field-frame mil-mb-20">
                        <label>Check-in</label>
                        <input id="check-in-2" type="text" class="datepicker-here" data-position="bottom left" placeholder="Select date" autocomplete="off" readonly="readonly">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="mil-field-frame mil-mb-20">
                        <label>Check-out</label>
                        <input id="check-out-2" type="text" class="datepicker-here" data-position="bottom left" placeholder="Select date" autocomplete="off" readonly="readonly">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="mil-field-frame mil-mb-20">
                        <label>Lodging type</label>
                        <select>
                            <option value="" disabled selected>Select lodging</option>
                            <option value="Kings room">Kings room</option>
                            <option value="Queens room">Queens room</option>
                            <option value="Deluxe room">Deluxe room</option>
                            <option value="Superior room">Superior room</option>
                            <option value="Master suites">Master suites</option>
                            <option disabled>-------------</option>
                            <option value="Studio apartment">Studio apartment</option>
                            <option value="Suite apartment">Suite apartment</option>
                        </select>
                    </div>
                    <div class="mil-field-row mil-mb-20">
                        <div class="mil-field-frame mil-field-col">
                            <label>Adults</label>
                            <input type="text" placeholder="Enter quantity" value="1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="mil-field-frame mil-field-col">
                            <label>Children</label>
                            <input type="text" placeholder="Enter quantity" value="0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="mil-button mil-accent-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-arrow-right">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
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
<script src="js/plugins/jquery.min.js"></script>
<!-- smooth scroll js -->
<script src="js/plugins/smooth-scroll.js"></script>
<!-- swiper js -->
<script src="js/plugins/swiper.min.js"></script>
<!-- datepicker js -->
<script src="js/plugins/datepicker.js"></script>
<!-- aquarelle js -->
<script src="js/main.js"></script>
@stack('scripts')
</body>

</html>
