
<!-- Start Modal -->
<div id="add_image-modal" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Add Image</h6>

        <div class="progress collection">
            <div id="add_img-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('room.gallery.add_image', $room->id) }}" enctype="multipart/form-data" class="update-form">
                    @csrf

                    <div class="row">
                        <div class="input-field col m7 s12">
                            <input name="image"
                                   type="file"
                                   class="dropify"
                                   data-height='200'/>
                            <small class="errorTxt3 grey-text">Upload image in JPG (1500 x 844)</small>
                        </div>
                    </div>

                    <div class="divider mt-1 mb-2"></div>

                    <!-- Simple flexbox solution -->
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button id="addImgBtn" type="submit"
                                class="waves-effect waves-green btn-large">Add Image</button>
                    </div>
                </form>
                    <!-- users edit account form ends -->
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("addImgBtn").addEventListener("click", function() {
        var preloader = document.getElementById("add_img-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
