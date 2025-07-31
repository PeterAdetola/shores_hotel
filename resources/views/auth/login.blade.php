@extends('layouts.auth_frame')

@section('title', 'Login')
@section('meta_description', 'Pacmedia - Tactical Digital Solutions. Brand Strategy, Development & Intelligent Automation.')
@section('meta_keywords', 'brand strategy, digital experience design, web development, AI automation systems, brand identity systems, conversion-focused design, custom development, intelligent customer operations, digital presence strategy, tactical digital solutions')
@section('page_css', asset('admin/assets/css/pages/login.css'))
@section('body_class', 'login-bg')

@section('content')
    <div id="login-page" class="row">
        <div class="col s12 m6 l4" style="margin: auto;">
            <div class="flex justify-center" style="width:5em; margin: auto;">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 101.5 101.5" style="enable-background:new 0 0 101.5 101.5;" xml:space="preserve">
              <defs><style>.cls-1{fill:#1c1c1c;}</style></defs><path class="cls-1" d="M0,0V92.7H92.7V0ZM44.13,85.55,25.84,68.33V48.91H44.13Zm0-40.42H25.84V24l18.29-4.59ZM66.44,63.74,48.15,66V48.91H66.44Zm0-18.61H48.15V26.91l18.29,2.87Z"/>
        </svg>
            </div>
            <div class="card border-radius-6 bg-opacity-8" style="padding-top:0 ;">
                <div class="progress collection">
                    <div id="preloader" class="indeterminate"  style="display:none;
                border:2px #ebebeb solid;"></div>
                </div>
                <div style="padding:0 2em 2em 2em">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row margin pt-7">
                            <div class="input-field col s12">
                                <i class="material-icons prefix pt-2">person_outline</i>
                                <input id="username" type="text" name="username" value="{{ old('username') }}" required  autocomplete="username" />
                                <label for="username" class="center-align">Username</label>
                                <x-input-error :messages="$errors->get('username')" class="mt-2 red-text" />
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="material-icons prefix pt-2">lock_outline</i>
                                <input id="password" type="password" name="password" required autocomplete="current-password"  data-error=".errorTxt2"/>
                                <label for="password">Password</label>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12 ml-2 mt-1">
                                <p class="ml-2">
                                    <label>
                                        <input class="filled-in"  name="remember" type="checkbox" />
                                        <span>Remember Me</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                        <div class="row pl-5 pr-5">
                            <div class="input-field right">
                                <button class="btn-large waves-effect waves-light"  onclick="ShowPreloader()">{{ __('Log in') }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="divider"></div>
                    <div class="row">
                        <div class="input-field col s6 m6">
                            @if (Route::has('password.request'))
                                <p class="margin left-align medium-small"><a href="{{ route('password.request') }}"class="grey-text">Forgot password ?</a></p>
                            @endif
                        </div>
                        <div class="input-field col s6 m6">
                            <p class="margin right-align medium-small"><a href="{{ route('register') }}" class="grey-text">Register Now!</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row center">Made with <span style="color:red">&#10084;</span> by Pacmedia Creatives</div>
        </div>
@endsection
