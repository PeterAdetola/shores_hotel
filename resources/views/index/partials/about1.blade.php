<div class="mil-about mil-p-100-0">
    <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape" style="width: 180%; bottom: -100%; left: -20%; opacity: .2" alt="shape">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-5 mil-mb-100">

                <div class="mil-text-frame">
                    <div class="mil-suptitle mil-mb-20 mil-fade-up">{{ $aboutContent1['suptitle'] }}</div>
                    <h2 class="mil-mb-60 mil-fade-up grey-text">{{ $aboutContent1['title'] }}</h2>
                    <ul class="mil-about-list">
                        @foreach($aboutContent1['items'] as $item)
                            <li class="mil-fade-up">
                                <div class="mil-item-head">
                                    <span>{{ $item['number'] }}</span>
                                    <h4>{{ $item['heading'] }}</h4>
                                </div>
                                <p>{{ $item['text'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
            <div class="col-xl-5 mil-mb-100">

                <div class="mil-illustration-1">
                    <img src="{{ asset('img/shapes/4.png') }}" class="mil-shape mil-fade-up" alt="shape">
                    <div class="mil-circle mil-1 mil-fade-up">
                        <img src="{{ asset('img/services/1.jpg') }}" alt="img">
                    </div>
                    <div class="mil-circle mil-2 mil-fade-up">
                        <img src="{{ asset('img/services/2.jpg') }}" alt="img">
                    </div>
                    <div class="mil-circle mil-3 mil-fade-up">
                        <img src="{{ asset('img/services/3.jpg') }}" alt="img">
                    </div>
                    <div class="mil-circle mil-4 mil-fade-up">
                        <img src="{{ asset('img/services/4.jpg') }}" alt="img">
                    </div>
                    <img src="{{ asset('img/shapes/1.png') }}" alt="object" class="mil-figure mil-1 mil-fade-up">
                    <img src="{{ asset('img/shapes/2.png') }}" alt="object" class="mil-figure mil-2 mil-fade-up">
                    <img src="{{ asset('img/shapes/3.png') }}" alt="object" class="mil-figure mil-3 mil-fade-up">
                </div>

            </div>
        </div>
    </div>
</div>
