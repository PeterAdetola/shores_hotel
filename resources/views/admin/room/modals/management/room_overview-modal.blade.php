
<!-- Start Modal -->
<div id="room_overview-modal{{ $room->id }}" class="modal" style="padding:0.2em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Room Category</h6>

        <div class="divider"></div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12" id="account">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col s12 m12 l5 mt-3">
                            <div style="
                            width: 18em;
                            height: 12em;
                            background-image: url('{{ $room->galleryImages->first() ? asset('storage/' . $room->galleryImages->first()->image_path) : asset('images/default.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            ">

                            </div>
                        </div>


                        <div class="col s12 m7 mt-2">
                            <div class="amenities-container row">
                                @foreach($room->facilities as $facility)
                                    <span class="amenity-item">
            <label for="facility">
                <span class="material-symbols-outlined">{{ $facility->icon }}</span>
                {{ $facility->name }}
            </label>
        </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
                <!-- users edit account form ends -->
            </div>

        </div>
        <!-- </div> -->
    </div>
</div>
<!-- /End Modal -->
