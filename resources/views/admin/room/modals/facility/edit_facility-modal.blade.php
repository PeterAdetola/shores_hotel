
<!-- Start Modal -->
<div id="edit_facility-modal{{ $facility->id }}" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Edit Facility</h6>

        <div class="progress collection">
            <div id="edit_facility{{ $facility->id }}-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12" id="account">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('facilities.update', $facility->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="col s12">
                        <div class="row">

                            <div class="col s12 input-field">
                                <input id="name" name="name" type="text" class="validate"  value="{{ $facility->name }}" />
                                <label for="name">Facility Name</label>
                            </div>

                            <div class="col s12 input-field">
                                <input id="icon" name="icon" type="text" class="validate"  value="{{ $facility->icon }}" />
                                <label for="icon">Icon</label>
                            </div>

                            <div class="col s12 mt-7">
                                <button  id="editFacilityBtn{{ $facility->id }}" type="submit" class="modal-action waves-effect waves-green btn-large"> Edit Facility </button>
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
    document.getElementById("editFacilityBtn{{ $facility->id }}").addEventListener("click", function() {
        var preloader = document.getElementById("edit_facility{{ $facility->id }}-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
