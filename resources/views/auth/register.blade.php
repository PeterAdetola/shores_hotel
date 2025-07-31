@extends('layouts.auth_frame')

@section('title', 'Register')
@section('meta_description', 'Pacmedia - Tactical Digital Solutions. Brand Strategy, Development & Intelligent Automation.')
@section('meta_keywords', 'brand strategy, digital experience design, web development, AI automation systems, brand identity systems, conversion-focused design, custom development, intelligent customer operations, digital presence strategy, tactical digital solutions')
@section('page_css', asset('admin/assets/css/pages/register.css'))
@section('body_class', 'login-bg')

@section('content')
    <div id="register-page" class="row">
        <div class="col s12 m6 l4 card-panel border-radius-6 register-card bg-opacity-8 pt-3">
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">account_circle</i>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus />
                        <label for="name" class="center-align">Name</label>
                    </div>
                    @error('name')
                    <small class="errorTxt3  red-text">{{ $message }}*</small>
                    @enderror
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">person_outline</i>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" />
                        <label for="username" class="center-align">Username</label>
                    </div>
                    @error('username')
                    <small class="errorTxt3  red-text">{{ $message }}*</small>
                    @enderror
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">mail_outline</i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" />
                        <label for="email">Email</label>
                    </div>
                    @error('email')
                    <small class="errorTxt3  red-text">{{ $message }}*</small>
                    @enderror
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password" type="password" autocomplete="new-password" name="password" value="{{ old('password') }}" required />
                        <label for="password">Password</label>
                    </div>
                    @error('password')
                    <small class="errorTxt3  red-text">{{ $message }}*</small>
                    @enderror
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required />
                        <label for="password_confirmation">Password Confirmation</label>
                    </div>
                    @error('password_confirmation')
                    <small class="errorTxt3  red-text">{{ $message }}*</small>
                    @enderror
                </div>

                <div class="row pl-5 pr-5">
                    <div class="input-field right">
                        <button class="btn-large waves-effect waves-light" onclick="ShowPreloader()">{{ __('Register') }}</button>
                    </div>
                </div>
                <div class="progress collection">
                    <div id="preloader" class="indeterminate" style="display:none;
                border:2px #ebebeb solid"></div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <p class="margin medium-small"><a href="{{ route('login') }}">Already have an account? Login</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
