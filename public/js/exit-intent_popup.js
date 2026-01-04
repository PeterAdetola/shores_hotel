// Exit-Intent & Scroll-Based Popup Controller
(function() {
    // Configuration
    const config = {
        // Desktop exit-intent settings
        sensitivity: 20, // How many pixels from top before triggering
        delay: 1000, // Minimum time on page before popup can show (in ms)

        // Mobile scroll-based settings
        scrollPercentage: 50, // Show popup after user scrolls this % down the page

        // General settings
        cookieName: 'exitIntentShown',
        cookieExpiry: 1, // Days before popup can show again
        testMode: false // Set to true for testing (disables cookie check)
    };

    let popupShown = false;
    let timeOnPage = 0;
    let exitIntentEnabled = false;
    let scrollCheckEnabled = false;

    // Check if popup was already shown (using cookie)
    function wasPopupShown() {
        if (config.testMode) return false; // Skip check in test mode

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

    // Calculate scroll percentage
    function getScrollPercentage() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollableHeight = documentHeight - windowHeight;

        if (scrollableHeight <= 0) return 0;

        return (scrollTop / scrollableHeight) * 100;
    }

    // Show the popup
    function showPopup() {
        if (popupShown) return;

        const popup = document.querySelector('.mil-book-popup-frame');
        if (popup) {
            popup.classList.add('mil-active');
            popupShown = true;
            setPopupCookie();

            // Disable further detection
            exitIntentEnabled = false;
            scrollCheckEnabled = false;
        }
    }

    // Hide the popup
    function hidePopup() {
        const popup = document.querySelector('.mil-book-popup-frame');
        if (popup) {
            popup.classList.remove('mil-active');
        }
    }

    // Desktop: Exit intent detection
    function handleMouseLeave(e) {
        // Check if mouse is leaving from the top of the page
        if (e.clientY <= config.sensitivity && exitIntentEnabled && !popupShown) {
            showPopup();
        }
    }

    // Mobile: Scroll-based detection
    function handleScroll() {
        if (!scrollCheckEnabled || popupShown) return;

        const scrollPercent = getScrollPercentage();

        if (scrollPercent >= config.scrollPercentage) {
            showPopup();
        }
    }

    // Initialize
    function init() {
        // Don't show if already shown (unless in test mode)
        // if (wasPopupShown()) {
        //     console.log('Exit-intent popup: Already shown to this user');
        //     return;
        // }

        // Wait for minimum time on page before enabling popup
        setTimeout(() => {
            if (isMobile()) {
                scrollCheckEnabled = true;
                console.log('Mobile detected: Scroll-based popup enabled at ' + config.scrollPercentage + '%');
            } else {
                exitIntentEnabled = true;
                console.log('Desktop detected: Exit-intent popup enabled');
            }
        }, config.delay);

        // Desktop: Exit intent
        if (!isMobile()) {
            document.addEventListener('mouseleave', handleMouseLeave);
        }

        // Mobile: Scroll-based
        if (isMobile()) {
            window.addEventListener('scroll', handleScroll);
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

        // Close on ESC key
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
            scrollCheckEnabled = true;
            console.log('Popup reset - will show again');
        },
        getScrollPercentage: function() {
            return Math.round(getScrollPercentage()) + '%';
        }
    };
})();
