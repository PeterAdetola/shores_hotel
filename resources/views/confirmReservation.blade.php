@extends('mainframe')
@section('content')
<!-- banner -->
<div class="mil-banner-sm"  style="margin-top: 10em">
    <img src="img/shapes/4.png" class="mil-shape" style="width: 70%; top: 0; right: -35%; transform: rotate(190deg)" alt="shape">
    <img src="img/shapes/4.png" class="mil-shape" style="width: 70%; bottom: -12%; left: -30%; transform: rotate(40deg)" alt="shape">
    <img src="img/shapes/4.png" class="mil-shape" style="width: 110%; top: -5%; left: -30%; opacity: .3" alt="shape">
    <div class="container">
        <div class="mil-banner-img-4">
            <img src="img/shapes/1.png" alt="object" class="mil-figure mil-1">
            <img src="img/shapes/2.png" alt="object" class="mil-figure mil-2">
            <img src="img/shapes/3.png" alt="object" class="mil-figure mil-3">
        </div>

    </div>
</div>
<!-- banner end -->
<!-- services -->
<!-- Confirmation Form (Wider Version) -->
<div class="mil-content-sm-pad mil-p-100-100 mb-5">
    <div class="container">
        <div class="mil-text-center" style="margin-bottom: 2em">
            <div class="mil-suptitle mil-mb-20 mil-fade-up">One More Thing</div>
            <h2 class="mil-fade-up">Confirm your Reservation</h2>
        </div>

        <div class="mil-modal-form">
            <form action="{{ route('store.booking') }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="mil-field-frame mil-mb-20">
                    <label for="customer_name">Full Name</label>
                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        placeholder="Enter your name"
                        autocomplete="name"
                        required
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>

                <!-- Email Field -->
                <div class="mil-field-frame mil-mb-20">
                    <label for="customer_email">Email</label>
                    <input
                        type="email"
                        id="customer_email"
                        name="customer_email"
                        placeholder="Enter your email"
                        autocomplete="email"
                        required
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>

                <!-- WhatsApp Number Field -->
                <div class="mil-field-frame mil-mb-20">
                    <label for="customer_phone">WhatsApp Number</label>
                    <input
                        type="tel"
                        id="customer_phone"
                        name="customer_phone"
                        placeholder="Enter WhatsApp number"
                        autocomplete="tel"
                        required
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mil-button mil-accent-1 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Confirm Reservation</span>
                </button>
            </form>

        </div>
    </div>
</div>
<!-- services end -->

@endsection
