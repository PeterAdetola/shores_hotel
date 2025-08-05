
@extends('admin.admin_master')
@section('admin')
    @php
        $pageTitle = 'Manage Rooms';
        $categories = getRoomCategories();
    @endphp



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

                        <!-- Somethings removed here -->

                    </div>
                </div>
            </div><br>
            <div class="col s12">
                <div class="container">
                    <!-- users view start -->
                    <div class="row">
                        <div class="col s12 m12 l7">
                            <div class="card subscriber-list-card">
                                <div class="card-content pb-1">
                                    <h4 class="card-title mb-0" style="display: inline-block">Accommodation Categories</h4>
                                    <a href="#add_category-modal" class="modal-trigger" style="float: right"><span class="chip btn light-green white-text text-accent-2">Add Category</span></a>
                                </div>
                                @include('admin.room.modals.add_category-modal')
                                <table class="subscription-table responsive-table highlight">
                                    <thead>
                                    <tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $category->name }}</td>
                                            <td style="text-align: right">
                                                <div class="action-container" >
                                                    <a href="#edit_category-modal{{ $category->id }}" class="modal-trigger action-edit">
                                                        <span class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                    <a href="#delete_category-modal{{ $category->id }}" class="modal-trigger action-delete">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
                                                    </a>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                            @include('admin.room.modals.edit_category-modal')
                                            @include('admin.room.modals.delete_category-modal')
                                    @empty
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;Category Name</td>
                                                    <td style="text-align: right">
                                                        <div class="action-container" >
                                                            <a href="#" class="modal-trigger action-edit">
                                                                <span class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
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


                    <div class="row">
                        <div class="col s12 m12 l7 mb-10">
                            <div class="card subscriber-list-card">
                                <div class="card-content pb-1">
                                    <h4 class="card-title mb-0" style="display: inline-block">Feature List</h4>
                                    <a href="#add_feature-modal" class="modal-trigger" style="float: right"><span class="chip btn light-green white-text text-accent-2">Add Feature</span></a>
                                </div>
{{--                                @include('admin.room.modals.add_feature-modal')--}}
                                <table class="subscription-table responsive-table highlight">
                                    <thead>
                                    <tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;Category Name</td>
                                            <td style="text-align: right">
                                                <div class="action-container" >
                                                    <a href="#" class="modal-trigger action-edit">
                                                        <span class="chip pink lighten-5 pink-text text-accent-2">Edit</span>
                                                    </a>
                                                    <a href="#" class="modal-trigger action-edit">
                                                        <span class="material-symbols-outlined grey-text">delete</span>
                                                    </a>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

{{--                    </div>--}}
                    <!-- users view ends -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: Page Main-->





@endsection
@section('scripts')
{{--    <script src="{{ asset('backend/assets/vendors/dropify/js/dropify.min.js') }}"></script>--}}
{{--    <script src="{{ asset('backend/assets/js/scripts/form-file-uploads.js') }}"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>--}}
@endsection
