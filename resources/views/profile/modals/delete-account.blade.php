
      
<!-- Modal Structure -->
<div id="delete-account" class="modal border-radius-10" style="padding:2em;">
  <form id="deleteAccount" method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
    <div class="modal-content">
      <h6 class="card-title">Are you sure you want to delete your account?</h6>
          
      <div class="progress collection">
        <div id="deleteAcctPreloader" class="indeterminate" style="display:none; 
        border:2px #ebebeb solid"></div>
      </div>
      <p class="">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
    <input id="password" type="password" name="password" placeholder="Password" required autofocus />
    </div>
    <div class="modal-footer">
      <button id="deleteAcctBtn" type="submit" class="modal-action red waves-effect waves-red btn-large">Delete</button>
      <a href="javascript:void(0)" class="btn-large btn-flat modal-close">Cancel</a>
    </div>
  </form>
  </div>
<script>
      // Preloader Script
document.getElementById("deleteAcctBtn").addEventListener("click", function() {
  var preloader = document.getElementById("deleteAcctPreloader");
  preloader.style.display = "block";
});
</script>