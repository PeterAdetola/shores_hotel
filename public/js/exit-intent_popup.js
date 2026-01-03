// Exit-Intent Popup Controller
(function() {
    // Configuration
    const config = {
        sensitivity: 20, // How many pixels from top before triggering
        delay: 1000, // Minimum time on page before popup can show (in ms)
        cookieName: 'exitIntentShown',
        cookieExpiry: 1, // Days before popup can show again
        showOnMobile: false // Set to true if you want exit-intent on mobile too
    };

    let popupShown = false;
    let timeOnPage = 0;
    let exitIntentEnabled = false;

    // Check if popup was already shown (using cookie or sessionStorage)
    function wasPopupShown() {
        // Check cookie
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === config.cookieName) {
                return true;
            }
        }
        return false;
    }

    // Set cookie to remember popup was shown
    function setPopupCookie() {
        const date = new Date();
        date.setTime(date.getTime() + (config.cookieExpiry * 24 * 60 * 60 * 1000));
        document.cookie = `${config.cookieName}=true; expires=${date.toUTCString()}; path=/`;
    }

    // Check if device is mobile
    function isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Show the popup
    function showPopup() {
        if (popupShown) return;

        const popup = document.querySelector('.mil-book-popup-frame');
        if (popup) {
            popup.classList.add('mil-active');
            popupShown = true;
            setPopupCookie();

            // Disable further exit intent detection
            exitIntentEnabled = false;
        }
    }

    // Hide the popup
    function hidePopup() {
        const popup = document.querySelector('.mil-book-popup-frame');
        if (popup) {
            popup.classList.remove('mil-active');
        }
    }

    // Exit intent detection
    function handleMouseLeave(e) {
        // Check if mouse is leaving from the top of the page
        if (e.clientY <= config.sensitivity && exitIntentEnabled && !popupShown) {
            showPopup();
        }
    }

    // Mobile exit intent (scroll to top rapidly)
    let lastScrollTop = 0;
    function handleMobileExit() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // If user scrolls up rapidly when near top
        if (scrollTop < 100 && lastScrollTop - scrollTop > 50 && exitIntentEnabled && !popupShown) {
            showPopup();
        }

        lastScrollTop = scrollTop;
    }

    // Initialize
    function init() {
        // Don't show if already shown
        // if (wasPopupShown()) {
        //     return;
        // }

        // Wait for minimum time on page before enabling exit intent
        setTimeout(() => {
            exitIntentEnabled = true;
        }, config.delay);

        // Desktop exit intent
        if (!isMobile() || config.showOnMobile) {
            document.addEventListener('mouseleave', handleMouseLeave);
        }

        // Mobile exit intent (optional)
        if (isMobile() && config.showOnMobile) {
            window.addEventListener('scroll', handleMobileExit);
        }

        // Close button handler
        const closeButton = document.querySelector('.mil-close-button');
        if (closeButton) {
            closeButton.addEventListener('click', hidePopup);
        }

        // Close on background click
        const popupFrame = document.querySelector('.mil-book-popup-frame');
        if (popupFrame) {
            popupFrame.addEventListener('click', function(e) {
                if (e.target === popupFrame) {
                    hidePopup();
                }
            });
        }

        // Optional: Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePopup();
            }
        });
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose functions globally if needed
    window.exitIntentPopup = {
        show: showPopup,
        hide: hidePopup,
        reset: function() {
            // Remove cookie to allow popup to show again
            document.cookie = `${config.cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
            popupShown = false;
            exitIntentEnabled = true;
        }
    };
})();
