@php
    $announcement = getPublishedAnnouncement();
@endphp
@if($announcement)
    <div class="mil-book-popup-frame" style="background-color: transparent">
        <div class="mil-book-popup" style="background-color: whitesmoke; border: solid {{ $announcement->border_color }} 3px; box-shadow: 0 0 30px {{ $announcement->border_color }}40;">
            <div class="mil-popup-head mil-mb-20" style="background: linear-gradient(135deg, {{ $announcement->border_color }}15, {{ $announcement->border_color }}05); padding: 1em; border-radius: 8px 8px 0 0; margin: -1px -1px 0 -1px;">
                <h3 class="mil-h3-lg" style="padding-left: 0.5em; color: {{ $announcement->border_color }}; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                    {{ $announcement->title }}
                </h3>
                <div class="mil-close-button announcement-close-btn" style="background-color: {{ $announcement->border_color }}15; border: 2px solid {{ $announcement->border_color }}; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $announcement->border_color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>
            <div style="margin: 0.5em; margin-bottom: 1em; padding: 2em; border: solid {{ $announcement->border_color }} 2px; border-radius: 20px; background: linear-gradient(to bottom, white, {{ $announcement->border_color }}08);">
                @if($announcement->subtitle)
                    <p class="mil-modal" style="color: {{ $announcement->border_color }}; font-weight: 600; font-size: 1.1em;">
                        {{ $announcement->subtitle }}
                    </p>
                @endif

                <div class="mil-modal announcement-content">
                    {!! $announcement->content !!}
                </div>
            </div>
            <a href="{{ $announcement->cta_link }}" class="mil-button announcement-cta-button" style="background-color: {{ $announcement->border_color }}; border-color: {{ $announcement->border_color }};">
                <span>{{ $announcement->cta_text }}</span>
            </a>
        </div>
    </div>

    <style>
        /* Style the rich text content from Quill */
        .announcement-content p {
            margin: 0.5em 0;
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

        /* Hover effect for CTA button */
        .announcement-cta-button:hover {
            background-color: {{ $announcement->border_color }}dd !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px {{ $announcement->border_color }}60;
            transition: all 0.3s ease;
        }

        /* Close button hover - UPDATED with dynamic color */
        .announcement-close-btn:hover {
            background-color: {{ $announcement->border_color }} !important;
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 0 15px {{ $announcement->border_color }}80;
            transition: all 0.3s ease;
        }

        .announcement-close-btn:hover svg {
            stroke: white !important;
        }

        /* Pulse animation for close button */
        @keyframes pulse-close {
            0%, 100% {
                box-shadow: 0 0 0 0 {{ $announcement->border_color }}60;
            }
            50% {
                box-shadow: 0 0 0 8px {{ $announcement->border_color }}00;
            }
        }

        .announcement-close-btn {
            animation: pulse-close 2s infinite;
        }
    </style>
@endif
