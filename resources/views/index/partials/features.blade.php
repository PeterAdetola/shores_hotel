<style>
    /* Target only the features section */
    .mil-features .mil-bg-icon {
        background-color: #e6c88c !important; /* Light golden brown */
    }
    .mil-features .mil-icon .material-symbols-outlined {
        color: #b8860b !important; /* Deep golden brown */
    }
</style>

<div class="mil-features mil-p-100-60">
    <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape mil-fade-up"
         style="width: 85%; top: -20%; left: -30%; transform: rotate(35deg)" alt="shape">

    <div class="container">
        <div class="mil-text-center">
            <div class="mil-suptitle mil-mb-20 mil-fade-up">Features</div>
            <h2 class="mil-mb-100 mil-fade-up">Features that will make <br>your vacation unforgettable</h2>
        </div>

        <div class="row">
            @foreach($featuresContent['items'] as $item)
                <div class="col-md-6 col-xl-4">
                    <div class="mil-iconbox mil-mb-40-adapt mil-fade-up">
                        <div class="mil-bg-icon"></div>
                        <div class="mil-icon">
                            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                        </div>
                        <h3 class="mil-mb-20 grey-text">{{ $item['title'] }}</h3>
                        <p>{{ $item['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
