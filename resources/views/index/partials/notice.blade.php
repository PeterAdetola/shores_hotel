@php
    $announcement = getPublishedAnnouncement();
@endphp
@if($announcement)
    <div class="mil-features mil-p-100-60" id="noticeSection" style="padding-top: 0; display: flex; align-items: center; min-height: 100vh; position: relative; background: linear-gradient(135deg, {{ $announcement->border_color }}08, {{ $announcement->border_color }}03);">
        <div class="container" style="border: {{ $announcement->border_color }} solid 10px; padding: 3em; text-align: center; background: white; border-radius: 20px; box-shadow: 0 10px 50px {{ $announcement->border_color }}30; position: relative;">

            <!-- Decorative corner elements -->
{{--            <div style="position: absolute; top: -4px; left: -4px; width: 60px; height: 60px; border-top: 8px solid {{ $announcement->border_color }}; border-left: 8px solid {{ $announcement->border_color }}; border-radius: 20px 0 0 0;"></div>--}}
{{--            <div style="position: absolute; top: -4px; right: -4px; width: 60px; height: 60px; border-top: 8px solid {{ $announcement->border_color }}; border-right: 8px solid {{ $announcement->border_color }}; border-radius: 0 20px 0 0;"></div>--}}
{{--            <div style="position: absolute; bottom: -4px; left: -4px; width: 60px; height: 60px; border-bottom: 8px solid {{ $announcement->border_color }}; border-left: 8px solid {{ $announcement->border_color }}; border-radius: 0 0 0 20px;"></div>--}}
{{--            <div style="position: absolute; bottom: -4px; right: -4px; width: 60px; height: 60px; border-bottom: 8px solid {{ $announcement->border_color }}; border-right: 8px solid {{ $announcement->border_color }}; border-radius: 0 0 20px 0;"></div>--}}

            <!-- UPDATED Dynamic Close Button -->
            <div class="mil-close-button notice-close-btn" id="noticeCloseBtn" style="position: absolute; top: 15px; right: 15px; z-index: 10; cursor: pointer; background-color: {{ $announcement->border_color }}15; border: 2px solid {{ $announcement->border_color }}; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $announcement->border_color }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>

            <div class="mil-text-center">
                @if($announcement->subtitle)
                    <div class="mil-suptitle mil-mb-20 mil-fade-up" style="color: {{ $announcement->border_color }}; font-weight: 600; font-size: 1.1em; text-transform: uppercase; letter-spacing: 2px;">
                        {{ $announcement->subtitle }}
                    </div>
                @endif
                <h2 class="mil-mb-40 mil-fade-up" style="color: {{ $announcement->border_color }}; text-shadow: 2px 2px 4px {{ $announcement->border_color }}20;">
                    {{ $announcement->title }}
                </h2>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; gap: 2em;">
                <div style="max-width: 700px; padding: 2em; background: linear-gradient(to bottom, {{ $announcement->border_color }}05, white); border-radius: 15px; border: 2px solid {{ $announcement->border_color }}20;" class="announcement-notice-content">
                    {!! $announcement->content !!}
                </div>

                <div class="mil-fade-up">
                    <a href="{{ $announcement->cta_link }}" class="mil-button announcement-notice-cta" style="background-color: {{ $announcement->border_color }}; border-color: {{ $announcement->border_color }}; padding: 15px 40px; font-size: 1.1em; box-shadow: 0 5px 20px {{ $announcement->border_color }}40;">
                        <span>{{ $announcement->cta_text }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Style the rich text content */
        .announcement-notice-content p {
            text-align: center;
            margin: 1em auto;
            line-height: 1.8;
        }
        .announcement-notice-content strong {
            font-weight: 700;
            font-size: 1.15em;
            color: {{ $announcement->border_color }};
            display: block;
            margin: 0.5em 0;
        }
        .announcement-notice-content em {
            font-style: italic;
            color: {{ $announcement->border_color }}cc;
        }
        .announcement-notice-content ul,
        .announcement-notice-content ol {
            text-align: left;
            max-width: 500px;
            margin: 1em auto;
        }
        .announcement-notice-content ul li::marker,
        .announcement-notice-content ol li::marker {
            color: {{ $announcement->border_color }};
            font-weight: bold;
        }
        .announcement-notice-content a {
            color: {{ $announcement->border_color }};
            text-decoration: underline;
            font-weight: 600;
        }

        /* CTA Button hover effect */
        .announcement-notice-cta:hover {
            background-color: {{ $announcement->border_color }}dd !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px {{ $announcement->border_color }}60 !important;
            transition: all 0.3s ease;
        }

        /* UPDATED Close button hover with dynamic color */
        .notice-close-btn:hover {
            background-color: {{ $announcement->border_color }} !important;
            transform: rotate(90deg) scale(1.15);
            box-shadow: 0 0 20px {{ $announcement->border_color }}90;
        }

        .notice-close-btn:hover svg {
            stroke: white !important;
        }

        /* Pulse animation for close button */
        @keyframes pulse-notice-close {
            0%, 100% {
                box-shadow: 0 0 0 0 {{ $announcement->border_color }}60;
            }
            50% {
                box-shadow: 0 0 0 10px {{ $announcement->border_color }}00;
            }
        }

        .notice-close-btn {
            animation: pulse-notice-close 2.5s infinite;
        }

        /* Pulse animation for notice section */
        @keyframes pulse-border {
            0%, 100% {
                box-shadow: 0 10px 50px {{ $announcement->border_color }}30;
            }
            50% {
                box-shadow: 0 10px 60px {{ $announcement->border_color }}50;
            }
        }

        #noticeSection .container {
            animation: pulse-border 3s ease-in-out infinite;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.getElementById('noticeCloseBtn');
            const noticeSection = document.getElementById('noticeSection');

            if (closeBtn && noticeSection) {
                closeBtn.addEventListener('click', function() {
                    // Fade out animation
                    noticeSection.style.transition = 'opacity 0.5s ease';
                    noticeSection.style.opacity = '0';

                    setTimeout(() => {
                        noticeSection.style.display = 'none';
                        // Store in localStorage so it doesn't show again this session
                        localStorage.setItem('noticeHidden_{{ $announcement->id }}', 'true');
                    }, 500);
                });
            }

            // Check if notice was already hidden for this specific announcement
            if (localStorage.getItem('noticeHidden_{{ $announcement->id }}') === 'true') {
                noticeSection.style.display = 'none';
            }
        });
    </script>
@endif
