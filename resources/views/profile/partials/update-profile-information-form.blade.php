
                      <!-- Profile info form -->
                        <div class="card">
                            <div class="card-content">
                  <h6 class="card-title">Profile Information</h6>
                  <p>Update your account's profile information and email address.</p><br>
    
       @if(Session::has('messageTitle'))
               <div class="card-alert card pink lighten-5" >
                <div class="card-content pink-text darken-1">
                  <span class="card-title pink-text darken-1"><i class="material-icons">notifications</i>&nbsp;{{ Session::get('messageTitle') }}</span>
                  <p>{{ Session::get('messageBody') }}</p>
                </div>
                <div class="card-action pink lighten-4">
                  <a href="#" data-dismiss="alert" aria-label="Close" class="close pink-text" aria-hidden="true">Ok</a>
                </div>
              </div>
       @endif
                  <div class="divider mb-1 mt-1"></div>
          <form id="send-verification" method="post" action="{{ route('verification.send') }}">
              @csrf
          </form>
          <form class="updateProfile" action="{{ route('profile.update') }}" method="post">
        @csrf
        @method('patch')
              <input type="hidden" name="tab" value="profile_info">
            <div class="row">

              <div class="col s12">
                <div class="input-field">
                  <label for="name">Name</label>
                  <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}"  data-error=".errorTxt1" required autofocus />
                  @error('name')
                  <small class="errorTxt1  red-text">{{ $message }}*</small>
                  @enderror 
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <label for="username">Username</label>
                  <input id="username" type="text" name="username" value="{{ old('username', Auth::user()->username) }}" data-error=".errorTxt2" required />  
                  @error('username')
                  <small class="errorTxt3  red-text">{{ $message }}*</small>
                  @enderror 
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <label for="email">E-mail</label>
                  <input id="email" name="email" value="{{ old('email', Auth::user()->email) }}" type="email" data-error=".errorTxt3" required />
                  @error('email')
                  <small class="errorTxt3  red-text">{{ $message }}*</small>
                  @enderror 
                </div>
              </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="col s12">
                <div class="card-alert card grey lighten-5">
                  <div class="card-content grey-text" style="padding: 1em;">
                    <p>Your email is not confirmed. Please check your inbox.
                    <button  form="send-verification" class="btn-flat red-text" style="font-size: 1em; display: inline; margin-right: 3em; margin-top: 3px; padding-left: 5px; padding-right: 5px;">Resend confirmation</a></button></p>
                  </div>
                  <button type="button" class="close grey-text" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
            </div>

            @if (session('status') === 'verification-link-sent')
            <div class="col s12">
              <div class="card-alert card light-green lighten-5">
                <div class="card-content grey-text">
                  <p>A new verification link has been sent to your email address.</p>
                </div>
                <button type="button" class="close grey-text" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
            </div>
            @endif

            @endif
              
              <div class="col s12 display-flex justify-content-end form-action">
                
                <a href="#update-profile" type="submit" class="modal-trigger btn-large waves-effect waves-light mr-2" >
                  Save
                </a>
                </div>
              </div>
            </div>
 @include('profile.modals.save-profile_info')
          </form>
                                </div>
                            </div>
                      <!-- End profile info form -->