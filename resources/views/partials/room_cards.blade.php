@foreach($rooms as $room)
    <div class="col-md-6 col-xl-4">
        <div class="mil-card mil-mb-40-adapt mil-fade-up">
            <div class="swiper-container mil-card-slider">
                <div class="swiper-wrapper">
                    @foreach($room->galleryImages as $img)
                        <div class="swiper-slide">
                            <div class="mil-card-cover">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="cover"
                                     data-swiper-parallax="-100" data-swiper-parallax-scale="1.1">
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Slider navigation arrows -->
            </div>

            <ul class="mil-parameters">
                <li>
                    <!-- Adult icon and count -->
                </li>
                <li>
                    <!-- Child icon and count -->
                </li>
            </ul>

            <div class="mil-descr">
                <h3 class="mil-mb-20">{{ $room->category->name ?? '—' }}</h3>
                <div class="mil-divider"></div>
                <div class="mil-card-bottom">
                    <div class="mil-price">
                        <span class="mil-symbol">₦</span>
                        <span class="mil-number" style="font-size: 1.2em">
                        {{ number_format($room->price_per_night, 2) }}
                    </span>/per night
                    </div>
                    <a href="{{ route('chosen_lodge') }}" class="mil-button mil-icon-button mil-accent-1">
                        <!-- Bookmark icon -->
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
