
@extends('admin.admin_master')
@section('admin')
    @section('vendor_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/dropify/css/dropify.min.css') }}">
    @endsection
    @section('headScript')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endsection

    @php
        $pageTitle = 'Manage Gallery - ' . $room->category->name;
    @endphp


    <style>
        .image-card {
            position: relative;
            width: 100%;
            padding-top: 100%; /* square card */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            margin-bottom: 2em;
        }

        .image-card img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-card:hover img {
            transform: scale(1.05);
        }

        .image-card .overlay {
            position: absolute;
            bottom: -25%; /* hidden initially */
            left: 0;
            width: 100%;
            height: 25%;
            background: rgba(0, 0, 0, 0.6);

            display: flex;
            justify-content: space-between; /* pushes icons to opposite sides */
            align-items: center;
            padding: 0 12px; /* spacing from edges */

            transition: bottom 0.3s ease;
        }

        .image-card:hover .overlay {
            bottom: 0;
        }

        .image-card .edit-btn,
        .image-card .drag-handle {
            color: #fff;
            font-size: 1.8rem;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .image-card .drag-handle {
            cursor: grab;
        }
        .featured-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: yellowgreen;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            z-index: 2;
        }

        .featured-badge i {
            font-size: 18px;
            color: white;
        }


    </style>

    <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="content-wrapper-before gradient-45deg-black-grey"></div>
            <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="row">
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{ $pageTitle }}</span></h5>
                            <ol class="breadcrumbs mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('edit_room', $room->id) }}">Edit Accommodation </a>
                                </li>
                                <li class="breadcrumb-item active">{{ $pageTitle }}
                                </li>
                            </ol>
                        </div>

                        <!-- Something is removed here -->

                    </div>
                </div>
            </div><br>
            <div class="col s12">
                <div class="container">
                    <!-- users view start -->
                    <div id="gallery" class="row">

                        @foreach ($room->galleryImages as $img)
                            <div class="col s12 m4 l3 gallery-item" data-id="{{ $img->id }}">
                                <div class="image-card">

                                    <!-- Featured star -->
                                    @if($img->is_featured)
                                        <span class="featured-badge">
                <i class="material-icons">star</i>
            </span>
                                    @endif
                                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="Room Image">
                                    <div class="overlay">
                                        <a href="#edit_img-modal{{ $img->id }}" class="edit-btn modal-trigger"><span class="material-symbols-outlined grey-text">edit_square</span></a>
                                        <button type="button" class="drag-handle btn-flat" title="Drag to reorder">
                                            <span class="material-symbols-outlined grey-text" style="cursor: grab;">drag_indicator</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @include('admin.room.modals.management.edit_img-modal')

                        @endforeach


                    </div>
                    @include('admin.room.modals.management.add_img-modal')
                    <div style="bottom: 50px; right: 19px;" class=" fixed-action-btn direction-top">
                        <a href="#add_image-modal" class="modal-trigger btn-floating btn-large gradient-45deg-black-grey gradient-shadow"><i class="material-icons">add</i></a>
                    </div>
                    <!-- users view ends -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: Page Main-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('gallery');

            const sortable = Sortable.create(el, {
                animation: 150,
                handle: '.drag-handle', // only allow drag by this element
                draggable: '.gallery-item',
                onEnd: function () {
                    // get ordered ids
                    const order = Array.from(el.querySelectorAll('.gallery-item')).map(i => i.dataset.id);

                    fetch("{{ route('rooms.images.reorder', $room->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order: order })
                    })
                        .then(r => r.json())
                        .then(json => {
                            // success UI feedback (Materialize toast example)
                            if (typeof M !== 'undefined' && M.toast) {
                                M.toast({ html: 'Order saved' });
                            } else {
                                console.log('Order saved', json);
                            }
                        })
                        .catch(err => {
                            if (typeof M !== 'undefined' && M.toast) {
                                M.toast({ html: 'Error saving order' });
                            } else {
                                console.error(err);
                            }
                        });
                }
            });
        });

        document.querySelectorAll('.toggle-featured').forEach(cb => {
            cb.addEventListener('change', function () {
                fetch(this.dataset.url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        // Show toast first
                        if (typeof M !== 'undefined' && M.toast) {
                            M.toast({
                                html: data.featured ? 'Featured image updated!' : 'Image unfeatured',
                                displayLength: 1500 // 1.5s
                            });
                        }

                        // Reload after short delay (so toast can show)
                        setTimeout(() => {
                            window.location.reload();
                        }, 1600);
                    })
                    .catch(() => {
                        if (typeof M !== 'undefined' && M.toast) {
                            M.toast({ html: 'Error updating featured image' });
                        }
                    });
            });
        });



    </script>




@endsection

@section('vendor_scripts')
    <script src="{{ asset('admin/assets/vendors/dropify/js/dropify.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/scripts/form-file-uploads.js') }}"></script>
@endsection
