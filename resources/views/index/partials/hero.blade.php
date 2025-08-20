<div class="mil-banner">
    <div class="mil-banner-bg">
        <img src="{{ asset('img/images/8.png') }}" alt="background">
        <div class="mil-image-gradient"></div>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-10">
                <div class="mil-banner-content-frame">
                    <div class="mil-banner-content">
                        <div class="mil-suptitle mil-mb-40">{{ $heroContent['suptitle'] }}</div>
                        <h1 class="mil-mb-40 white-text shade">{{ $heroContent['title'] }}</h1>

                        @include('index.partials.search_panel')

                        <p><span class="mil-accent-2">*</span>{{ $heroContent['note'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
