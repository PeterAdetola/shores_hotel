
@extends('admin.admin_master')
@section('admin')
@php
$pageTitle = 'Edit Profile';
@endphp
      

                      <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{ $pageTitle }}</span></h5>
                            <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin Home</a></li>
                  <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                    <div class="section">
                      <!-- Profile update form -->
                       @include('profile.partials.update-profile-information-form')
                       @include('profile.partials.update-password-form')
                       @include('profile.partials.delete-user-form')
                      
                        </div>
                    </div>
                </div>
                <!-- <div class="content-overlay"></div> -->
            </div>
        </div>
    </div>

@endsection