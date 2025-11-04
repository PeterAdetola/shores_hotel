{{-- resources/views/admin/partials/email-sidebar.blade.php --}}
<div class="sidebar-left sidebar-fixed">
    <div class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <div class="sidebar-details">
                    <h5 class="m-0 sidebar-title">
                        <i class="material-icons app-header-icon text-top">mail_outline</i> Mailbox
                    </h5>
                    <div class="row valign-wrapper mt-10 pt-2 animate fadeLeft">
                        <div class="col s3 media-image">
                            <img src="{{ asset('admin/assets/images/user/2.jpg') }}" alt=""
                                 class="circle z-depth-2 responsive-img">
                        </div>
                        <div class="col s9">
                            <p class="m-0 subtitle font-weight-700">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="m-0 text-muted">{{ $activeEmail ?? 'No account selected' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sidebar-list" class="sidebar-menu list-group position-relative animate fadeLeft">
                <div class="sidebar-list-padding app-sidebar sidenav" id="email-sidenav">
                    <ul class="email-list display-grid">
                        <li class="sidebar-title">Folders</li>
                        <li class="{{ ($folder ?? 'INBOX') == 'INBOX' ? 'active' : '' }}">
                            <a href="{{ route('admin.email.inbox') }}" class="text-sub">
                                <i class="material-icons mr-2">mail_outline</i> Inbox
                            </a>
                        </li>
                        <li class="{{ ($folder ?? '') == 'SENT' ? 'active' : '' }}">
                            <a href="{{ route('admin.email.inbox', ['folder' => 'SENT']) }}" class="text-sub">
                                <i class="material-icons mr-2">send</i> Sent
                            </a>
                        </li>
                        <li class="{{ ($folder ?? '') == 'DRAFT' ? 'active' : '' }}">
                            <a href="{{ route('admin.email.inbox', ['folder' => 'DRAFT']) }}" class="text-sub">
                                <i class="material-icons mr-2">description</i> Draft
                            </a>
                        </li>
                        <li class="{{ ($folder ?? '') == 'SPAM' ? 'active' : '' }}">
                            <a href="{{ route('admin.email.inbox', ['folder' => 'SPAM']) }}" class="text-sub">
                                <i class="material-icons mr-2">info_outline</i> Spam
                            </a>
                        </li>
                        <li class="{{ ($folder ?? '') == 'TRASH' ? 'active' : '' }}">
                            <a href="{{ route('admin.email.inbox', ['folder' => 'TRASH']) }}" class="text-sub">
                                <i class="material-icons mr-2">delete</i> Trash
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="#" data-target="email-sidenav" class="sidenav-trigger hide-on-large-only">
                <i class="material-icons">menu</i>
            </a>
        </div>
    </div>
</div>
