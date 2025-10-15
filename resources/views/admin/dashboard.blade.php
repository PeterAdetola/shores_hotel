@extends('admin.admin_master')
@section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/data-tables.css') }}">
@endsection
@section('admin')

    <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0"><span>Welcome Admin</span></h5>
{{--                            <ol class="breadcrumbs mb-0">--}}
{{--                                <li class="breadcrumb-item"><a href="#">Home</a>--}}
{{--                                </li>--}}
{{--                                <li class="breadcrumb-item"><a href="#">Pages</a>--}}
{{--                                </li>--}}
{{--                                <li class="breadcrumb-item active">Blank Page--}}
{{--                                </li>--}}
{{--                            </ol>--}}
                            <p style="font-weight: 600">Welcome to Shores Hotel Reservation management portal.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                    <div class="section">
                        <div class="row">
                            <div class="col s12 m6 l6 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
{{--                                        <i class="material-icons amber-text small-ico-bg mb-5">--}}
{{--                                            <span class="material-symbols-outlined">nest_multi_room</span>--}}
{{--                                        </i>--}}
                                        <i class="material-icons amber-text small-ico-bg mb-5">
                                            business
                                        </i>

                                        <h4 class="m-0"><b>5</b></h4>
                                        <p>Today Check-ins</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l6 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <i class="material-icons amber-text small-ico-bg mb-5">room</i>
                                        <h4 class="m-0"><b>2</b></h4>
                                        <p>Today Check-outs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
{{--                    <div class="content-overlay"></div>--}}
{{--                        <div class="card-content bookings">--}}
{{--                            <div class="card">--}}
{{--                                <div class="stat-box">--}}
{{--                                    <div class="stat-number">10</div>--}}
{{--                                    <div class="stat-label">Today Check-ins</div>--}}
{{--                                </div>--}}
{{--                                <div class="stat-box">--}}
{{--                                    <div class="stat-number">5</div>--}}
{{--                                    <div class="stat-label">Today Check-outs</div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                    <div class="card-content bookings">
                        <div class="card">
                            <div class="col s12">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12 m12 l12">
                                    <div class="card-content">
                                        <h4 class="card-title">Recent Bookings</h4>
                                        <div class="row">
                                            <div class="col s12">
                                                <table id="scroll-dynamic" class="display">
                                                    <thead>
                                                    <tr>
                                                        <th>Booking Code</th>
                                                        <th>Guest</th>
                                                        <th>Date</th>
                                                        <th>Room Type</th>
                                                        <th>Status</th>
                                                        <th>Check-in</th>
                                                        <th>Check-out</th>
                                                        <th>Guest-No.</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(getAllBookings(5) as $booking)
                                                        <tr>
                                                            <td>{{ $booking->booking_code ?? 'N/A' }}</td>
                                                            <td>{{ $booking->customer_name }}</td>
                                                            <td title="{{ $booking->created_at->format('Y-m-d H:i') }}">
                                                                {{ $booking->created_at->diffForHumans() }}
                                                            </td>
                                                            <td>{{ $booking->lodging_type ?? optional($booking->room->category)->name }}</td>
                                                            <td>{{ ucfirst($booking->status) }}</td>
                                                            <td title="{{ $booking->check_in->format('Y-m-d') }}">
                                                                {{ \Carbon\Carbon::parse($booking->check_in)->diffForHumans() }}
                                                            </td>

                                                            <td title="{{ $booking->check_out->format('Y-m-d') }}">
                                                                {{ \Carbon\Carbon::parse($booking->check_out)->diffForHumans() }}
                                                            </td>

                                                            <td>{{ $booking->adults }}A / {{ $booking->children }}C</td>
                                                            <td>

                                                                <a href="#confirm_booking-modal{{ $booking->id }}" class="modal-trigger gradient-45deg-indigo-blue mb-1 chip waves-effect waves-light accent-2 white-text">
                                                                    confirm
                                                                </a>
                                                                &nbsp;&nbsp;
                                                                <a href="#decline_booking{{ $booking->id }}" class="modal-trigger gradient-45deg-deep-orange-orange mb-1 chip waves-effect waves-light accent-2 white-text">
                                                                    cancel
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @include('admin.bookings.modals.confirm_booking-modal')
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th>Booking Code</th>
                                                        <th>Guest</th>
                                                        <th>Date</th>
                                                        <th>Room Type</th>
                                                        <th>Status</th>
                                                        <th>Check-in</th>
                                                        <th>Check-out</th>
                                                        <th>Guest-No.</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-content bookings mt-3">

                        <div class="card">
                            <div class="col s12">

                                <div class="row" id="main-view">
                                    <div class="col s12">
                                        <ul class="tabs tab-demo">
                                            <li class="tab col m3"><a class="active" href="#test1">Unprocessed</a></li>
                                            <li class="tab col m3"><a href="#test2">Processed</a></li>
                                        </ul>
                                        <div class="divider"></div>
                                    </div>
                                    <div class="col s12 mt-1">
                                        <div id="test1" class="col s12">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th>Guest</th>
                                                    <th>Date</th>
                                                    <th>Room Type</th>
                                                    <th>Check-in</th>
                                                    <th>Check-out</th>
                                                    <th>Guest-No.</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(getUnprocessedBookings(5) as $booking)
                                                <tr>
                                                    <td>{{ $booking->customer_name }}</td>
                                                    <td title="{{ $booking->created_at->format('Y-m-d H:i') }}">
                                                        {{ $booking->created_at->diffForHumans() }}
                                                    </td>
                                                    <td>{{ $booking->lodging_type ?? optional($booking->room->category)->name }}</td>
                                                    <td title="{{ $booking->check_in->format('Y-m-d') }}">
                                                        {{ \Carbon\Carbon::parse($booking->check_in)->diffForHumans() }}
                                                    </td>

                                                    <td title="{{ $booking->check_out->format('Y-m-d') }}">
                                                        {{ \Carbon\Carbon::parse($booking->check_out)->diffForHumans() }}
                                                    </td>

                                                    <td>{{ $booking->adults }}A / {{ $booking->children }}C</td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="test2" class="col s12">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th>Guest</th>
                                                    <th>Date</th>
                                                    <th>Room Type</th>
                                                    <th>Check-in</th>
                                                    <th>Check-out</th>
                                                    <th>Guest-No.</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(getProcessedBookings(5) as $booking)
                                                    <tr>
                                                        <td>{{ $booking->customer_name }}</td>
                                                        <td title="{{ $booking->created_at->format('Y-m-d H:i') }}">
                                                            {{ $booking->created_at->diffForHumans() }}
                                                        </td>
                                                        <td>{{ $booking->lodging_type ?? optional($booking->room->category)->name }}</td>
                                                        <td title="{{ $booking->check_in->format('Y-m-d') }}">
                                                            {{ \Carbon\Carbon::parse($booking->check_in)->diffForHumans() }}
                                                        </td>

                                                        <td title="{{ $booking->check_out->format('Y-m-d') }}">
                                                            {{ \Carbon\Carbon::parse($booking->check_out)->diffForHumans() }}
                                                        </td>

                                                        <td>{{ $booking->adults }}A / {{ $booking->children }}C</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
        </div>
    </div>
    </div>
    <!-- END: Page Main-->





    <script src="{{ asset('admin/assets/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code lists',
            height: 250,
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code '
        });

        tinymce.init({
            selector: 'textarea#myeditorinstanceII', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code lists',
            height: 200,
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code '
        });
    </script>
@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/js/dataTables.select.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/data-tables.js') }}"></script>
@endsection
