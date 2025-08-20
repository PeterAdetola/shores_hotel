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
                                    <ul class="stepper horizontal" id="horizStepper">
                                        <li class="step active">
                                            <div class="step-title waves-effect">Information</div>
                                            <form action="{{ route('rooms.update.info', $room->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <select name="room_category_id" required>
                                                                <option value="" disabled selected>Select Category
                                                                </option>
                                                                @foreach (getRoomCategories() as $category)
                                                                    <option value="{{ $category->id }}" {{ $room->room_category_id == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="input-field col m4 s610">
                                                            <label for="pricePerNight">Price Per Night: <span
                                                                    class="red-text">*</span></label>
                                                            <input type="number" id="pricePerNight" name="price_per_night"
                                                                   value="{{ old('price_per_night', $room->price_per_night) }}" min="0.00" step="0.01" required>

                                                        </div>
                                                        <div class="input-field col m2 s2">
                                                            <label for="numUnit">Number of Units: <span
                                                                    class="red-text">*</span></label>
                                                            <input type="number" id="numUnit" name="num_units"
                                                                   value="{{ old('num_units', $room->num_units) }}" min="1" max="10" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <label for="adultMax">Adult Maximum No. : <span id="adultValue">{{ $room->adult_max }}</span></label><br><br>
                                                            <span class="range-field">
                           <input id="adultMax" name="adult_max" type="range" min="1" max="5" value="{{ $room->adult_max }}">
                        </span>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label for="childrenMax">Children Maximum No. : <span id="childrenValue">{{ $room->children_max }}</span></label><br><br>
                                                            <span class="range-field">
                            <input id="childrenMax" name="children_max" type="range" min="0" max="5" value="{{ $room->children_max }}">
                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button
                                                                    class="waves-effect waves dark  btn-large btn-primary"
                                                                    type="submit">
                                                                    Update Information
                                                                    {{--                                                                    <i class="material-icons right">arrow_forward</i>--}}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                        <li class="step">
                                            <div class="step-title waves-effect">Images</div>
                                            <form action="{{ route('rooms.update.featuredImage', $room->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <input name="image" type="file" id="input-file-now-custom-2"
                                                                   class="dropify"
                                                                   data-default-file="{{ $room->featured_image_url }}"
                                                                   data-height='200'
                                                            />
                                                            <small>Upload JPG (1500 x 844)</small>
                                                        </div>
                                                        {{-- Gallery Images --}}
                                                        <div class="input-field col m6 s12">
                                                            <div id="simpleList" class="preview-container">
                                                                @foreach ($room->galleryImages as $img)
                                                                    <img src="{{ asset('storage/' . $img->image_path) }}" width="80" style="margin:4px;">
                                                                @endforeach

                                                            </div>
                                                            <div class="file-field input-field">
                                                                <div class="btn">
                                                                    <span>Edit Images</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button
                                                                    class="waves-effect waves dark btn-large btn-primary"
                                                                    type="submit">
                                                                    Update Images
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                        <li class="step">
                                            <div class="step-title waves-effect">Facilities</div>
                                            <form action="{{ route('rooms.update.facilities', $room->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="step-content">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <p>Facilities</p>
                                                            <div class="amenities-container">
                                                                @foreach (getFacilities() as $facility)
                                                                    <div class="amenity-item">
                                                                        <input type="checkbox" id="facility_{{ $facility->id }}" name="facilities[]"
                                                                               value="{{ $facility->id }}"
                                                                            {{ in_array($facility->id, $room->facilities->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                                        <label for="facility_{{ $facility->id }}">
                                                                            <span class="material-symbols-outlined">{{ $facility->icon }}</span>
                                                                            {{ $facility->name }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col l6">
                                                            <div class="input-field col s12">
                                                                <div class="switch">
                                                                    <label>
                                                                        <input type="checkbox" name="availability" value="1" {{ $room->availability ? 'checked' : '' }}>
                                                                        <span class="lever"></span> Availability
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="input-field col s12">
                                                                <textarea name="description" id="textarea2" class="materialize-textarea">{{ old('description', $room->description) }}</textarea>
                                                                <label for="textarea2">Textarea</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="step-actions">
                                                        <div class="row">
                                                            <div class="col m4 s12 mb-3">
                                                                <button id="addRoomBtn"
                                                                        class="waves-effect waves-dark btn btn-primary"
                                                                        type="submit">
                                                                    Update Room
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                    </ul>


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
