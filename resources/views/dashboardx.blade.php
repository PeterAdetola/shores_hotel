@extends('admin.admin_master')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/vendors/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/vendors/jquery.nestable/nestable.css') }}">

@endsection
@section('admin')

    <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6 description">
                            <h5 class="breadcrumbs-title mt-0 mb-0"><span>Blank Page</span></h5>
                            <ol class="breadcrumbs mb-0">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Pages</a>
                                </li>
                                <li class="breadcrumb-item active">Blank Page
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                    <div class="section">

                        <!-- All sections starts here -->

                        <div class="row">
                            <div class="col s12">
                                <div id="media-slider" class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col s12 m6 l10">
                                                <h4 class="card-title">Hero Images</h4>
                                            </div>
                                        </div>
                                        <div id="view-media-slider">
                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="slider">
                                                        <ul class="slides">
                                                            @php
                                                                $slides = getSlides();
                                                            @endphp
                                                            @if (count($slides) > 0)
                                                                @foreach($slides as $slide)
                                                                    <li>
                                                                        <img src="{{ url($slide->image) }}" alt="{{ $slide->image }}">
                                                                        <!-- random image -->
                                                                        <div class="caption left-align">
                                                                            {!! $slide->heading !!}
                                                                            <!-- <h5 class="light grey-text text-lighten-3">{{-- $slide->sub_heading --}}</h5> -->
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @else
                                                                <li>
                                                                    <img src="{{ ('backend/assets/images/gallery/flat-bg.jpg') }}" alt="">
                                                                    <!-- random image -->
                                                                    <div class="caption right-align">
                                                                        <h3 class="grey-text">No image in the gallery yet</h3>
                                                                        <h5 class="light grey-text text-lighten-1">Upload image to the gallery with the 'Edit hero' button below</h5>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- <a class="waves-effect btn-large right"><i class="material-icons left">keyboard_arrow_right</i>edit hero section</a> -->
                                            </div>

                                        </div>
                                        <div class="row right-align">
                                            <a  href="{{  route('view.slides')}}" class="mr-1"><i class="material-icons vertical-align-middle dark-small-ico-bg m-0">arrow_forward</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- About Summary -->
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <h4 class="card-title">About us</h4>
                                    </div>
                                </div>
                                <div class="divider"></div>


                                @php
                                    $aboutSum = getAboutSummary()->first();
                                    $noAboutHeading = empty($aboutSum->heading);
                                    $noAboutSum = empty($aboutSum->summary);
                                    $edit = !empty($aboutSum->heading) || !empty($aboutSum->summary);
                                    $stats = getStats();
                                @endphp

                                <div class="row mt-1" style="">
                                    @if(!$noAboutSum)
                                        <div id="aboutSum" class="col s12 m6 mt-1 ml-1 collection" style="padding: 2em;">
                                            <h2>{!! $aboutSum->heading !!}</h2>
                                            <p>{!! $aboutSum->summary !!}</p>
                                            <a href="#about_summary-modal" class="modal-trigger chip mt-2">Edit Summary</a>
                                        </div>
                                    @else
                                        <div id="aboutSum" class="col s12 m6 mt-1 ml-1 collection" style="padding: 3em;">
                                            <h2 class="card-title">About Us</h2>
                                            <p>Jupiter Corporate Services (JCS) simplifies business startups and management, offering registration and compliance services in Nigeria. Founded in 2012, JCS has registered thousands of businesses and supports over 1500 with regulatory compliance. With a team of 10, JCS focuses on integrity, expertise, and empowering businesses to thrive.</p>
                                            <a href="#about_summary-modal" class="modal-trigger chip mt-2">Add Summary</a>
                                        </div>
                                    @endif

                                    <div class="col s10 m5 mr-5" style="float: right;">
                                        <div class="row">
                                            @foreach ($stats as $stat)
                                                @if ($stat->order == 1)
                                                    <a href="#edit_stat-modal{{ $stat->id }}" class="modal-trigger">
                                                        <div class="col s6 card-width">
                                                            <div class="card border-radius-6">
                                                                <div class="card-content center-align">
                                                                    <h4 class="m-0"><b>{{ $stat->order == 1 ? date('Y') - $stat->value : 0 }}</b></h4>
                                                                    <p>{{ $stat->order == 1 ? $stat->key : 'Enter Value' }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @elseif ($stat->order == 2)
                                                    <a href="#edit_stat-modal{{ $stat->id }}" class="modal-trigger">
                                                        <div class="col s6 card-width">
                                                            <div class="card border-radius-6">
                                                                <div class="card-content center-align">
                                                                    <h4 class="m-0"><b>{{ $stat->value }}</b></h4>
                                                                    <p>{{ $stat->key }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @elseif ($stat->order == 3)
                                                    <a href="#edit_stat-modal{{ $stat->id }}" class="modal-trigger">
                                                        <div class="col s6 card-width">
                                                            <div class="card border-radius-6">
                                                                <div class="card-content center-align">
                                                                    <h4 class="m-0"><b>{{ $stat->value }}</b></h4>
                                                                    <p>{{ $stat->key }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @elseif ($stat->order == 4)
                                                    <a href="#edit_stat-modal{{ $stat->id }}" class="modal-trigger">
                                                        <div class="col s6 card-width">
                                                            <div class="card border-radius-6">
                                                                <div class="card-content center-align">
                                                                    <h4 class="m-0"><b>{{ $stat->value }}</b></h4>
                                                                    <p>{{ $stat->key }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif

                                                @include('admin.stat.modals.edit_stat-modal', ['stat' => $stat])
                                            @endforeach

                                            @if (count($stats) < 4)
                                                <div class="row right-align">
                                                    <a href="#add_stat-modal" class="modal-trigger red-text mr-5"><i class="material-icons vertical-align-middle dark-small-ico-bg m-0">add</i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @include('admin.about.modals.about_summary-modal')
                                @include('admin.stat.modals.add_stat-modal')
                            </div>
                        </div>

                        @php
                            $services = getServices();
                            $faqs = getFaqs();
                            $testimonials = getTestimonials();
                        @endphp
                        <div class="row">

                            <div class="col s12 m4 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <i class="material-icons grey-text mb-5">highlight</i>
                                        <h4 class="m-0"><b>{{ count($services) }}</b></h4>
                                        <p>{{ (count($services) > 1)? 'Services' : 'Service' }}</p>
                                        <p class="mt-3">
                                            <a href="{{ route('view.services') }}" class="red-text"><i class="material-icons vertical-align-middle small-ico-bg">arrow_forward</i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m4 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <i class="material-icons grey-text mb-5">forum</i>
                                        <h4 class="m-0"><b>{{ count($faqs) }}</b></h4>
                                        <p>{{ (count($services) > 1)? 'FAQs' : 'FAQ' }}</p>
                                        <p class="mt-3">
                                            <a href="{{ route('view.faq_services') }}" class="red-text"><i class="material-icons vertical-align-middle small-ico-bg">arrow_forward</i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m4 card-width">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <i class="material-icons grey-text mb-5">message</i>
                                        <h4 class="m-0"><b>{{ count($testimonials) }}</b></h4>
                                        <p>{{ (count($testimonials) > 1)? 'Testimonials' : 'Testimonial' }}</p>
                                        <p class="mt-3">
                                            <a href="{{ route('view.testimonials') }}" class="red-text"><i class="material-icons vertical-align-middle small-ico-bg">arrow_forward</i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- users view card details start -->
                        <div class="card mb-5">
                            <div class="card-content">
                                @php
                                    $contact = getContact();
                                    $thereIsContact = (count($contact) > 0);
                                @endphp
                                @if(count($contact) > 0)
                                    @foreach($contact as $contact)

                                        <div class="row indigo lighten-5 border-radius-4 mb-2 pt-1 pb-1">
                                            <div class="col s12 m4" style="padding: 2em">
                                                <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp;

          Phone

        &nbsp;</span>
                                                </h6>
                                                <div class="collection border" style="padding: 1em 2em; margin-top: -1em; z-index: 0;">
                                                    <h6 class="m-0"><span>{!! $contact->phone !!}</span></h6>
                                                </div>
                                            </div>
                                            <div class="col s12 m4" style="padding: 2em">
                                                <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp;

          Email

        &nbsp;</span>
                                                </h6>
                                                <div class="collection border" style="padding: 1em 2em; margin-top: -1em; z-index: 0;">
                                                    <h6 class=" m-0"><span>{!! $contact->email !!}</span></h6>
                                                </div>
                                            </div>


                                            <div class="col s12 m4" style="padding: 2em; visibility: {{($contact->address) ? 'visible' : 'hidden'}};">
                                                <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp;

             Address

           &nbsp;</span>
                                                </h6>
                                                <div class="collection border" style="padding: 1em 2em; margin-top: -1em; z-index: 0;">
                                                    <p class="m-0">{!! $contact->address !!}</p>
                                                </div>
                                            </div>


                                            <div class="right mr-2 {{($contact->address) ? '' : 'mt-5'}}">
                                                @if($thereIsContact)
                                                    <a href="#edit-contact-modal" class="modal-trigger red-text"><i class="material-icons vertical-align-middle dark-small-ico-bg">edit</i></a>
                                                @else
                                                    <a href="#edit-contact-modal" class="modal-trigger red-text"><i class="material-icons vertical-align-middle dark-small-ico-bg">add</i></a>
                                                @endif
                                            </div>
                                        </div>

                                        @include('admin.contact.modals.edit-contact-modal')
                                    @endforeach
                                @else
                                    <div class="row indigo lighten-5 border-radius-4 mb-2 pt-1 pb-1">
                                        <div class="col s12 m4" style="padding: 2em">
                                            <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp; Phone &nbsp;</span>
                                            </h6>
                                            <div class="collection border" style="padding: 2em 4em; margin-top: -1em; z-index: 0;">
                                                <h6 class="m-0"><span>234-XXX-XXX-XXXX</span></h6>
                                            </div>
                                        </div>
                                        <div class="col s12 m4" style="padding: 2em">
                                            <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp; Email &nbsp;</span>
                                            </h6>
                                            <div class="collection border" style="padding: 2em 4em; margin-top: -1em; z-index: 0;">
                                                <h6 class=" m-0"><span>company@email.com</span></h6>
                                            </div>
                                        </div>
                                        <div class="col s12 m4" style="padding: 2em">
                                            <h6 class="indigo-text ml-2">
            <span style="
            position: relative;
            background-color: #e8eaf6;
            z-index: 1;
            ">&nbsp; Address &nbsp;</span>
                                            </h6>
                                            <div class="collection border" style="padding: 1em 2em; margin-top: -1em; z-index: 0;">
                                                <p class="m-0">0, Name of the Street, At a particular rd. Name of state</p>
                                            </div>
                                        </div>
                                        <div class="right mr-2">
                                            <a href="#add-contact-modal" class="modal-trigger red-text"><i class="material-icons vertical-align-middle dark-small-ico-bg">arrow_forward</i></a>
                                        </div>
                                    </div>
                                    @include('admin.contact.modals.add-contact-modal')
                                @endif

                                @php
                                    $smedia = getSmedia();
                                @endphp
                                <div class="row">
                                    <div class="col s12">
                                        <h6 class="mb-2 mt-2"><i class="material-icons">link</i> Social Links</h6>
                                        <table class="striped">
                                            @if(count($smedia) > 0)
                                                <tbody>
                                                @foreach($smedia as $smedia)
                                                    <tr>
                                                        <td>{{ $smedia->name }}:</td>
                                                        <td><a href="#">{{ $smedia->link }}</a></td>
                                                        <td><a href="#edit-smedia-modal{{ $smedia->id }}" class="modal-trigger"><i class="material-icons vertical-align-middle dark-small-ico-bg">edit</i></a></td>
                                                        @include('admin.contact.modals.edit-smedia-modal')
                                                    </tr>
                                                @endforeach
                                                @else
                                                    <tr>
                                                        <td>Social Media type:</td>
                                                        <td><a href="#">social media url</a></td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                        <div class="right mt-2">
                                            <a href="#add-smedia-modal" class="modal-trigger red-text"><i class="material-icons vertical-align-middle dark-small-ico-bg">add</i></a>
                                        </div>
                                    </div>
                                </div>
                                @include('admin.contact.modals.add-smedia-modal')
                            </div>
                        </div>
                        <!-- Contents ends here -->
                    </div>
                    <!-- START RIGHT SIDEBAR NAV -->
                    <!-- END RIGHT SIDEBAR NAV -->
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
    <!-- END: Page Main-->


    <script src="{{ asset('backend/assets/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code lists',
            height: 250,
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code '
        });

        tinymce.init({
            selector: 'textarea#myeditorinstanceII', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code lists',
            height: 200,
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code '
        });
    </script>
@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/vendors/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scripts/form-file-uploads.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection
