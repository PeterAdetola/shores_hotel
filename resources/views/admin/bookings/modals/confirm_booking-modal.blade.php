
<!-- Start Modal -->
<div id="confirm_booking-modal{{ $booking->id }}" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Confirm Reservation</h6>

        <div class="progress collection">
            <div id="edit_category{{ $booking->id }}-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12" id="account">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('room-categories.update', $booking->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="col s12">
                        <div class="row">

                            <p>You are about to confirm that the {{ $booking->lodging_type ?? optional($booking->room->category)->name }} is available for
                                {{ $booking->adults }} {{ $booking->adults > 1 ? 'adults' : 'adult' }} @if ($booking->children == 1)
                                    and 1 child
                                @elseif ($booking->children > 1)
                                    and {{ $booking->children }} children
                                @endif
                            </p>
                            <div class="col s12 mt-7">
                                <button  id="editCategoryBtn{{ $booking->id }}" type="submit" class="modal-action waves-effect waves-green green btn-large"> Yes, Its Available </button>
                                <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
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
<script>
    document.getElementById("editCategoryBtn{{ $booking->id }}").addEventListener("click", function() {
        var preloader = document.getElementById("edit_booking{{ $booking->id }}-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
