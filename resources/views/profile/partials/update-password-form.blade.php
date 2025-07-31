<!-- Pasword update form -->
                        <div class="card mb-4">
                            <div class="card-content">
          <h6 class="card-title">Update Password</h6>
          <p>Ensure your account is using a long, random password to stay secure.</p>

            @if (session('status') === 'password-updated')
              <div class="col s12">
                <div class="card-alert card light-green lighten-5">
                  <div class="card-content grey-text">
                    <p>Password updated</p>
                  </div>
                  <button type="button" class="close grey-text" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              </div>
            @endif

          <form id="updatePassword" class="paaswordvalidate" action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="update_password" />
            <div class="row">

              <div class="col s12">
                <div class="input-field">
                  <input id="current_password" type="password" name="current_password" data-error=".errorTxt4" required />
                  <label for="current_password">Current Password</label> 
                  <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 red-text" /> 
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="password" name="password" type="password" data-error=".errorTxt5" required />
                  <label for="password">New Password</label>
                  <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2  red-text" /> 
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="password_confirmation" type="password" name="password_confirmation" data-error=".errorTxt6" required />
                  <label for="password_confirmation">Retype new Password</label> 
                  <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2  red-text" /> 
                </div>
              </div>

              <div class="col s12 display-flex justify-content-end form-action">
                <a href="#update-password" type="submit" class="modal-trigger btn-large waves-effect waves-light mr-1" >Save</a>
              </div>

              </div>
            </div>
          @include('profile.modals.update-password')
          </form>
        </div>