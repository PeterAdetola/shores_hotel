<!-- Start Modal -->
<div id="delete_room-modal" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Delete {{$room->category->name}}</h6>

        <div class="progress collection">
            <div id="delete_room-preloader" class="indeterminate" style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('room.destroy', $room->id) }}">
                    @csrf
                    @method('DELETE')

                    <h5 class="red-text">You are about to delete {{$room->category->name}}</h5>
                    <p>Would you like to proceed?</p>
                    <!-- Simple flexbox solution -->
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button id="deleteRoomBtn" type="submit" class="red waves-effect waves-green btn-large">Yes, Delete
                            Room
                        </button>
                    </div>
                </form>
                <!-- users edit account form ends -->
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("deleteRoomBtn").addEventListener("click", function () {
        var preloader = document.getElementById("delete_room-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
