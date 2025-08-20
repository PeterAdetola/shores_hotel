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
                            <h1 class="mil-mb-40">Get in Touch!</h1>
                            <div class="mil-suptitle mil-breadcrumbs">
                                <ul>
                                    <li><a href="home-1.html">Home</a></li>
                                    <li><a href="search.html">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- banner end -->

    <!-- contact form -->
    <div class="mil-content-pad mil-p-100-100">
        <div class="container">
            <form>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="mil-field-frame mil-mb-20">
                            <label>Name</label>
                            <input type="text" placeholder="Enter your name">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mil-field-frame mil-mb-20">
                            <label>Email</label>
                            <input type="email" placeholder="Enter your email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="mil-field-frame mil-mb-20">
                            <label>Message</label>
                            <textarea placeholder="Write a message here"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <p class="mil-fade-up"><span class="mil-accent-2">*</span>{{ $contactContent['form']['disclaimer'] ?? '' }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="mil-desctop-right mil-fade-up">
                            <button type="submit" class="mil-button">
                                <span>{{ $contactContent['form']['button'] ?? 'Send' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- contact form end -->

    <!-- contact info -->
    <div class="mil-contact mil-p-100-60">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 85%; top: -20%; right: -30%; transform: rotate(-30deg) scaleX(-1);" alt="shape">
        <img src="img/shapes/4.png" class="mil-shape mil-fade-up" style="width: 110%; bottom: 15%; left: -30%; opacity: .2" alt="shape">
        <div class="container">
            <div class="row">
                @foreach(($contactContent['info'] ?? []) as $item)
                    <div class="col-xl-4">
                        <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                            <div class="mil-bg-icon"></div>
                            <div class="mil-icon mil-icon-fix">
                                <span class="material-symbols-outlined light-text">{{ $item['icon'] ?? 'circle'  }}</span>
                            </div>
                            <h3 class="mil-mb-20 grey-text">{{ $item['title'] ?? '' }}</h3>
                            <p>{{ $item['text'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- contact info end -->
@endsection
