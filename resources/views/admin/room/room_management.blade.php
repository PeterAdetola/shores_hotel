
@extends('admin.admin_master')
@section('admin')
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
        {{--        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/data-tables/css/dataTables.checkboxes.css') }}">--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/data-tables.css') }}">
    @endsection
    @php
        $pageTitle = 'Room Management';
    @endphp
    @section('headScript')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endsection

    <style>
        .action-container {
            display: inline-flex;
            align-items: center;
            /*background: #f5f5f5;*/
            border-radius: 20px;
            padding: 0 8px;
        }

        .action-edit {
            margin-right: 12px;
        }

        .action-delete {
            margin-left: 4px;
        }

        .action-delete i {
            font-size: 20px;
        }

        .action-container a:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .action-container a {
            transition: all 0.2s ease;
        }

        .action-delete:hover i {
            color: #f44336 !important;
        }
        .amenity-item {
            display: inline-flex;
            margin: 0 8px 8px 0;
            background-color: #f5f5f5;
            border-radius: 20px;
            padding: 6px 12px;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .amenity-item label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .amenity-item .material-symbols-outlined {
            font-size: 18px;
            color: #555;
        }

        .amenity-item:hover {
            background-color: #e0e0e0;
            transform: translateY(-1px);
        }

        /* Optional selected state */
        .amenity-item.selected {
            background-color: #e6c88c;
            border-color: #b8860b;
        }
        .amenity-item.selected .material-symbols-outlined,
        .amenity-item.selected label {
            color: #333;
        }
             /* Toast notifications */
         .success-toast {
             background-color: #4caf50 !important;
         }
        .warning-toast {
            background-color: #ff9800 !important;
        }
        .error-toast {
            background-color: #f44336 !important;
        }

        /* Switch animation */
        .switch label input[type="checkbox"]:checked + .lever {
            background-color: #4caf50 !important;
        }
        .switch label input[type="checkbox"]:checked + .lever:after {
            background-color: #2e7d32 !important;
        }
    </style>


    {{--    @include('admin.room.partials.form_stepper_style')--}}

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
            </div><br>
            <div class="col s12">
                <div class="container">
                    <!-- users view start -->
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 m12 l10">
                                <div class="card subscriber-list-card">
                                    {{--                                <div class="card-content pb-1">--}}
                                    {{--                                    <h4 class="card-title mb-0" style="display: inline-block">Facility List</h4>--}}
                                    {{--                                    <a href="#add_facility-modal" class="modal-trigger" style="float: right"><span--}}
                                    {{--                                            class="chip btn light-green white-text text-accent-2">Add Facility</span></a>--}}
                                    {{--                                </div>--}}
                                    <div class="divider"></div>
                                    {{--                                @include('admin.room.modals.facility.add_facility-modal')--}}
                                    <table class="subscription-table responsive-table" id="room-table">
                                        <thead>
                                        <tr>
                                            <th style="padding-left:2em">Category</th>
                                            <th>Price</th>
                                            <th>Units</th>
                                            <th style="text-align: left;">Availability</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="sortable">
                                        @forelse ($rooms as $room)
                                            <tr class="hoverable z-depth-1"  data-id="{{ $room->id }}">
                                                <td style="padding-left:2em">{{ $room->category->name ?? '—' }}</td>
                                                <td>  <input type="number" class="price-input"
                                                             data-id="{{ $room->id }}"
                                                             value="{{ $room->price_per_night }}"
                                                             style="width: 90px; color: grey">
                                                </td>
                                                <td>{{ $room->num_units ?? '—' }}</td>
                                                <td>
                                                    <div class="switch">
                                                        <label>
                                                            <input type="checkbox" data-id="{{ $room->id }}" {{ $room->availability ? 'checked' : '' }}>
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </td>

                                                <td style="text-align: right">
                                                    <div class="action-container">
                                                        <a href="#room_overview-modal{{ $room->id }}"
                                                           class="modal-trigger action-edit">
                                                            <span class="material-symbols-outlined grey-text">visibility</span>
                                                        </a>
                                                        <a href="{{ route('edit_room', $room->id) }}"
                                                           class="modal-trigger action-edit">
                                                            <span class="material-symbols-outlined grey-text">edit_square</span>
                                                        </a>
                                                        {{--                                                    <a href=""--}}
                                                        {{--                                                       class="modal-trigger action-delete">--}}
                                                        {{--                                                        <span class="material-symbols-outlined grey-text">delete</span>--}}
                                                        {{--                                                    </a>--}}
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="javascript:void(0)" class="drag_handle">
                                                            <span class="material-symbols-outlined grey-text" style="cursor: grab;">drag_indicator</span>
                                                        </a>
                                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>

                                            @include('admin.room.modals.management.room_overview-modal')
                                            {{--                                        @include('admin.room.modals.facility.edit_facility-modal')--}}
                                            {{--                                        @include('admin.room.modals.facility.delete_facility-modal')--}}
                                        @empty
                                            <tr>
                                                <td style="padding-left:2em">Room Category</td>
                                                <td>00,000.00</td>
                                                <td>0</td>
                                                <td>
                                                    <div class="switch">
                                                        <label>
                                                            <input type="checkbox" disabled>
                                                            <span class="lever"></span>
                                                        </label>
                                                    </div>
                                                </td>

                                                <td style="text-align: right">
                                                    <div class="action-container">
                                                        <a href="#room_overview-modal"
                                                           class="modal-trigger action-edit">
                                                            <span class="material-symbols-outlined grey-text">visibility</span>
                                                        </a>
                                                        <a href="#!"
                                                           class="modal-trigger action-edit">
                                                            <span class="material-symbols-outlined grey-text">edit_square</span>
                                                        </a>
                                                        {{--                                                    <a href=""--}}
                                                        {{--                                                       class="modal-trigger action-delete">--}}
                                                        {{--                                                        <span class="material-symbols-outlined grey-text">delete</span>--}}
                                                        {{--                                                    </a>--}}
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="javascript:void(0)" class="drag_handle">
                                                            <span class="material-symbols-outlined grey-text" style="cursor: grab;">drag_indicator</span>
                                                        </a>
                                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
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




@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/data-tables/js/dataTables.select.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/data-tables.js') }}"></script>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable
        const sortableEl = document.getElementById('sortable');
        if (sortableEl) {
            new Sortable(sortableEl, {
                handle: '.drag_handle',
                animation: 150,
                onEnd: function() {
                    const order = Array.from(sortableEl.querySelectorAll('tr')).map((row, index) => ({
                        id: row.dataset.id,
                        position: index + 1
                    }));

                    fetch('{{ route("rooms.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order })
                    }).catch(console.error);
                }
            });
        }

        // Price input handler
        document.querySelectorAll('.price-input').forEach(input => {
            input.addEventListener('keydown', async function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    try {
                        const response = await fetch(`/rooms/${this.dataset.id}/update-price`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ price_per_night: this.value })
                        });

                        if (!response.ok) throw new Error('Update failed');

                        this.style.color = 'green';
                        setTimeout(() => this.style.color = 'grey', 1000);
                    } catch (error) {
                        this.style.color = 'red';
                        setTimeout(() => this.style.color = 'grey', 1000);
                        console.error(error);
                    }
                }
            });
        });

        // Checkbox handler
        document.querySelectorAll('td input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', async function() {
                const roomId = this.dataset.id;
                const isAvailable = this.checked;
                const roomRow = this.closest('tr');
                const roomCategory = roomRow.querySelector('td:first-child').textContent.trim();

                try {
                    const response = await fetch('{{ route("rooms.updateAvailability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id: roomId,
                            availability: isAvailable ? 1 : 0
                        })
                    });

                    if (!response.ok) throw new Error('Update failed');

                    const data = await response.json();

                    // Show notification
                    showToastNotification(
                        isAvailable
                            ? `${roomCategory} is now available`
                            : `${roomCategory} is no longer available`,
                        isAvailable ? 'success' : 'warning'
                    );

                } catch (error) {
                    console.error(error);
                    // Revert checkbox state if update fails
                    this.checked = !isAvailable;
                    showToastNotification('Failed to update availability', 'error');
                }
            });
        });

// Toast notification function (add this to your scripts)
        function showToastNotification(message, type = 'info') {
            // If using Materialize toast
            if (typeof M !== 'undefined' && M.toast) {
                M.toast({html: message, classes: `${type}-toast`});
            }
            // Fallback using SweetAlert
            else if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            }
            // Basic browser alert fallback
            else {
                alert(message);
            }
        }
    });
</script>

@endsection
