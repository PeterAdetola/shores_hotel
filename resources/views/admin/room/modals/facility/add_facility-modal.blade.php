
<!-- Start Modal -->
<div id="add_facility-modal" class="modal" style="padding:1em;">
    <div class="modal-content">
        <h6 class="card-title ml-2" style="display:inline-block;">Add Facility</h6>

        <div class="progress collection">
            <div id="add_facility-preloader" class="indeterminate"  style="display:none;
        border:2px #ebebeb solid"></div>
        </div>

        <!-- <div class="card-body"> -->
        <div class="row">
            <div class="col s12" id="account">
                <!-- users edit media object ends -->
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('facilities.store') }}">
                    @csrf

                    <div class="col s12">
                        <div class="row">

                            <div class="col s12 input-field">
                                <input id="name" name="name" type="text" class="validate" required />
                                <label for="name">Facility Name</label>
                            </div>

                                <div class="col s12 input-field">
                                    <input id="icon" name="icon" type="text" class="validate" required />
                                    <label for="icon">Icon</label>
                                </div>

                            <div class="col s12 mt-7">
                                <button  id="addFacilityBtn" type="submit" class="modal-action waves-effect waves-green btn-large"> Add Facility </button>
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
    document.getElementById("addFacilityBtn").addEventListener("click", function() {
        var preloader = document.getElementById("add_facility-preloader");
        preloader.style.display = "block";
    });
</script>
<!-- /End Modal -->
