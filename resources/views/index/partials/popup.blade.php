@php
    $announcement = getPublishedAnnouncement();
@endphp
@if($announcement)
    <div class="mil-book-popup-frame" style="background-color: transparent">
        <div class="mil-book-popup" style="background-color: whitesmoke; border: solid {{ $announcement->border_color }} 3px; box-shadow: 0 5px 30px {{ $announcement->border_color }}30;">

            <!-- Header with close button back to original position -->
            <div class="mil-popup-head mil-mb-20" style="display: flex; justify-content: space-between; align-items: center; padding: 0.5em;">
                <h3 class="mil-h3-lg" style="padding-left: 0.5em; color: {{ $announcement->border_color }};">
                    {{ $announcement->title }}
                </h3>
                <!-- Simple clean close button -->
                <div class="mil-close-button" style="cursor: pointer; padding: 5px; line-height: 1; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $announcement->border_color }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div style="margin: 0.5em; margin-bottom: 1em; padding: 2em; border: solid {{ $announcement->border_color }} 1px; border-radius: 12px; background-color: white;">
                @if($announcement->subtitle)
                    <p style="color: {{ $announcement->border_color }}; font-weight: 600; margin-bottom: 0.8em;">
                        {{ $announcement->subtitle }}
                    </p>
                @endif

                <div class="announcement-content">
                    {!! $announcement->content !!}
                </div>
            </div>

            <!-- CTA Button -->
            <a href="{{ $announcement->cta_link }}" class="mil-button announcement-popup-cta" style="background-color: {{ $announcement->border_color }}; border-color: {{ $announcement->border_color }};">
                <span>{{ $announcement->cta_text }}</span>
            </a>
        </div>
    </div>

    <style>
        /* Content styles */
        .announcement-content p {
            margin: 0.5em 0;
            line-height: 1.7;
        }
        .announcement-content strong {
            font-weight: 600;
            color: {{ $announcement->border_color }};
        }
        .announcement-content em {
            font-style: italic;
        }
        .announcement-content ul,
        .announcement-content ol {
            margin-left: 1.5em;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }
        .announcement-content ul li::marker,
        .announcement-content ol li::marker {
            color: {{ $announcement->border_color }};
        }
        .announcement-content a {
            color: {{ $announcement->border_color }};
            text-decoration: underline;
        }

        /* CTA Button */
        .announcement-popup-cta:hover {
            opacity: 0.85;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px {{ $announcement->border_color }}50;
            transition: all 0.3s ease;
        }

        /* Close button */
        .mil-close-button:hover svg {
            stroke: #333;
            transition: stroke 0.2s ease;
        }

        .mil-close-button:hover {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
    </style>
@endif
