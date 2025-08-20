@extends('admin.admin_master')
@section('admin')
    @php
        $pageTitle = 'Room Config';
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
                    <!-- Accommodation Categories start -->
                    <div class="row">
                        <div class="col s12 m12 l7">
                            <div class="card subscriber-list-card">
                                <div class="card-content pb-1">
                                    <h4 class="card-title mb-0" style="display: inline-block">Accommodation
                                        Categories</h4>
                                    <a href="#add_category-modal" class="modal-trigger" style="float: right"><span
                                            class="chip btn light-green white-text text-accent-2">Add Category</span></a>
                                </div>
                                @include('admin.room.modals.category.add_category-modal')
                                <table class="subscription-table responsive-table highlight">
                                    <thead>
                                    <tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse (getRoomCategories() as $category)
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $category->name }}</td>
                                            <td style="text-align: right">
                                                <div class="action-container">
                                                    <a href="#edit_category-modal{{ $category->id }}"
                                                       class="modal-trigger action-edit">
                                                        <span
                                                            class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                    <a href="#delete_category-modal{{ $category->id }}"
                                                       class="modal-trigger action-delete">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
                                                    </a>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        @include('admin.room.modals.category.edit_category-modal')
                                        @include('admin.room.modals.category.delete_category-modal')
                                    @empty
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;Category Name</td>
                                            <td style="text-align: right">
                                                <div class="action-container">
                                                    <a href="#" class="modal-trigger action-edit">
                                                        <span
                                                            class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                    <a href="#" class="modal-trigger action-delete">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
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
                    <!-- Accommodation Categories ends -->
                    <!-- Facility List start -->
                    <div class="row">
                        <div class="col s12 m12 l7 mb-10">
                            <div class="card subscriber-list-card">
                                <div class="card-content pb-1">
                                    <h4 class="card-title mb-0" style="display: inline-block">Facility List</h4>
                                    <a href="#add_facility-modal" class="modal-trigger" style="float: right"><span
                                            class="chip btn light-green white-text text-accent-2">Add Facility</span></a>
                                </div>
                                <div class="divider"></div>
                                @include('admin.room.modals.facility.add_facility-modal')
                                <table class="subscription-table responsive-table" id="facility-table">
                                    <thead>
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;Facility</td>
                                        <td>&nbsp;&nbsp;Icon</td>
                                        <td style="text-align: right">
                                            <div class="action-container">
                                                Actions
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </div>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody id="sortable">
                                    @forelse (getFacilities() as $facility)
                                        <tr class="hoverable z-depth-1"  data-id="{{ $facility->id }}">
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $facility->name }}</td>
                                            <td>&nbsp;&nbsp;<span
                                                    class="material-symbols-outlined">{{ $facility->icon }}</span></td>
                                            <td style="text-align: right">
                                                <div class="action-container">
                                                    <a href="#edit_facility-modal{{ $facility->id }}"
                                                       class="modal-trigger action-edit">
                                                        <span
                                                            class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                         <a href="#delete_facility-modal{{ $facility->id }}"
                                                            class="modal-trigger action-delete">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
                                                    </a>

                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href="javascript:void(0)" class="drag_handle">
                                                        <span class="material-symbols-outlined grey-text" style="cursor: grab;">drag_indicator</span>
                                                    </a>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        @include('admin.room.modals.facility.edit_facility-modal')
                                        @include('admin.room.modals.facility.delete_facility-modal')
                                    @empty
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;Facility Name</td>
                                            <td>&nbsp;&nbsp;<span class="material-symbols-outlined">check_circle</span>
                                            </td>
                                            <td style="text-align: right">
                                                <div class="action-container">
                                                    <a href="#" class="modal-trigger action-edit">
                                                        <span
                                                            class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                    <a href="#" class="modal-trigger action-edit">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
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
                    <!-- Facility List end -->
                </div>
            </div>
        </div>
        <!-- END: Page Main-->
        <script>

            document.addEventListener('DOMContentLoaded', function() {
                let sortable = new Sortable(document.getElementById('sortable'), {
                    handle: '.drag_handle', // or whatever drag handle you want
                    animation: 150,
                    onEnd: function () {
                        let order = [];
                        document.querySelectorAll('#sortable tr').forEach((row, index) => {
                            order.push({ id: row.dataset.id, position: index + 1 });
                        });

                        fetch('{{ route("facilities.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: order })
                        });
                    }
                });
            });

        </script>
@endsection
