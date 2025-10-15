@extends('admin.admin_master')
@section('admin')
    @php
        $pageTitle = 'Add Room';
    @endphp
    @section('vendor_styles')
        <link rel="stylesheet" type="text/css"
              href="{{ asset('admin/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/dropify/css/dropify.min.css') }}">
    @endsection
    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/pages/form-wizard.css') }}">
        @include('admin.room.partials.form_stepper_style')
    @endsection
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

                        <!-- Something's removed here -->

                    </div>
                </div>
            </div>
            <br>
            <div class="col s12">
                <div class="container">
                    <!-- page content starts -->
                    <div class="row">
                        <div class="col s12 mb-5">
                            <div class="card">
                                @if(session('error'))
                                    <div class="card-alert card orange lighten-5">
                                        <div class="card-content orange-text">
                                            <p>{{ session('error_message') }}</p>
                                        </div>
                                        <button type="button" class="close orange-text" data-dismiss="alert"
                                                aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="card-content pb-0">
                                    <div class="card-header mb-2">
                                        <h4 class="card-title">{{ $pageTitle }}</h4>
                                    </div>

                                    <form action="{{ route('rooms.store') }}" method="POST"
                                          enctype="multipart/form-data"  onsubmit="disableSubmit(this)">
                                        @csrf
                                        <ul class="stepper horizontal" id="horizStepper">
                                            <li class="step active">
                                                <div class="step-title waves-effect">Information</div>
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m3 s6">
                                                            <select name="room_type" required>
                                                                <option value="" disabled selected>Select Type</option>
                                                                <option value="0">Hotel</option>
                                                                <option value="1">Apartment</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-field col m3 s6">
                                                            <select name="room_category_id" class="browser-default" required>
                                                                <option value="" disabled selected>Select Category
                                                                </option>
                                                                @forelse (getRoomCategories() as $category)
                                                                    <option
                                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @empty
                                                                    <option value="" disabled>No categories available
                                                                    </option>
                                                                @endforelse
                                                            </select>
                                                        </div>

                                                        <div class="input-field col m3 s6">
                                                            <label for="pricePerNight">Price Per Night: <span
                                                                    class="red-text">*</span></label>
                                                            <input type="number" id="pricePerNight" class="validate"
                                                                   name="price_per_night" value="0.00" min="0.00"
                                                                   required>

                                                        </div>
                                                        <div class="input-field col m3 s6">
                                                            <label for="numUnit">Number of Units: <span
                                                                    class="red-text">*</span></label>
                                                            <input type="number" id="numUnit" class="validate"
                                                                   name="num_units" value="1" min="1" max="10" required>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="input-field col m3 s6">
                                                            <label for="adultMax">Adult Maximum No. :
                                                                <span id="adultValue">1</span> <span class="red-text">*</span>
                                                            </label>
                                                            <input id="adultMax" name="adult_max" type="number" min="1" max="5" value="1"/>
                                                        </div>

                                                        <div class="input-field col m3 s6">
                                                            <label for="childrenMax">
                                                                Children Maximum No. :
                                                                <span id="childrenValue">0</span> <span class="red-text">*</span>
                                                            </label>
                                                            <input id="childrenMax" name="children_max" type="number" min="0" max="5" value="0"/>
                                                        </div>
                                                        <div class="input-field col m3 s6">
                                                            <div class="availability-box">
                                                                <div class="switch">
                                                                    <label>
                                                                        <input type="checkbox" name="availability"
                                                                               class="hidden-checkbox" value="1"
                                                                               checked>
                                                                        <span class="lever"></span>
                                                                        <span
                                                                            class="availability-text">Availability</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                    </div>

                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="red btn btn-reset" type="button"
                                                                        data-stepper-reset>
                                                                    <i class="material-icons left">clear</i>Reset
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="btn btn-light previous-step" type="button" disabled>
                                                                    <i class="material-icons left">arrow_back</i>
                                                                    Prev
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="waves-effect waves dark btn btn-primary next-step" type="button">
                                                                    Next
                                                                    <i class="material-icons right">arrow_forward</i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="step">
                                                <div class="step-title waves-effect">Images</div>
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <input name="image" type="file" id="input-file-now-custom-2" class="dropify" data-height='200'/>
                                                            <small class="errorTxt3  grey-text">Upload image in JPG (1500 x 844)</small>
                                                        </div>
                                                        <div class="input-field col m6 s12">
                                                            <div id="simpleList" class="preview-container"></div>
                                                            <small class="errorTxt3  grey-text">Upload images in JPG
                                                                (1500 x 844)</small>
                                                            <div class="file-field input-field ml-2">
                                                                <div class="btn">
                                                                    <span>File</span>
                                                                    <input name="images[]" id="file-upload"
                                                                           accept="image/*" type="file" multiple>
                                                                </div>
                                                                <div class="file-path-wrapper">
                                                                    <input class="file-path validate" type="text"
                                                                           placeholder="Upload one or more files"
                                                                           readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="red btn btn-reset" type="button"
                                                                        data-stepper-reset>
                                                                    <i class="material-icons left">clear</i>Reset
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="btn btn-light previous-step"
                                                                        type="button">
                                                                    <i class="material-icons left">arrow_back</i>
                                                                    Prev
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button
                                                                    class="waves-effect waves dark btn btn-primary next-step"
                                                                    type="button">
                                                                    Next
                                                                    <i class="material-icons right">arrow_forward</i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="step">
                                                <div class="step-title waves-effect">Amenities</div>
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <p>Amenities</p>
                                                            <div class="amenities-container">
                                                                @forelse (getFacilities() as $facility)
                                                                    <div class="amenity-item">
                                                                        <input
                                                                            type="checkbox"
                                                                            id="facility_{{ $facility->id }}"
                                                                            name="facilities[]"
                                                                            value="{{ $facility->id }}">

                                                                        <label for="facility_{{ $facility->id }}">
                                                                            <span
                                                                                class="material-symbols-outlined">{{ $facility->icon }}</span>
                                                                            {{ $facility->name }}
                                                                        </label>
                                                                    </div>
                                                                @empty
                                                                    <p>No facilities available</p>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                        <div class="col l6">
                                                            <div class="input-field col s12">
                                                                <textarea name="description" id="textarea2"
                                                                          class="materialize-textarea"></textarea>
                                                                <label for="textarea2">Textarea</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="red btn btn-reset" type="button"
                                                                        data-stepper-reset>
                                                                    <i class="material-icons left">clear</i>Reset
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button class="btn btn-light previous-step"
                                                                        type="button">
                                                                    <i class="material-icons left">arrow_back</i>
                                                                    Prev
                                                                </button>
                                                            </div>
                                                            <div class="col m4 s12 mb-3">
                                                                <button id="addRoomBtn"
                                                                        class="waves-effect waves-dark btn btn-primary"
                                                                        type="submit">
                                                                    Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </form>


                                    <div class="row">
                                        <div class="progress collection">
                                            <div id="add_room-preloader" class="indeterminate"
                                                 style="display:none; border:2px #ebebeb solid"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- page content ends -->
                </div>
                <!-- <div class="content-overlay"></div> -->
            </div>
        </div>
    </div>
    <!-- END: Page Main-->
@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/materialize-stepper/materialize-stepper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/dropify/js/dropify.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/form-wizard.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts/form-file-uploads.js') }}"></script>
    <script src="{{ asset('admin/assets/js/custom/stepper-form.js') }}"></script>

    <script>
        document.getElementById("addRoomBtn").addEventListener("click", function () {
            var preloader = document.getElementById("add_room-preloader");
            preloader.style.display = "block";
        });

        function disableSubmit(form) {
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerText = 'Submitting...'; // Optional
            return true; // Continue form submission
        }
    </script>
@endsection
