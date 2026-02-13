@extends('admin.admin_master')
@section('admin')
    <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <h5 class="grey-text">Valentine Season Announcement</h5>
                        </div>
                        <div class="col s6 right-align">
                            <form method="POST" action="{{ route('announcements.unpublishAll', 'valentine') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn red darken-1">
                                    <i class="material-icons left">visibility_off</i>
                                    Unpublish
                                </button>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="card-panel green lighten-4 green-text text-darken-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <ul id="simpleList" class="collapsible">
                        <li class="hoverable">
                            <div class="collapsible-header center" tabindex="0">
                                <div class="ml-10">
                                    <h6 class="row left font-weight-700 grey-text">
                                        💖 Valentine Season Special
                                        <span class="new badge pink" data-badge-caption="Active"></span>
                                    </h6><br>
                                    <p class="row grey-text">Exclusive discounts for Shores Hotel & Shores Apartment</p>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col s12">
                                        <p><strong>Campaign:</strong> Valentine Season Special</p>
                                        <p><strong>Message:</strong> Love is in the air! Treat yourself or your special someone to a relaxing stay with exclusive Valentine discounts.</p>

                                        <p class="mt-2"><strong>Discount Offers:</strong></p>
                                        <ul>
                                            <li>✨ 10% OFF on Weekday bookings</li>
                                            <li>✨ 5% OFF on Weekend bookings</li>
                                        </ul>

                                        <p class="mt-2"><strong>Features:</strong></p>
                                        <ul>
                                            <li>24/7 electricity</li>
                                            <li>Free Wi-Fi</li>
                                            <li>Room service</li>
                                            <li>Serene ambience</li>
                                            <li>Beautiful rooftop lounge access</li>
                                        </ul>

                                        <p class="mt-2"><strong>Duration:</strong> Limited time offer</p>
                                        <p><strong>CTA:</strong> Book Your Romantic Stay ({{ route('getRooms') }})</p>
                                    </div>
                                </div>

                                <div class="divider mb-2 mt-2"></div>

                                <div class="row">
                                    <div class="col s12">
                                        <a href="{{ route('edit.announcement', 'valentine') }}" class="btn-small lime accent-1">
                                            <i class="material-icons left">edit</i>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('announcements.togglePublish', 'valentine') }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-small orange">
                                                <i class="material-icons left">visibility_off</i>
                                                Unpublish
                                            </button>
                                        </form>

                                        <a href="#deleteValentine" class="btn-small red modal-trigger">
                                            <i class="material-icons left">delete</i>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div id="deleteValentine" class="modal">
                                <div class="modal-content">
                                    <h5>Delete Valentine Announcement?</h5>
                                    <p>Are you sure you want to delete the Valentine Season announcement? This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
                                    <form method="POST" action="{{ route('announcements.destroy', 'valentine') }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="waves-effect waves-red btn red">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed-action-btn">
        <a href="{{ route('announcements.create') }}" class="btn-floating btn-large lime accent-1">
            <i class="large material-icons">add</i>
        </a>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize modals
                var elems = document.querySelectorAll('.modal');
                M.Modal.init(elems);

                // Initialize collapsible
                var collapsibleElems = document.querySelectorAll('.collapsible');
                M.Collapsible.init(collapsibleElems);
            });
        </script>
    @endpush
@endsection
