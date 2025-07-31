@extends('layouts.auth_frame')

@section('title', 'Verify Email')
@section('meta_description', 'Pacmedia - Tactical Digital Solutions. Brand Strategy, Development & Intelligent Automation.')
@section('meta_keywords', 'brand strategy, digital experience design, web development, AI automation systems, brand identity systems, conversion-focused design, custom development, intelligent customer operations, digital presence strategy, tactical digital solutions')
@section('page_css', asset('admin/assets/css/pages/login.css'))
@section('body_class', 'login-bg')

@section('content')
    <div id="login-page" class="row">
        <div class="col s12 m6 l5" style="margin: auto;">
            <div class="flex justify-center" style="width:5em; margin: auto;">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 101.5 101.5" style="enable-background:new 0 0 101.5 101.5;" xml:space="preserve">
              <defs><style>.cls-1{fill:#1c1c1c;}</style></defs><path class="cls-1" d="M0,0V92.7H92.7V0ZM44.13,85.55,25.84,68.33V48.91H44.13Zm0-40.42H25.84V24l18.29-4.59ZM66.44,63.74,48.15,66V48.91H66.44Zm0-18.61H48.15V26.91l18.29,2.87Z"/>
        </svg>
            </div>
            <div class="card-panel border-radius-6 login-card bg-opacity-8">

                <div class="progress collection">
                    <div id="preloader" class="indeterminate"  style="display:none;
                border:2px #ebebeb solid"></div>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <div class="row collection" style="padding:1em">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="row collection" style="padding:1em">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="row">

                    <div class="col s4 mt-4">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="btn-flat waves-effect waves-light">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                    <div class="col s8">
                        <form method="POST" action="{{ route('verification.send') }}"> {{-- Changed action from route('login') to route('verification.send') --}}
                            @csrf
                            <div class="row pl-5 pr-5">
                                <div class="input-field right">
                                    <button class="btn-large waves-effect waves-light"  onclick="ShowPreloader()">{{ __('Resend Verification Email') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
