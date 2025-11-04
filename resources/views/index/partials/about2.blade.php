<?php $route = Route::current()->getName(); ?>
<div class="mil-about">
    <div class="container">
        <div class="row justify-content-between align-items-center flex-sm-row-reverse">
            <div class="col-xl-5 mil-mb-100">

                <div class="mil-text-frame">
                    @if($route == 'home')
                    <div class="mil-suptitle mil-mb-20 mil-fade-up">{{ $aboutContent2['suptitle'] }}</div>
                    @endif
                    <h2 class="mil-mb-40 mil-fade-up grey-text" @if($route == 'aboutUs') style="margin-top: 3em" @endif >{{ $aboutContent2['title'] }}</h2>

                    {{-- Paragraphs --}}
                    @foreach($aboutContent2['paragraphs'] as $paragraph)
                        <p class="mil-mb-20 mil-fade-up">{{ $paragraph }}</p>
                    @endforeach

                    {{-- Buttons --}}
                    <span class="mil-buttons-frame mil-fade-up">
                        @foreach($aboutContent2['buttons'] as $button)
                            @if($button['type'] === 'primary')
                                <a href="{{ $button['url'] }}" class="mil-button">
                                    @if($button['icon'] === 'email')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-mail">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    @endif
                                    <span>{{ $button['label'] }}</span>
                                </a>
                            @else
                                <a href="{{ $button['url'] }}" class="mil-link mil-open-book-popup">
                                    <span>{{ $button['label'] }}</span>
                                    @if($button['icon'] === 'arrow-right')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-arrow-right">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </span>
                </div>

            </div>
            <div class="col-xl-6 mil-mb-100">

                <div class="mil-illustration-2">
                    <img src="{{ asset($aboutContent2['images']['shape']) }}" class="mil-shape mil-fade-up" alt="shape">
                    <div class="mil-main-img mil-fade-up">
                        <img src="{{ asset($aboutContent2['images']['main']) }}" alt="img">
                    </div>

                    {{-- Figures --}}
                    @foreach($aboutContent2['images']['figures'] as $index => $figure)
                        <img src="{{ asset($figure) }}" alt="object" class="mil-figure mil-{{ $index+1 }} mil-fade-up">
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
