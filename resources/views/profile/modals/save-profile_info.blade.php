
    <div id="update-profile" class="modal" style="padding:2em;">
        <div class="modal-content">
          <h6 class="card-title">You are about to change your profile information</h6>
          
      <div class="progress collection">
        <div id="updateProfilePreloader" class="indeterminate" style="display:none; 
        border:2px #ebebeb solid"></div>
      </div>

        <p>Do you want to proceed with the change?</p>
        </div>


        <div class="modal-footer">
          <button id="updateProfileBtn" type="submit" class="modal-action waves-effect waves-red btn-large">Yes, Update Profile</button>
          <a id="reload" href="javascript:void(0)" class="btn-large btn-flat modal-close">No, Cancel</a>
        </div>
      </div>
<script>
      // Preloader Script
document.getElementById("updateProfileBtn").addEventListener("click", function() {
  var preloader = document.getElementById("updateProfilePreloader");
  preloader.style.display = "block";
});
</script>