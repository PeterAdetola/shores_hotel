
    <div id="update-password" class="modal" style="padding:2em;">
        <div class="modal-content">
          <h6 class="card-title">You are about to alter your password details</h6>
          
      <div class="progress collection">
        <div id="updatePwdPreloader" class="indeterminate" style="display:none; 
        border:2px #ebebeb solid"></div>
      </div>

        <p>Do you want to proceed with the change?</p>
        </div>


        <div class="modal-footer">
          <button id="updatePwdBtn" type="submit" class="modal-action waves-effect waves-red btn-large">Yes, Update</button>
          <a id="reload" href="javascript:void(0)" class="btn-large btn-flat modal-close">No, Cancel</a>
        </div>
      </div>
<script>
      // Preloader Script
document.getElementById("updatePwdBtn").addEventListener("click", function() {
  var preloader = document.getElementById("updatePwdPreloader");
  preloader.style.display = "block";
});
</script>