@extends('admin.admin_master')
@section('admin')

    @section('vendor_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/quill/quill.snow.css') }}">
    @endsection
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-sidebar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/app-email-content.css') }}">
    @endsection
    @php
        $pageTitle = 'Email Inbox';
    @endphp
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="col s12">
                <div class="container">
                    <!-- Sidebar Area Starts -->
                    <div class="sidebar-left sidebar-fixed">
                        <div class="sidebar">
                            <div class="sidebar-content">
                                <div class="sidebar-header">
                                    <div class="sidebar-details">
                                        <h5 class="m-0 sidebar-title"><i class="material-icons app-header-icon text-top">mail_outline</i> Mailbox</h5>
                                        <div class="row valign-wrapper mt-10 pt-2">
                                            <div class="col s3 media-image">
                                                <img src="../../../app-assets/images/user/2.jpg" alt="" class="circle z-depth-2 responsive-img">
                                                <!-- notice the "circle" class -->
                                            </div>
                                            <div class="col s9">
                                                <p class="m-0 subtitle font-weight-700">Lawrence Collins</p>
                                                <p class="m-0 text-muted">lawrence.collins@xyz.com</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="sidebar-list" class="sidebar-menu list-group position-relative">
                                    <div class="sidebar-list-padding app-sidebar sidenav" id="email-sidenav">
                                        <ul class="email-list display-grid">
                                            <li class="sidebar-title">Folders</li>
                                            <li class="active"><a href="#!" class="text-sub"><i class="material-icons mr-2"> mail_outline </i> Inbox</a>
                                            </li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> send </i> Sent</a></li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> description </i> Draft</a></li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> info_outline </i> Span</a></li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> delete </i> Trash</a></li>
                                            <li class="sidebar-title">Filters</li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> star_border </i> Starred</a></li>
                                            <li><a href="#!" class="text-sub"><i class="material-icons mr-2"> label_outline </i> Important</a></li>
                                            <li class="sidebar-title">Labels</li>
                                            <li><a href="#!" class="text-sub"><i class="purple-text material-icons small-icons mr-2">
                                                        fiber_manual_record </i> Note</a></li>
                                            <li><a href="#!" class="text-sub"><i class="amber-text material-icons small-icons mr-2">
                                                        fiber_manual_record </i> Paypal</a></li>
                                            <li><a href="#!" class="text-sub"><i class="light-green-text material-icons small-icons mr-2">
                                                        fiber_manual_record </i> Invoice</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <a href="#" data-target="email-sidenav" class="sidenav-trigger hide-on-large-only"><i
                                        class="material-icons">menu</i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar Area Ends -->
                    <!-- Content Area Starts -->
                    <div class="app-email-content">
                        <div class="content-area content-right">
                            <div class="app-wrapper">
                                <div class="app-search">
                                    <i class="material-icons mr-2 search-icon">search</i>
                                    <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter">
                                </div>
                                <div class="card card-default scrollspy border-radius-6 fixed-width">
                                    <div class="card-content pt-0">
                                        <div class="row">
                                            <div class="col s12">
                                                <!-- Email Header -->
                                                <div class="email-header">
                                                    <div class="subject">
                                                        <div class="back-to-mails">
                                                            <a href="{{ route('inbox') }}"><i class="material-icons">arrow_back</i></a>
                                                        </div>
                                                        <div class="email-title">Fruitcake sweet jelly-o sweet ice cream donut ice cream</div>
                                                    </div>
                                                    <div class="header-action">
                  <span class="badge grey lighten-2"><i class="amber-text material-icons small-icons mr-2">
                      fiber_manual_record </i>Paypal</span>
                                                        <div class="favorite">
                                                            <i class="material-icons">star_border</i>
                                                        </div>
                                                        <div class="email-label">
                                                            <i class="material-icons">label_outline</i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Email Header Ends -->
                                                <hr>
                                                <!-- Email Content -->
                                                <div class="email-content">
                                                    <div class="list-title-area">
                                                        <div class="user-media">
                                                            <img src="../../../app-assets/images/user/9.jpg" alt=""
                                                                 class="circle z-depth-2 responsive-img avtar">
                                                            <div class="list-title">
                                                                <span class="name">Pari Kalin</span>
                                                                <span class="to-person">to me</span>
                                                            </div>
                                                        </div>
                                                        <div class="title-right">
                                                            <span class="mail-time">Fri, Jan 11, 9:01 AM(4 days ago)</span>
                                                            <i class="material-icons">reply</i>
                                                            <i class="material-icons">more_vert</i>
                                                        </div>
                                                    </div>
                                                    <div class="email-desc">
                                                        <p>Fruitcake lemon drops jelly-o marshmallow cake dragée. Pie pudding cookie candy canes chocolate
                                                            bar. Cookie cheesecake carrot cake jelly-o jelly cupcake. Sweet roll icing dragée croissant tart
                                                            marshmallow icing marshmallow. Sweet roll jujubes cheesecake. Soufflé croissant bear claw
                                                            marshmallow macaroon sweet. <strong>Sweet roll</strong> macaroon topping cake jelly-o halvah chupa
                                                            chups.</p>
                                                        <p>Lollipop carrot cake gummies. Croissant lemon drops cotton candy. Candy canes sesame snaps
                                                            macaroon pie pie fruitcake cupcake danish marshmallow. Topping jelly donut liquorice oat cake sugar
                                                            plum cake dragée. Sweet cheesecake marshmallow. Gummies brownie lemon drops marshmallow oat cake
                                                            bear claw pudding.</p>
                                                    </div>
                                                </div>
                                                <!-- Email Content Ends -->
                                                <hr>
                                                <!-- Email Footer -->
                                                <div class="email-footer">
                                                    <h6 class="footer-title"> (3)</h6>
                                                    <div class="footer-action">
                                                        <div class="attachment-list">
                                                            <div class="attachment">
                                                                <img src="../../../app-assets/images/gallery/35.png" alt="" class="responsive-img attached-image">
                                                                <div class="size">
                                                                    <span class="grey-text">(0.75Mb)</span>
                                                                </div>
                                                                <div class="links">
                                                                    <a href="#" class="left">
                                                                        <i class="material-icons">remove_red_eye</i>
                                                                    </a>
                                                                    <a href="#" class="Right">
                                                                        <i class="material-icons">file_download</i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="attachment">
                                                                <img src="../../../app-assets/images/gallery/36.png" alt="" class="responsive-img attached-image">
                                                                <div class="size">
                                                                    <span class="grey-text">(1Mb)</span>
                                                                </div>
                                                                <div class="links">
                                                                    <a href="#" class="left">
                                                                        <i class="material-icons">remove_red_eye</i>
                                                                    </a>
                                                                    <a href="#" class="Right">
                                                                        <i class="material-icons">file_download</i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="attachment">
                                                                <img src="../../../app-assets/images/gallery/39.png" alt="" class="responsive-img attached-image">
                                                                <div class="size">
                                                                    <span class="grey-text">(1.2Mb)</span>
                                                                </div>
                                                                <div class="links">
                                                                    <a href="#" class="left">
                                                                        <i class="material-icons">remove_red_eye</i>
                                                                    </a>
                                                                    <a href="#" class="Right">
                                                                        <i class="material-icons">file_download</i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="footer-buttons">
                                                            <a class="btn reply mb-1"><i class="material-icons left">reply</i><span>Reply</span></a>
                                                            <a class="btn forward mb-1"><i class="material-icons left">reply</i><span>Forward</span></a>
                                                        </div>
                                                    </div>
                                                    <div class="reply-box d-none">
                                                        <form action="#">
                                                            <div class="input-field col s12">
                                                                <div class="snow-container mt-2">
                                                                    <div class="compose-editor"></div>
                                                                    <div class="compose-quill-toolbar">
                          <span class="ql-formats mr-0">
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-underline"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                          </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="input-field col s12">
                                                                <a class="btn reply-btn right">Reply</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="forward-box d-none">
                                                        <hr>
                                                        <form action="#">
                                                            <div class="input-field col s12">
                                                                <i class="material-icons prefix"> person_outline </i>
                                                                <input id="email" type="email" class="validate">
                                                                <label for="email">To</label>
                                                            </div>
                                                            <div class="input-field col s12">
                                                                <i class="material-icons prefix"> title </i>
                                                                <input id="subject" type="text" class="validate">
                                                                <label for="subject">Subject</label>
                                                            </div>
                                                            <div class="input-field col s12">
                                                                <div class="snow-container mt-2">
                                                                    <div class="forward-email"></div>
                                                                    <div class="forward-email-toolbar">
                          <span class="ql-formats mr-0">
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-underline"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                          </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="input-field col s12">
                                                                <a class="btn forward-btn right">Forward</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- Email Footer Ends -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Area Ends --><!-- START RIGHT SIDEBAR NAV -->
                    <aside id="right-sidebar-nav">
                        <div id="slide-out-right" class="slide-out-right-sidenav sidenav rightside-navigation">
                            <div class="row">
                                <div class="slide-out-right-title">
                                    <div class="col s12 border-bottom-1 pb-0 pt-1">
                                        <div class="row">
                                            <div class="col s2 pr-0 center">
                                                <i class="material-icons vertical-text-middle"><a href="#" class="sidenav-close">clear</a></i>
                                            </div>
                                            <div class="col s10 pl-0">
                                                <ul class="tabs">
                                                    <li class="tab col s4 p-0">
                                                        <a href="#messages" class="active">
                                                            <span>Messages</span>
                                                        </a>
                                                    </li>
                                                    <li class="tab col s4 p-0">
                                                        <a href="#settings">
                                                            <span>Settings</span>
                                                        </a>
                                                    </li>
                                                    <li class="tab col s4 p-0">
                                                        <a href="#activity">
                                                            <span>Activity</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-out-right-body row pl-3">
                                    <div id="messages" class="col s12 pb-0">
                                        <div class="collection border-none mb-0">
                                            <input class="header-search-input mt-4 mb-2" type="text" name="Search" placeholder="Search Messages" />
                                            <ul class="collection right-sidebar-chat p-0 mb-0">
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Elizabeth Elliott</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Thank you</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">5.00 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-1.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Mary Adams</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Hello Boo</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">4.14 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-2.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Caleb Richards</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Hello Boo</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">4.14 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-3.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Caleb Richards</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Keny !</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">9.00 PM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-4.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">June Lane</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Ohh God</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">4.14 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-5.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Edward Fletcher</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Love you</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">5.15 PM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-6.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Crystal Bates</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Can we</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">8.00 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Nathan Watts</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Great!</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">9.53 PM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-8.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Willard Wood</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Do it</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">4.20 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-1.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Ronnie Ellis</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Got that</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">5.20 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-9.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Daniel Russell</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Thank you</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">12.00 AM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-10.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Sarah Graves</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Okay you</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">11.14 PM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-off avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-11.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Andrew Hoffman</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Can do</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">7.30 PM</span>
                                                </li>
                                                <li class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0"
                                                    data-target="slide-out-chat">
                <span class="avatar-status avatar-online avatar-50"><img
                        src="../../../app-assets/images/avatar/avatar-12.png" alt="avatar" />
                  <i></i>
                </span>
                                                    <div class="user-content">
                                                        <h6 class="line-height-0">Camila Lynch</h6>
                                                        <p class="medium-small blue-grey-text text-lighten-3 pt-3">Leave it</p>
                                                    </div>
                                                    <span class="secondary-content medium-small">2.00 PM</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="settings" class="col s12">
                                        <p class="setting-header mt-8 mb-3 ml-5 font-weight-900">GENERAL SETTINGS</p>
                                        <ul class="collection border-none">
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Notifications</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input checked type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Show recent activity</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Show recent activity</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Show Task statistics</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Show your emails</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Email Notifications</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input checked type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <p class="setting-header mt-7 mb-3 ml-5 font-weight-900">SYSTEM SETTINGS</p>
                                        <ul class="collection border-none">
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>System Logs</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Error Reporting</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Applications Logs</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input checked type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Backup Servers</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="collection-item border-none">
                                                <div class="m-0">
                                                    <span>Audit Logs</span>
                                                    <div class="switch right">
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="activity" class="col s12">
                                        <div class="activity">
                                            <p class="mt-5 mb-0 ml-5 font-weight-900">SYSTEM LOGS</p>
                                            <ul class="widget-timeline mb-0">
                                                <li class="timeline-items timeline-icon-green active">
                                                    <div class="timeline-time">Today</div>
                                                    <h6 class="timeline-title">Homepage mockup design</h6>
                                                    <p class="timeline-text">Melissa liked your activity.</p>
                                                    <div class="timeline-content orange-text">Important</div>
                                                </li>
                                                <li class="timeline-items timeline-icon-cyan active">
                                                    <div class="timeline-time">10 min</div>
                                                    <h6 class="timeline-title">Melissa liked your activity Drinks.</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content green-text">Resolved</div>
                                                </li>
                                                <li class="timeline-items timeline-icon-red active">
                                                    <div class="timeline-time">30 mins</div>
                                                    <h6 class="timeline-title">12 new users registered</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content">
                                                        <img src="../../../app-assets/images/icon/pdf.png" alt="document" height="30" width="25"
                                                             class="mr-1">Registration.doc
                                                    </div>
                                                </li>
                                                <li class="timeline-items timeline-icon-indigo active">
                                                    <div class="timeline-time">2 Hrs</div>
                                                    <h6 class="timeline-title">Tina is attending your activity</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content">
                                                        <img src="../../../app-assets/images/icon/pdf.png" alt="document" height="30" width="25"
                                                             class="mr-1">Activity.doc
                                                    </div>
                                                </li>
                                                <li class="timeline-items timeline-icon-orange">
                                                    <div class="timeline-time">5 hrs</div>
                                                    <h6 class="timeline-title">Josh is now following you</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content red-text">Pending</div>
                                                </li>
                                            </ul>
                                            <p class="mt-5 mb-0 ml-5 font-weight-900">APPLICATIONS LOGS</p>
                                            <ul class="widget-timeline mb-0">
                                                <li class="timeline-items timeline-icon-green active">
                                                    <div class="timeline-time">Just now</div>
                                                    <h6 class="timeline-title">New order received urgent</h6>
                                                    <p class="timeline-text">Melissa liked your activity.</p>
                                                    <div class="timeline-content orange-text">Important</div>
                                                </li>
                                                <li class="timeline-items timeline-icon-cyan active">
                                                    <div class="timeline-time">05 min</div>
                                                    <h6 class="timeline-title">System shutdown.</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content blue-text">Urgent</div>
                                                </li>
                                                <li class="timeline-items timeline-icon-red">
                                                    <div class="timeline-time">20 mins</div>
                                                    <h6 class="timeline-title">Database overloaded 89%</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content">
                                                        <img src="../../../app-assets/images/icon/pdf.png" alt="document" height="30" width="25"
                                                             class="mr-1">Database-log.doc
                                                    </div>
                                                </li>
                                            </ul>
                                            <p class="mt-5 mb-0 ml-5 font-weight-900">SERVER LOGS</p>
                                            <ul class="widget-timeline mb-0">
                                                <li class="timeline-items timeline-icon-green active">
                                                    <div class="timeline-time">10 min</div>
                                                    <h6 class="timeline-title">System error</h6>
                                                    <p class="timeline-text">Melissa liked your activity.</p>
                                                    <div class="timeline-content red-text">Error</div>
                                                </li>
                                                <li class="timeline-items timeline-icon-cyan">
                                                    <div class="timeline-time">1 min</div>
                                                    <h6 class="timeline-title">Production server down.</h6>
                                                    <p class="timeline-text">Here are some news feed interactions concepts.</p>
                                                    <div class="timeline-content blue-text">Urgent</div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide Out Chat -->
                        <ul id="slide-out-chat" class="sidenav slide-out-right-sidenav-chat">
                            <li class="center-align pt-2 pb-2 sidenav-close chat-head">
                                <a href="#!"><i class="material-icons mr-0">chevron_left</i>Elizabeth Elliott</a>
                            </li>
                            <li class="chat-body">
                                <ul class="collection">
                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">hello!</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">How can we help? We're here for you!</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">I am looking for the best admin template.?</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">Materialize admin is the responsive materializecss admin template.</p>
                                        </div>
                                    </li>

                                    <li class="collection-item display-grid width-100 center-align">
                                        <p>8:20 a.m.</p>
                                    </li>

                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">Ohh! very nice</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">Thank you.</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">How can I purchase it?</p>
                                        </div>
                                    </li>

                                    <li class="collection-item display-grid width-100 center-align">
                                        <p>9:00 a.m.</p>
                                    </li>

                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">From ThemeForest.</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">Only $24</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">Ohh! Thank you.</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
          <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png"
                                                                   alt="avatar" />
          </span>
                                        <div class="user-content speech-bubble">
                                            <p class="medium-small">I will purchase it for sure.</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">Great, Feel free to get in touch on</p>
                                        </div>
                                    </li>
                                    <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
                                        <div class="user-content speech-bubble-right">
                                            <p class="medium-small">https://pixinvent.ticksy.com/</p>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="center-align chat-footer">
                                <form class="col s12" onsubmit="slideOutChat()" action="javascript:void(0);">
                                    <div class="input-field">
                                        <input id="icon_prefix" type="text" class="search" />
                                        <label for="icon_prefix">Type here..</label>
                                        <a onclick="slideOutChat()"><i class="material-icons prefix">send</i></a>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </aside>
                    <!-- END RIGHT SIDEBAR NAV -->
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
@endsection



@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/sortable/jquery-sortable-min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/quill/quill.min.js') }}"></script>

@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('admin/assets/js/search.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/app-email-content.js') }}"></script>
@endsection
