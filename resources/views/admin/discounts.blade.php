@extends('admin.admin_master')
@section('admin')
    @php
        $pageTitle = 'Manage Discount';
    @endphp

    <style>
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap; /* prevents overflow on small screens */
        }

        .action-buttons form {
            margin: 0;
        }
    </style>


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
                    </div>
                </div>
            </div>
            <br>
            <div class="col s12">
                <div class="container">

                    <!-- Apply to All Section -->
                    <div class="card-content bookings">
                        <div class="card">
                            <div class="col s12">
                                <div class="section section-data-tables">
                                    <div class="row">
                                        <div class="col s12 m12 l12">
                                            <div class="card-content">
                                                <h4 class="card-title">Apply to All (Rooms & Apartments)</h4>

                                                <!-- Preloader for Apply All -->
                                                <div class="progress collection">
                                                    <div id="all-preloader" class="indeterminate" style="display:none; border:2px #ebebeb solid"></div>
                                                </div>

                                                <form action="{{ route('admin.discount.apply-all') }}" method="POST" id="apply-all-form">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col s12 m6 l4">
                                                            <div class="col s12 input-field">
                                                                <input id="discount_all" name="discount_percentage" type="number"
                                                                       placeholder="0.00" class="validate" min="0" max="100"
                                                                       step="0.01" required style="margin-left: -5px"/>
                                                                <label for="discount_all">Discount Percentage (%)</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="action-buttons">
                                                        <form action="{{ route('admin.discount.apply-all') }}" method="POST" id="apply-all-form">
                                                            @csrf
                                                            <button id="apply-all-btn" class="btn waves-effect waves-light" type="submit">
                                                                Apply to all
                                                                <i class="material-icons left">
                                                                    <span class="material-symbols-outlined">sell</span>
                                                                </i>
                                                            </button>
                                                        </form>

                                                        <a href="javascript:void(0)"
                                                           id="remove-all-btn"
                                                           class="btn red waves-effect waves-light">
                                                            Remove all discounts
                                                            <i class="material-icons left">
                                                                <span class="material-symbols-outlined">close</span>
                                                            </i>
                                                        </a>
                                                    </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Type Management -->
                    <div class="section">
                        <div class="row">
                            <!-- Rooms Section -->
                            <div class="col s12 m6 l6 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content">
                                        <h4 class="card-title">
                                            <i class="material-icons left">hotel</i>
                                            Rooms ({{ $roomsCount }} total)
                                        </h4>

                                        <!-- Preloader for Rooms -->
                                        <div class="progress collection">
                                            <div id="rooms-preloader" class="indeterminate" style="display:none; border:2px #ebebeb solid"></div>
                                        </div>

                                        <!-- Active Discount Status -->
                                        <div class="">
                                            @if($roomsDiscount && $roomsDiscount->has_discount)
                                                <div class="chip green lighten-1 white-text">
                                                    <span class="card-title white-text darken-1" style="font-size: 1em">
                                                        &nbsp;&nbsp;Active Discount: {{ number_format($roomsDiscount->discount_percentage, 2) }}%&nbsp;&nbsp;
                                                    </span>
                                                </div>
                                            @else
                                                <div class="chip grey white-text">
                                                    <span class="card-title white-text darken-1" style="font-size: 1em">
                                                        &nbsp;&nbsp;No Active Discount&nbsp;&nbsp;
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.discount.apply-rooms') }}" method="POST" id="apply-rooms-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="col s12 input-field">
                                                        <input id="discount_rooms" name="discount_percentage" type="number"
                                                               value="{{ $roomsDiscount && $roomsDiscount->has_discount ? $roomsDiscount->discount_percentage : '' }}"
                                                               placeholder="0.00" class="validate" min="0" max="100"
                                                               step="0.01" required style="margin-left: -5px"/>
                                                        <label for="discount_rooms">Discount Percentage (%)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="action-buttons">
                                                <form action="{{ route('admin.discount.apply-rooms') }}" method="POST" id="apply-rooms-form">
                                                    @csrf
                                                    <button id="apply-rooms-btn" class="btn waves-effect waves-light" type="submit">
                                                        Apply
                                                        <i class="material-icons left">
                                                            <span class="material-symbols-outlined">sell</span>
                                                        </i>
                                                    </button>
                                                </form>

                                                @if($roomsDiscount && $roomsDiscount->has_discount)
                                                    <a href="javascript:void(0)"
                                                       id="remove-rooms-btn"
                                                       class="btn red waves-effect waves-light">
                                                        Remove discount
                                                        <i class="material-icons left">
                                                            <span class="material-symbols-outlined">close</span>
                                                        </i>
                                                    </a>
                                                @endif
                                            </div>


                                    </div>
                                </div>
                            </div>


                            <!-- Apartments Section -->
                            <div class="col s12 m6 l6 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content">
                                        <h4 class="card-title">
                                            <i class="material-icons left">
                                                <span class="material-symbols-outlined">domain</span>
                                            </i>
                                            Apartments ({{ $apartmentsCount }} total)
                                        </h4>

                                        <!-- Preloader for Apartments -->
                                        <div class="progress collection">
                                            <div id="apartments-preloader" class="indeterminate" style="display:none; border:2px #ebebeb solid"></div>
                                        </div>

                                        <!-- Active Discount Status -->
                                        <div class="">
                                            @if($apartmentsDiscount && $apartmentsDiscount->has_discount)
                                                <div class="chip green lighten-1 white-text">
                                                    <span class="card-title white-text darken-1" style="font-size: 1em">
                                                        &nbsp;&nbsp;Active Discount: {{ number_format($apartmentsDiscount->discount_percentage, 2) }}%&nbsp;&nbsp;
                                                    </span>
                                                </div>
                                            @else
                                                <div class="chip grey white-text">
                                                    <span class="card-title white-text darken-1" style="font-size: 1em">
                                                        &nbsp;&nbsp;No Active Discount&nbsp;&nbsp;
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.discount.apply-apartments') }}" method="POST" id="apply-apartments-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="col s12 input-field">
                                                        <input id="discount_apartments" name="discount_percentage" type="number"
                                                               value="{{ $apartmentsDiscount && $apartmentsDiscount->has_discount ? $apartmentsDiscount->discount_percentage : '' }}"
                                                               placeholder="0.00" class="validate" min="0" max="100"
                                                               step="0.01" required style="margin-left: -5px"/>
                                                        <label for="discount_apartments">Discount Percentage (%)</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="action-buttons">
                                                <form action="{{ route('admin.discount.apply-apartments') }}" method="POST" id="apply-apartments-form">
                                                    @csrf
                                                    <button id="apply-apartments-btn" class="btn waves-effect waves-light" type="submit">
                                                        Apply
                                                        <i class="material-icons left">
                                                            <span class="material-symbols-outlined">sell</span>
                                                        </i>
                                                    </button>
                                                </form>

                                                @if($apartmentsDiscount && $apartmentsDiscount->has_discount)
                                                    <a href="javascript:void(0)"
                                                       id="remove-apartments-btn"
                                                       class="btn red waves-effect waves-light">
                                                        Remove discount
                                                        <i class="material-icons left">
                                                            <span class="material-symbols-outlined">close</span>
                                                        </i>
                                                    </a>
                                                @endif
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

    <!-- Modals -->
    <!-- Remove All Modal -->
    <div id="remove-all-modal" class="modal" style="padding:1em;">
        <div class="modal-content">
            <h6 class="card-title ml-2" style="display:inline-block;">Remove All Discounts</h6>

            <div class="row">
                <div class="col s12">
                    <p>Are you sure you want to remove discounts from ALL rooms and apartments?</p>
                    <div class="col s12 mt-7">
                        <a href="{{ route('admin.discount.remove-all') }}" id="confirm-remove-all-btn" class="modal-action waves-effect waves-green red btn-large">
                            Yes, Remove All
                        </a>
                        <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Rooms Modal -->
    <div id="remove-rooms-modal" class="modal" style="padding:1em;">
        <div class="modal-content">
            <h6 class="card-title ml-2" style="display:inline-block;">Remove Rooms Discount</h6>

            <div class="row">
                <div class="col s12">
                    <p>Remove discount from all rooms?</p>
                    <div class="col s12 mt-7">
                        <a href="{{ route('admin.discount.remove-rooms') }}" id="confirm-remove-rooms-btn" class="modal-action waves-effect waves-green red btn-large">
                            Yes, Remove Discount
                        </a>
                        <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Apartments Modal -->
    <div id="remove-apartments-modal" class="modal" style="padding:1em;">
        <div class="modal-content">
            <h6 class="card-title ml-2" style="display:inline-block;">Remove Apartments Discount</h6>

            <div class="row">
                <div class="col s12">
                    <p>Remove discount from all apartments?</p>
                    <div class="col s12 mt-7">
                        <a href="{{ route('admin.discount.remove-apartments') }}" id="confirm-remove-apartments-btn" class="modal-action waves-effect waves-green red btn-large">
                            Yes, Remove Discount
                        </a>
                        <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize modals
        document.addEventListener('DOMContentLoaded', function() {
            var modals = document.querySelectorAll('.modal');
            M.Modal.init(modals);
        });

        // Apply All Form
        document.getElementById("apply-all-form").addEventListener("submit", function() {
            document.getElementById("all-preloader").style.display = "block";
            document.getElementById("apply-all-btn").disabled = true;
        });

        // Remove All Button
        const removeAllBtn = document.getElementById("remove-all-btn");
        if (removeAllBtn) {
            removeAllBtn.addEventListener("click", function(e) {
                e.preventDefault();
                var modal = M.Modal.getInstance(document.getElementById('remove-all-modal'));
                modal.open();
            });
        }

        // Confirm Remove All
        const confirmRemoveAllBtn = document.getElementById("confirm-remove-all-btn");
        if (confirmRemoveAllBtn) {
            confirmRemoveAllBtn.addEventListener("click", function() {
                document.getElementById("all-preloader").style.display = "block";
                var removeBtn = document.getElementById("remove-all-btn");
                if (removeBtn) removeBtn.classList.add('disabled');
            });
        }

        // Apply Rooms Form
        document.getElementById("apply-rooms-form").addEventListener("submit", function() {
            document.getElementById("rooms-preloader").style.display = "block";
            document.getElementById("apply-rooms-btn").disabled = true;
        });

        // Remove Rooms Button
        const removeRoomsBtn = document.getElementById("remove-rooms-btn");
        if (removeRoomsBtn) {
            removeRoomsBtn.addEventListener("click", function(e) {
                e.preventDefault();
                var modal = M.Modal.getInstance(document.getElementById('remove-rooms-modal'));
                modal.open();
            });
        }

        // Confirm Remove Rooms
        const confirmRemoveRoomsBtn = document.getElementById("confirm-remove-rooms-btn");
        if (confirmRemoveRoomsBtn) {
            confirmRemoveRoomsBtn.addEventListener("click", function() {
                document.getElementById("rooms-preloader").style.display = "block";
                var removeBtn = document.getElementById("remove-rooms-btn");
                if (removeBtn) removeBtn.classList.add('disabled');
            });
        }

        // Apply Apartments Form
        document.getElementById("apply-apartments-form").addEventListener("submit", function() {
            document.getElementById("apartments-preloader").style.display = "block";
            document.getElementById("apply-apartments-btn").disabled = true;
        });

        // Remove Apartments Button
        const removeApartmentsBtn = document.getElementById("remove-apartments-btn");
        if (removeApartmentsBtn) {
            removeApartmentsBtn.addEventListener("click", function(e) {
                e.preventDefault();
                var modal = M.Modal.getInstance(document.getElementById('remove-apartments-modal'));
                modal.open();
            });
        }

        // Confirm Remove Apartments
        const confirmRemoveApartmentsBtn = document.getElementById("confirm-remove-apartments-btn");
        if (confirmRemoveApartmentsBtn) {
            confirmRemoveApartmentsBtn.addEventListener("click", function() {
                document.getElementById("apartments-preloader").style.display = "block";
                var removeBtn = document.getElementById("remove-apartments-btn");
                if (removeBtn) removeBtn.classList.add('disabled');
            });
        }
    </script>
@endsection
