 <!-- BEGIN: SideNav -->
@php

$route = Route::current()->getName()

@endphp
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper" style="height:5em">
          <a class="brand-logo darken-1" href="{{route('dashboard')}}">
          <img style="padding-bottom: 0.2em; height: 1.3em;" class="hide-on-med-and-down" src="{{ asset('admin/assets/images/logo/pacmediac_logo.png') }}" alt="Pacmedia logo"/>
          <img style="margin-top: -8px; height: 1.3em;" class="show-on-medium-and-down hide-on-med-and-up" src="{{ asset('admin/assets/images/logo/pacmediac_logo.png') }}" alt="Pacmedia logo"/><span class="logo-text hide-on-med-and-down" style="padding-bottom:20px"><img src="{{ asset('admin/assets/images/logo/logo-text.png') }}"  style="height: 1.2em;" /></span>
        </a>

{{--          <a class="brand-logo darken-1" href="{{route('dashboard')}}">--}}
{{--          <img style="padding-bottom: 0.2em; height: 1.3em;" class="hide-on-med-and-down" src="--}}{{-- asset('admin/assets/images/logo/recordia_bg_logo.png') --}}{{--" alt="recordia logo"/>--}}
{{--          <img style="margin-top: -8px; height: 1.3em;" class="show-on-medium-and-down hide-on-med-and-up" src="--}}{{-- asset('admin/assets/images/logo/recordia_bg_logo.png') --}}{{--" alt="recordia logo"/><span class="logo-text hide-on-med-and-down"><img src="--}}{{-- asset('admin/assets/images/logo/recordia_text.png') --}}{{--"  style="height: 1.5em;" /></span>--}}
{{--        </a>--}}

        <a class="navbar-toggler" href="#">
          <i class="material-icons">radio_button_checked</i>
        </a>
      </h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">

        <li class="active bold"><a class="{{ ($route == 'dashboard')? 'active' : '' }} waves-effect waves-cyan " href="{{ route('dashboard') }}"><i class="material-icons"><span class="material-symbols-outlined">dashboard</span></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
        </li>

        <li class="bold">
          <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
          <i class="material-icons"><span class="material-symbols-outlined">calendar_add_on</span></i>
            <span class="menu-title" data-i18n="Home Slide Setup">Bookings</span>
          </a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li>
                <a class="{{ ($route == 'get.all_bookings')? 'active' : '' }} waves-effect waves-cyan" href="{{ route('get.all_bookings') }}"><i class="material-icons">radio_button_unchecked</i>
                  <span data-i18n="Home Slide">All Bookings</span>
                </a>
              </li>
                <li>
                    <a class="{{ ($route == 'instantRecords')? 'active' : '' }} waves-effect waves-cyan" href=""><i class="material-icons">radio_button_unchecked</i>
                        <span data-i18n="Home Slide">Unprocessed Bookings</span>
                    </a>
                </li>
                <li>
                    <a class="{{ ($route == 'instantRecords')? 'active' : '' }} waves-effect waves-cyan" href=""><i class="material-icons">radio_button_unchecked</i>
                        <span data-i18n="Home Slide">Processed Bookings</span>
                    </a>
                </li>
            </ul>
          </div>
        </li>

          <li class="bold">
              <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
                  <i class="material-icons"><span class="material-symbols-outlined">nest_multi_room</span></i>
                  <span class="menu-title" data-i18n="Home Slide Setup">Rooms</span>
              </a>
              <div class="collapsible-body">
                  <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                      <li>
                          <a class="{{ ($route == 'room_management')? 'active' : '' }} waves-effect waves-cyan" href="{{ route('room_management') }}"><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Room Management">Room Management</span>
                          </a>
                      </li>
                      <li>
                          <a class="{{ ($route == 'add_room')? 'active' : '' }} waves-effect waves-cyan" href="{{ route('add_room') }}"><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Add Room">Add Room</span>
                          </a>
                      </li>
                      <li>
                          <a class="{{ ($route == 'room_config')? 'active' : '' }} waves-effect waves-cyan" href="{{ route('room_config') }}"><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Home Slide">Room Config</span>
                          </a>
                      </li>
                  </ul>
              </div>
          </li>

          <li class="bold">
              <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
                  <i class="material-icons"><span class="material-symbols-outlined">mail
</span></i>
                  <span class="menu-title" data-i18n="Home Slide Setup">Emails</span>
              </a>
              <div class="collapsible-body">
                  <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                      <li>
                          <a class="{{ ($route == 'instantRecords')? 'active' : '' }} waves-effect waves-cyan" href=""><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Home Slide">Instant Records</span>
                          </a>
                      </li>
                      <li>
                          <a class="{{ ($route == 'instantRecords')? 'active' : '' }} waves-effect waves-cyan" href=""><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Home Slide">Instant Records</span>
                          </a>
                      </li>
                      <li>
                          <a class="{{ ($route == 'instantRecords')? 'active' : '' }} waves-effect waves-cyan" href=""><i class="material-icons">radio_button_unchecked</i>
                              <span data-i18n="Home Slide">Instant Records</span>
                          </a>
                      </li>
                  </ul>
              </div>
          </li>
      </ul>
      <!-- <div class="navigation-background"></div> -->
      <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>


    </aside>
    <!-- END: SideNav
