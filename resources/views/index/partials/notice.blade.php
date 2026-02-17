@php
    $announcement = getPublishedAnnouncement();
@endphp
@if($announcement)
    <div class="mil-features mil-p-100-60" id="noticeSection" style="padding-top: 0; display: flex; align-items: center; min-height: 100vh; position: relative;">
        <div class="container" style="border: {{ $announcement->border_color }} solid 8px; padding: 5em; text-align: center; background: white; border-radius: 8px; box-shadow: 0 10px 50px {{ $announcement->border_color }}30; position: relative;">

            <!-- Simple Clean Close Button -->
            <div id="noticeCloseBtn" style="position: absolute; top: 15px; right: 15px; z-index: 10; cursor: pointer; line-height: 1; padding: 5px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="{{ $announcement->border_color }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>

            <div class="mil-text-center">
                @if($announcement->subtitle)
                    <div class="mil-suptitle mil-mb-20 mil-fade-up" style="color: {{ $announcement->border_color }}; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">
                        {{ $announcement->subtitle }}
                    </div>
                @endif
                <h2 class="mil-mb-40 mil-fade-up" style="color: {{ $announcement->border_color }};">
                    {{ $announcement->title }}
                </h2>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; gap: 2em;">
                <div style="max-width: 700px;" class="announcement-notice-content">
                    {!! $announcement->content !!}
                </div>

                <div class="mil-fade-up">
                    <a href="{{ $announcement->cta_link }}" class="mil-button announcement-notice-cta" style="background-color: {{ $announcement->border_color }}; border-color: {{ $announcement->border_color }};">
                        <span>{{ $announcement->cta_text }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .announcement-notice-content p {
            text-align: center;
            margin: 1em auto;
            line-height: 1.8;
        }
        .announcement-notice-content strong {
            font-weight: 700;
            color: {{ $announcement->border_color }};
        }
        .announcement-notice-content em {
            font-style: italic;
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
        }

        /* CTA Button */
        .announcement-notice-cta:hover {
            opacity: 0.85;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px {{ $announcement->border_color }}50;
            transition: all 0.3s ease;
        }

        /* Close button */
        #noticeCloseBtn:hover svg {
            stroke: #333;
            transition: stroke 0.2s ease;
        }

        #noticeCloseBtn:hover {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.getElementById('noticeCloseBtn');
            const noticeSection = document.getElementById('noticeSection');

            if (closeBtn && noticeSection) {
                closeBtn.addEventListener('click', function() {
                    noticeSection.style.transition = 'opacity 0.4s ease';
                    noticeSection.style.opacity = '0';
                    setTimeout(() => {
                        noticeSection.style.display = 'none';
                        localStorage.setItem('noticeHidden_{{ $announcement->id }}', 'true');
                    }, 400);
                });
            }

            // Don't show again if already closed
            if (localStorage.getItem('noticeHidden_{{ $announcement->id }}') === 'true') {
                noticeSection.style.display = 'none';
            }
        });
    </script>
@endif
