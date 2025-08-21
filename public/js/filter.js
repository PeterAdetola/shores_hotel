$(document).ready(function() {
    console.log('JavaScript loaded and ready');

    // First, let's check what enum values are actually in the database
    $.ajax({
        url: '/getlodged/debug-categories', // You'll need to add this route
        method: 'GET',
        success: function(response) {
            console.log('Database categories:', response.categories);
            console.log('Unique types in database:', response.unique_types);
        }
    });

    // DEBUGGING: Check if filter elements exist
    console.log('Filter elements found:', $('.mil-filter a').length);
    $('.mil-filter a').each(function(index) {
        console.log(`Filter ${index}:`, $(this).text(), $(this).attr('href'));
    });

    // Handle filter clicks - Using event delegation to ensure it works even after DOM changes
    $(document).on('click', '.mil-filter a', function(e) {
        e.preventDefault();
        console.log('Filter button clicked:', $(this).text());

        // DEBUGGING: Check if event is firing
        console.log('Filter click event fired successfully');

        // Remove active class from all filter links
        $('.mil-filter a').removeClass('mil-active');

        // Add active class to clicked link
        $(this).addClass('mil-active');

        // Get filter type - try multiple possible enum formats
        let filterText = $(this).text().toLowerCase().trim();
        let filterType = '';

        console.log('Raw filter text:', filterText);

        // More comprehensive filter mapping
        if (filterText === 'rooms' || filterText === 'room') {
            filterType = 'room'; // Adjust based on your enum values
        } else if (filterText === 'apartments' || filterText === 'apartment') {
            filterType = 'apartment'; // Adjust based on your enum values
        } else if (filterText === 'all' || filterText === 'show all') {
            filterType = ''; // Empty for all results
        }

        console.log('Filter text:', filterText);
        console.log('Filter type being sent:', filterType);

        // Make AJAX request
        $.ajax({
            url: '/getlodged/filter',
            method: 'GET',
            data: {
                type: filterType
            },
            beforeSend: function() {
                console.log('AJAX request starting with type:', filterType);
                $('.row.mil-mb-40').addClass('filtering');

                // Add loading indicator
                $('.row.mil-mb-40').html(`
                    <div class="col-12">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Filtering accommodations...</p>
                        </div>
                    </div>
                `);
            },
            success: function(response) {
                console.log('AJAX success, response:', response);
                console.log('Number of rooms returned:', response.length);

                // Log each room's category info
                response.forEach((room, index) => {
                    console.log(`Room ${index + 1} category:`, room.category);
                    console.log(`Room ${index + 1} full data:`, room);
                });

                updateRoomsDisplay(response);
            },
            error: function(xhr, status, error) {
                console.error('Filter request failed:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                console.error('Status Code:', xhr.status);

                // Try to parse error response for more details
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    console.error('Parsed error:', errorResponse);
                } catch (e) {
                    console.error('Could not parse error response');
                }

                // Show error message to user
                $('.row.mil-mb-40').html(`
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            <h4>Error Loading Accommodations</h4>
                            <p>There was an error filtering the accommodations. Please try again.</p>
                            <p><small>Error: ${error} (Status: ${xhr.status})</small></p>
                            <button class="btn btn-primary" onclick="location.reload()">Refresh Page</button>
                        </div>
                    </div>
                `);
            },
            complete: function() {
                console.log('AJAX request completed');
                $('.row.mil-mb-40').removeClass('filtering');
            }
        });
    });

    function updateRoomsDisplay(rooms) {
        console.log('Updating rooms display with:', rooms.length, 'rooms');
        const container = $('.row.mil-mb-40');

        // Clear existing content
        container.empty();

        if (rooms.length === 0) {
            container.html(`
                <div class="col-12">
                    <div class="text-center">
                        <h3>No accommodations found</h3>
                        <p>Please try a different filter or check back later.</p>
                        <p><small>Debug: Check browser console for more details</small></p>
                    </div>
                </div>
            `);
            return;
        }

        // Generate HTML for each room
        rooms.forEach(function(room) {
            console.log('Generating card for room:', room);
            const roomHtml = generateRoomCard(room);
            container.append(roomHtml);
        });

        // Force a reflow before reinitializing components
        container[0].offsetHeight;

        // Reinitialize any sliders or animations if needed
        reinitializeComponents();

        // Debug: Compare generated HTML with original
        console.log('Generated HTML sample:', container.find('.mil-card:first').prop('outerHTML'));
    }

    function generateRoomCard(room) {
        console.log('Generating room card for:', room);

        // Generate gallery images HTML
        let galleryHtml = '';
        if (room.gallery_images && room.gallery_images.length > 0) {
            room.gallery_images.forEach(function(img) {
                galleryHtml += `
                    <div class="swiper-slide">
                        <div class="mil-card-cover" style="position: relative; overflow: hidden; width: 100%; height: 100%; background: transparent;">
                            <img src="/storage/${img.image_path}" alt="cover"
                                 data-swiper-parallax="-100" data-swiper-parallax-scale="1.1"
                                 style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; min-width: 100%; min-height: 100%;">
                        </div>
                    </div>
                `;
            });
        } else {
            // Fallback images if no gallery images
            galleryHtml = `
                <div class="swiper-slide">
                    <div class="mil-card-cover" style="position: relative; overflow: hidden; width: 100%; height: 100%; background: transparent;">
                        <img src="/img/rooms/1.1.jpg" alt="cover"
                             data-swiper-parallax="-100" data-swiper-parallax-scale="1.1"
                             style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; min-width: 100%; min-height: 100%;">
                    </div>
                </div>
            `;
        }

        // Format price
        const price = parseFloat(room.price_per_night || 0);
        const formattedPrice = price.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Generate dynamic route URL with better error handling
        let categorySlug = 'uncategorized';
        let roomId = room.id || 0;

        if (room.category && room.category.slug) {
            categorySlug = room.category.slug;
        }

        // Option 1: Use route pattern from Blade template (recommended)
        let chosenLodgeUrl;
        if (window.routePatterns && window.routePatterns.chosen_lodge) {
            chosenLodgeUrl = window.routePatterns.chosen_lodge
                .replace('CATEGORY_SLUG', encodeURIComponent(categorySlug))
                .replace('ROOM_ID', roomId);
        } else {
            // Option 2: Fallback to manual URL construction
            chosenLodgeUrl = `/chosen_lodge/${encodeURIComponent(categorySlug)}/${roomId}`;
        }

        console.log('Generated URL:', chosenLodgeUrl);
        console.log('Category slug:', categorySlug, 'Room ID:', roomId);

        // Generate room card HTML - matching exact Blade structure
        return `
            <div class="col-md-6 col-xl-4">
                <div class="mil-card mil-mb-40-adapt">
                    <div class="swiper-container mil-card-slider" data-generated="true" style="overflow: hidden; width: 100%;">
                        <div class="swiper-wrapper">
                            ${galleryHtml}
                        </div>
                        <div class="mil-card-nav">
                            <div class="mil-slider-btn mil-card-prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-arrow-right">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                            <div class="mil-slider-btn mil-card-next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-arrow-right">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class="mil-card-pagination"></div>
                    </div>
                    <ul class="mil-parameters">
                        <li>
                            <div class="mil-icon">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M12.7432 5.75582C12.6516 7.02721 11.7084 8.00663 10.6799 8.00663C9.65144 8.00663 8.70673 7.02752 8.6167 5.75582C8.52291 4.43315 9.44106 3.505 10.6799 3.505C11.9188 3.505 12.837 4.45722 12.7432 5.75582Z"
                                            stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path
                                            d="M10.6793 10.0067C8.64232 10.0067 6.68345 11.0185 6.19272 12.9889C6.12771 13.2496 6.29118 13.5075 6.55905 13.5075H14.7999C15.0678 13.5075 15.2303 13.2496 15.1662 12.9889C14.6755 10.9869 12.7166 10.0067 10.6793 10.0067Z"
                                            stroke="black" stroke-width="1.00189" stroke-miterlimit="10"/>
                                        <path
                                            d="M6.42937 6.31713C6.3562 7.33276 5.59385 8.13264 4.77209 8.13264C3.95033 8.13264 3.18672 7.33308 3.1148 6.31713C3.04007 5.26053 3.7821 4.50537 4.77209 4.50537C5.76208 4.50537 6.50411 5.27992 6.42937 6.31713Z"
                                            stroke="black" stroke-width="1.00189" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path
                                            d="M6.61604 10.0688C6.05177 9.81023 5.4303 9.71082 4.77162 9.71082C3.14604 9.71082 1.57985 10.5189 1.18752 12.0929C1.13594 12.3011 1.26661 12.5071 1.48043 12.5071H4.99045"
                                            stroke="black" stroke-width="1.00189" stroke-miterlimit="10"
                                            stroke-linecap="round"/>
                                    </g>
                                    <defs>
                                        <clipPath>
                                            <rect width="16.0035" height="16.0035" fill="white"
                                                  transform="translate(0.176514 0.504028)"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div>Adults: ${room.adult_max || '—'}</div>
                        </li>
                        <li>
                            <div class="mil-icon">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                     width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000"
                                     preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                       fill="#000000" stroke="none">
                                        <path d="M1030 4891 c0 -5 5 -44 11 -87 22 -159 101 -316 215 -428 63 -61 174
-135 237 -157 20 -7 37 -16 37 -19 0 -4 -19 -17 -42 -30 -136 -75 -298 -201
-434 -337 -206 -207 -370 -459 -476 -735 -32 -86 -45 -108 -60 -108 -34 0
-151 -51 -220 -97 -295 -194 -384 -592 -201 -898 79 -130 229 -246 378 -291
33 -10 61 -19 63 -20 2 -1 18 -43 37 -92 46 -124 146 -318 223 -431 89 -133
163 -220 288 -342 316 -307 694 -496 1149 -576 143 -24 500 -25 646 0 311 52
592 161 839 324 145 96 237 172 361 299 218 225 370 468 482 775 16 42 21 46
66 58 112 29 249 114 326 203 56 66 101 145 131 233 24 70 28 96 28 200 1 99
-3 133 -22 195 -47 152 -142 279 -270 364 -69 45 -186 96 -220 96 -15 0 -27
21 -56 99 -186 489 -553 900 -1020 1140 -290 149 -627 231 -950 231 -220 0
-309 27 -416 127 -71 65 -116 149 -132 246 l-11 67 -148 0 c-171 0 -158 10
-138 -109 14 -89 44 -174 87 -251 20 -36 38 -68 40 -72 5 -12 -96 -10 -162 3
-89 16 -164 56 -232 124 -70 69 -108 142 -124 238 l-12 67 -149 0 c-85 0 -149
-4 -149 -9z m1672 -751 c355 -109 465 -553 203 -815 -153 -153 -374 -187 -575
-87 -137 68 -259 256 -260 400 l0 32 -150 0 -150 0 0 -40 c0 -118 60 -295 139
-407 101 -143 239 -246 411 -305 90 -30 102 -32 240 -32 138 0 150 2 240 32
121 42 214 97 301 179 160 151 249 356 249 573 0 90 -21 207 -51 283 -11 27
-18 52 -15 54 9 10 243 -119 336 -185 123 -87 336 -300 422 -422 73 -104 181
-304 223 -415 14 -38 39 -115 54 -170 l28 -100 97 -7 c148 -11 245 -61 311
-161 102 -156 79 -346 -56 -471 -72 -67 -135 -92 -259 -103 l-93 -8 -37 -125
c-43 -147 -62 -195 -129 -329 -302 -602 -942 -992 -1624 -991 -679 1 -1317
392 -1618 991 -67 134 -86 182 -129 329 l-37 125 -93 8 c-124 11 -187 36 -259
103 -85 78 -116 151 -116 269 1 77 5 98 28 145 41 86 89 135 170 175 62 31 86
37 171 43 l99 7 28 100 c48 172 102 301 184 440 324 551 913 898 1531 904 96
1 136 -3 186 -19z"/>
                                        <path d="M1640 2769 c-109 -12 -196 -53 -282 -135 -86 -82 -148 -211 -148
-310 l0 -45 147 3 147 3 9 44 c12 60 78 126 138 138 58 12 124 -1 166 -33 38
-29 73 -91 73 -129 l0 -25 150 0 150 0 0 38 c0 89 -56 217 -128 295 -114 121
-257 174 -422 156z"/>
                                        <path d="M3360 2769 c-116 -12 -214 -63 -302 -156 -74 -80 -128 -204 -128
-295 l0 -38 150 0 150 0 0 25 c0 38 35 100 73 129 42 32 108 45 166 33 60 -12
126 -78 138 -138 l9 -44 147 -3 147 -3 0 44 c0 167 -148 361 -323 423 -59 21
-151 30 -227 23z"/>
                                        <path d="M1973 1596 c-67 -39 -125 -75 -129 -79 -13 -12 106 -156 179 -217 86
-72 222 -142 332 -171 112 -30 298 -30 409 0 178 47 340 153 452 295 35 44 63
84 61 89 -1 4 -58 41 -126 82 l-124 74 -20 -27 c-216 -288 -599 -318 -838 -66
-25 27 -52 59 -60 71 -14 21 -15 21 -136 -51z"/>
                                    </g>
                                </svg>
                            </div>
                            <div>Children: ${room.children_max || '—'}</div>
                        </li>
                    </ul>
                    <div class="mil-descr">
                        <h3 class="mil-mb-20">${room.category ? room.category.name : '—'}</h3>
                        <div class="mil-divider"></div>
                        <div class="mil-card-bottom">
                            <div class="mil-price">
                                <span class="mil-symbol">₦</span>
                                <span class="mil-number" style="font-size: 1.2em">${formattedPrice}</span>/per night
                            </div>
                            <a href="${chosenLodgeUrl}" class="mil-button mil-icon-button mil-accent-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-bookmark">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function reinitializeComponents() {
        // Wait a bit for DOM to settle and force proper sizing
        setTimeout(function() {
            // Force all containers to have proper dimensions before Swiper init
            $('.mil-card-slider').each(function() {
                const container = $(this);
                const parentWidth = container.parent().width();
                container.width(parentWidth);
            });

            // Reinitialize Swiper sliders
            if (typeof Swiper !== 'undefined') {
                // Destroy existing swipers first
                $('.mil-card-slider').each(function() {
                    if (this.swiper) {
                        this.swiper.destroy(true, true);
                    }
                });

                // Force reflow to ensure proper dimensions
                $('.mil-card-slider').each(function() {
                    this.offsetHeight; // Trigger reflow
                    this.offsetWidth; // Trigger width reflow
                });

                // Initialize new swipers with updated settings
                $('.mil-card-slider').each(function() {
                    const swiperContainer = this;
                    const swiper = new Swiper(swiperContainer, {
                        slidesPerView: 1,
                        spaceBetween: 0,
                        loop: true,
                        parallax: true,
                        observer: true,
                        observeParents: true,
                        observeSlideChildren: true,
                        watchSlidesProgress: true,
                        autoHeight: false,
                        centeredSlides: true,
                        navigation: {
                            nextEl: $(swiperContainer).find('.mil-card-next')[0],
                            prevEl: $(swiperContainer).find('.mil-card-prev')[0],
                        },
                        pagination: {
                            el: $(swiperContainer).find('.mil-card-pagination')[0],
                            clickable: true,
                        },
                        on: {
                            init: function() {
                                setTimeout(() => {
                                    this.update();
                                    this.updateSize();
                                }, 50);
                            },
                            slideChangeTransitionEnd: function() {
                                this.update();
                            }
                        }
                    });
                });
            }

            // Force image sizing after Swiper initialization
            setTimeout(function() {
                $('.mil-card-cover img').each(function() {
                    const img = $(this);
                    const container = img.closest('.mil-card-cover');

                    img.css({
                        'width': '100%',
                        'height': '100%',
                        'object-fit': 'cover',
                        'object-position': 'center',
                        'min-width': '100%',
                        'min-height': '100%'
                    });

                    const swiper = img.closest('.mil-card-slider')[0].swiper;
                    if (swiper) {
                        swiper.update();
                        swiper.updateSize();
                    }
                });
            }, 100);

            // Reinitialize any fade-up animations
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }

        }, 150);
    }
});
