 <!-- BEGIN: SideNav -->
@php

    $route = Route::current()->getName();
    $emailAccounts = getEmailAccountsForSidebar();
    $activeEmail = getActiveEmailAccount();


@endphp
 <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
     <div class="brand-sidebar">
         <h1 class="logo-wrapper" style="height:5em">
             <a class="brand-logo darken-1" href="{{route('dashboard')}}">
                 <img style="padding-bottom: 0.2em; height: 1.3em;" class="hide-on-med-and-down"
                      src="{{ asset('admin/assets/images/logo/pacmediac_logo.png') }}" alt="Pacmedia logo"/>
                 <img style="margin-top: -8px; height: 1.3em;" class="show-on-medium-and-down hide-on-med-and-up"
                      src="{{ asset('admin/assets/images/logo/pacmediac_logo.png') }}" alt="Pacmedia logo"/><span
                     class="logo-text hide-on-med-and-down" style="padding-bottom:20px"><img
                         src="{{ asset('admin/assets/images/logo/logo-text.png') }}" alt="Pacmedia logo" style="height: 1.2em;"/></span>
             </a>

             <a class="navbar-toggler" href="#">
                 <i class="material-icons">radio_button_checked</i>
             </a>
         </h1>
     </div>
     <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
         data-menu="menu-navigation" data-collapsible="menu-accordion">

         <li class="active bold"><a class="{{ ($route == 'dashboard')? 'active' : '' }} waves-effect waves-cyan "
                                    href="{{ route('dashboard') }}"><i class="material-icons"><span
                         class="material-symbols-outlined">dashboard</span></i><span class="menu-title"
                                                                                     data-i18n="Dashboard">Dashboard</span></a>
         </li>

         <li class="bold">
             <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
                 <i class="material-icons"><span class="material-symbols-outlined">calendar_add_on</span></i>
                 <span class="menu-title" data-i18n="Home Slide Setup">Bookings</span>
             </a>
             <div class="collapsible-body">
                 <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                     <li>
                         <a class="{{ ($route == 'get.all_bookings')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('get.all_bookings') }}"><i class="material-icons">radio_button_unchecked</i>
                             <span data-i18n="Home Slide">All Bookings</span>
                         </a>
                     </li>
                     <li>
                         <a class="{{ ($route == 'get.unprocessed_bookings')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('get.unprocessed_bookings') }}"><i class="material-icons">radio_button_unchecked</i>
                             <span data-i18n="Home Slide">Unprocessed Bookings</span>
                         </a>
                     </li>
                     <li>
                         <a class="{{ ($route == 'get.processed_bookings')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('get.processed_bookings') }}"><i class="material-icons">radio_button_unchecked</i>
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
                         <a class="{{ ($route == 'room_management')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('room_management') }}"><i class="material-icons">radio_button_unchecked</i>
                             <span data-i18n="Room Management">Room Management</span>
                         </a>
                     </li>
                     <li>
                         <a class="{{ ($route == 'add_room')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('add_room') }}"><i class="material-icons">radio_button_unchecked</i>
                             <span data-i18n="Add Room">Add Room</span>
                         </a>
                     </li>
                     <li>
                         <a class="{{ ($route == 'room_config')? 'active' : '' }} waves-effect waves-cyan"
                            href="{{ route('room_config') }}"><i class="material-icons">radio_button_unchecked</i>
                             <span data-i18n="Home Slide">Room Config</span>
                         </a>
                     </li>
                 </ul>
             </div>
         </li>

         {{-- Email Accounts Section in Main Sidebar --}}
         <li class="bold">
             <a class="collapsible-header waves-effect waves-cyan" href="javascript:void(0)">
                 <i class="material-icons">mail</i>
                 <span class="menu-title" data-i18n="Email">Email Accounts</span>
             </a>
             <div class="collapsible-body">
                 <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                     @if(!empty($emailAccounts) && count($emailAccounts) > 0)
                         @foreach($emailAccounts as $account)
                             <li>
                                 <a class="waves-effect waves-cyan {{ $activeEmail == $account['email'] ? 'active' : '' }}"
                                    href="{{ route('admin.email.switch-and-view', ['email' => $account['email']]) }}"
                                     {{ !$account['has_password'] ? 'onclick="return false;" style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                     <i class="material-icons">
                                         {{ $account['has_password'] ? 'radio_button_checked' : 'cancel' }}
                                     </i>
                                     <span data-i18n="Email">
                                {{ $account['display_name'] ?? $account['email'] }}
                                         @if(!$account['has_password'])
                                             <small class="red-text">(Not configured)</small>
                                         @endif
                            </span>

                                     {{-- Show unread count badge --}}
                                     @if($account['has_password'] && isset($account['unread_count']) && $account['unread_count'] > 0)
                                         <span class="new badge red" data-badge-caption="">{{ $account['unread_count'] }}</span>
                                     @endif
                                 </a>
                             </li>
                         @endforeach
                     @else
                         <li>
                             <a class="waves-effect waves-cyan" href="javascript:void(0)">
                                 <i class="material-icons">info</i>
                                 <span>No email accounts found</span>
                             </a>
                         </li>
                     @endif
                 </ul>
             </div>
         </li>


{{--         <li class="active bold"><a class="{{ ($route == 'admin.discount')? 'active' : '' }} waves-effect waves-cyan "--}}
{{--                                    href="{{ route('admin.discount') }}"><i class="material-icons"><span--}}
{{--                         class="material-symbols-outlined">percent_discount</span></i><span class="menu-title"--}}
{{--                                                                                     data-i18n="Dashboard">Discounts</span></a>--}}
{{--         </li>--}}
          <li class="active bold"><a class="{{ ($route == 'announcements')? 'active' : '' }} waves-effect waves-cyan "
                                     href="{{ route('announcements') }}"><i class="material-icons"><span
                          class="material-symbols-outlined">percent_discount</span></i><span class="menu-title"
                                                                                      data-i18n="Dashboard">Announcement</span></a>
          </li>
      </ul>
      <!-- <div class="navigation-background"></div> -->
      <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>


    </aside>
    <!-- END: SideNav
