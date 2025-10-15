<div id="edit_img-modal{{ $img->id }}" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2 left" style="display:inline-block;">Update Image</h6>
{{--        --}}


        <div class="progress collection">
            <div id="edit_img-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <form method="POST" action="{{ route('room.gallery.update', [$room->id, $img->id]) }}" enctype="multipart/form-data" class="update-form">
                @csrf
                @method('PATCH')
                <div class="col s12">

                    <div class="row">
                        <div class="input-field col m7 s12">
                            <input name="image"
                                   data-default-file="{{ asset('uploads/' . $img->image_path) }}"
                                   type="file"
                                   class="dropify"
                                   data-height='200'/>
                            <small class="errorTxt3 grey-text">Upload image in JPG (1500 x 844)</small>
                        </div>
                    </div>

                    <div class="divider mt-1 mb-2"></div>

                    <button id="editImgBtn" type="submit"
                            class="modal-action waves-effect waves-green btn-large">Update</button>
            </form>

            <!-- Delete form (separate) -->
            <form method="POST" action="{{ route('room.gallery.delete', ['room' => $room->id, 'roomImage' => $img->id]) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-floating waves-effect waves-light gradient-45deg-purple-deep-orange right">
                    <i class="material-icons">delete</i>
                </button>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    document.getElementById("editImgBtn").addEventListener("click", function () {
        var preloader = document.getElementById("edit_img-preloader");
        preloader.style.display = "block";
    });
</script>
