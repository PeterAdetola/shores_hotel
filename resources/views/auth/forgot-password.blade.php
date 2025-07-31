@extends('layouts.auth_frame')

@section('title', 'Forgot Password')
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
                <x-auth-session-status class="mb-4 green-text" :status="session('status')" />
                <div class="row collection" style="padding:1em">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <div class="row">

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf


                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="material-icons prefix pt-2">mail_outline</i>
                                <input id="email"  type="email" name="email" :value="old('email')"  data-error=".errorTxt1" required autocomplete="email" />
                                <label for="email">Email</label>
                            </div>
                            @error('email')
                            <small class="errorTxt1  red-text">{{ $message }}*</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col s8 right">
                                <button class="btn-large waves-effect waves-light right"  onclick="ShowPreloader()">{{ __('Email Password Reset Link') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
