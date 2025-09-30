
@extends('admin.admin_master')
@section('admin')
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
        {{--        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/dataTables.checkboxes.css') }}">--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/data-tables.css') }}">
    @endsection
    @php
        $pageTitle = 'All Bookings';
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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin Home</a>
                                </li>
                                <li class="breadcrumb-item active">{{ $pageTitle }}
                                </li>
                            </ol>
                        </div>

                        <!-- Something is removed here -->

                    </div>
                </div>
            </div><br>
            <div class="col s12">
                <div class="container">
                    <!-- users view start -->
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div id="button-trigger" class="card card card-default scrollspy">
                                    <div class="card-content">
                                        <h4 class="card-title">{{ $pageTitle }}</h4>
                                        <div class="row">
                                            <div class="col s12">
                                                <table id="data-table-row-grouping" class="display">
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
                                                    @foreach($bookings as $booking)
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
                                                                <a class="gradient-45deg-indigo-blue mb-1 chip waves-effect waves-light accent-2 white-text">confirm</a> &nbsp;&nbsp;
                                                                <a class="gradient-45deg-blue-grey-blue-grey mb-1 chip waves-effect waves-light accent-2 white-text">cancel</a>
                                                            </td>
                                                        </tr>
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
                    <!-- users view ends -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: Page Main-->





@endsection
@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/js/dataTables.select.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/data-tables.js') }}"></script>
@endsection
