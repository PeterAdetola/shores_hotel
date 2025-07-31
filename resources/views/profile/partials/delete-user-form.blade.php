

        <div class="card-panel mb-5">          
          <div class="card-content">
            <div class="row mb-2 ml-3"><i class="material-icons left red-text small-ico-bg">info</i></div>
            <div class="divider mb-2"></div>
              <h6 class="card-title">Delete Account</h6>
              <div class="caption mb-0">
                 <p class="collection" style="padding:2em">
                   {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                  </p>
              
    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 red-text" />
               <a href="#delete-account" class="btn-large red modal-trigger" >Delete Account</a>
              </div>
          </div>
        </div>


@include('profile.modals.delete-account')