<div class="mil-features mil-p-100-60" id="noticeSection" style="padding-top: 0; display: flex; align-items: center; min-height: 100vh; position: relative;">

    <div class="container" style="border: grey solid 15px; padding: 5em; text-align: center;">
        <div class="mil-close-button" id="noticeCloseBtn" style="position: absolute; top: 20px; right: 20px; z-index: 10;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <div class="mil-text-center">
            <div class="mil-suptitle mil-mb-20 mil-fade-up">Be the 1st to know</div>
            <h2 class="mil-mb-40 mil-fade-up">Announcement of something exciting</h2>
        </div>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 2em;">
            <div style="max-width: 700px;">
                <p class="mil-fade-up" style="text-align: center; margin: 0 auto;">
                    Shores Hotel offers a complete hospitality experience: a luxury hotel, residential apartments, and an exciting lounge for all your needs.
                </p>
            </div>
            <div class="mil-fade-up">
                <a href="{{ route('aboutUs') }}" class="mil-button">
                    <span>Discover the Excitement</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.getElementById('noticeCloseBtn');
        const noticeSection = document.getElementById('noticeSection');

        if (closeBtn && noticeSection) {
            closeBtn.addEventListener('click', function() {
                noticeSection.style.display = 'none';
            });
        }
    });
</script>
