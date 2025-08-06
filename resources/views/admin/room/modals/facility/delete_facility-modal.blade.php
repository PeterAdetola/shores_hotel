
<!-- Start Modal -->
<div id="delete_facility-modal{{ $facility->id }}" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Delete Facility</h6>

        <div class="progress collection">
            <div id="delete_facility{{ $facility->id }}-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12" id="account">
                <!-- users delete media object ends -->
                <!-- users delete account form start -->
                <form method="POST" action="{{ route('room-categories.destroy', $facility->id) }}">
                    @csrf
                    @method('DELETE')

                    <div class="col s12">
                        <div class="row">
                            <div class="col s12 input-field">
                                <input id="name" disabled name="name" type="text" class="validate"  value="{{ $facility->name }}" />
                                <label for="name">Facility Name</label>
                            </div>

                            <div class="col s12 mt-7">
                                <button  id="deleteFacilityBtn{{ $facility->id }}" type="submit" class="modal-action waves-effect red waves-red btn-large"> Delete Facility </button>
                                <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- users delete account form ends -->
            </div>
        </div>
        <!-- </div> -->
    </div>
</div>
<script>
    document.getElementById("deleteFacilityBtn{{ $facility->id }}").addEventListener("click", function() {
        var preloader = document.getElementById("delete_facility{{ $facility->id }}-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
