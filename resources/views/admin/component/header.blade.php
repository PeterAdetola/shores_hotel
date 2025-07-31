<!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-black-grey no-shadow">
          <div class="nav-wrapper">

            <div class="header-search-wrapper hide-on-med-and-down"><i class="material-icons">search</i>
              <input class="header-search-input z-depth-2" type="text" name="Search" placeholder="Explore Admin Section" data-search="template-list">
              <ul class="search-list collection display-none"></ul>
            </div>
            <ul class="navbar-list right">

             
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <li>{{ getUserName() }}&nbsp;&nbsp;&nbsp;</li>
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><b class="btn-floating  center waves-effect waves-light gradient-45deg-black-grey" >{{ getUserInitial() }}</b></a></li>


              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
            </ul>
            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
              <li><a class="grey-text text-darken-1" href="{{route('profile.edit')}}"><i class="material-icons">person_outline</i> Profile</a></li>
              <li class="divider"></li>
              <form method="POST" action="{{ route('logout') }}">
                            @csrf
              <li>
                <a class="grey-text text-darken-1" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                              <i class="material-icons">keyboard_tab</i> Logout</a></li>
              </form>



            </ul>
          </div>
          <nav class="display-none search-sm">
            <div class="nav-wrapper">
              <form id="navbarForm">
                <div class="input-field search-input-sm">
                  <input class="search-box-sm mb-0" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">
                  <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                  <ul class="search-list collection search-list-sm display-none"></ul>
                </div>
              </form>
            </div>
          </nav>
        </nav>
      </div>
    </header>
    <!-- END: Header-->