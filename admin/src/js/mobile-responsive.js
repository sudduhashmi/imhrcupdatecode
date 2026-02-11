/**
 * Mobile Responsive Controller
 * Handles mobile menu, touch events, and responsive behaviors
 */

(function() {
    'use strict';

    // Initialize mobile responsive features
    function initMobileResponsive() {
        setupMenuToggle();
        setupOverlayClick();
        setupTouchEvents();
        handleWindowResize();
        fixViewport();
    }

    // Setup hamburger menu toggle
    function setupMenuToggle() {
        const menuIcon = document.querySelector('.menu-icon');
        const sidebar = document.querySelector('.left-side-bar');
        const body = document.body;

        if (menuIcon) {
            menuIcon.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (body.classList.contains('sidebar-enable')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
    }

    // Open sidebar
    function openSidebar() {
        const body = document.body;
        const sidebar = document.querySelector('.left-side-bar');
        
        body.classList.add('sidebar-enable');
        if (sidebar) {
            sidebar.classList.add('open');
        }
    }

    // Close sidebar
    function closeSidebar() {
        const body = document.body;
        const sidebar = document.querySelector('.left-side-bar');
        
        body.classList.remove('sidebar-enable');
        if (sidebar) {
            sidebar.classList.remove('open');
        }
    }

    // Setup overlay click to close sidebar
    function setupOverlayClick() {
        const overlay = document.querySelector('.mobile-menu-overlay');
        
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeSidebar();
            });
        }
    }

    // Setup touch events for better mobile UX
    function setupTouchEvents() {
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            // Swipe left - close sidebar
            if (diff > swipeThreshold) {
                closeSidebar();
            }
            // Swipe right - open sidebar
            else if (diff < -swipeThreshold) {
                if (window.innerWidth < 1025) {
                    openSidebar();
                }
            }
        }
    }

    // Handle window resize
    function handleWindowResize() {
        let resizeTimer;

        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            
            resizeTimer = setTimeout(function() {
                const width = window.innerWidth;
                
                // Auto-close sidebar on larger screens
                if (width > 1024) {
                    closeSidebar();
                    document.body.classList.remove('sidebar-enable');
                }

                // Adjust header and container
                adjustLayout();
            }, 250);
        });
    }

    // Adjust layout based on screen size
    function adjustLayout() {
        const width = window.innerWidth;
        const header = document.querySelector('.header');
        const mainContainer = document.querySelector('.main-container');
        const sidebar = document.querySelector('.left-side-bar');

        if (width <= 1024) {
            // Mobile/Tablet layout
            if (header) header.style.left = '0';
            if (mainContainer) mainContainer.style.marginLeft = '0';
            if (sidebar) sidebar.style.position = 'fixed';
        } else {
            // Desktop layout
            if (header) header.style.left = '280px';
            if (mainContainer) mainContainer.style.marginLeft = '280px';
        }
    }

    // Fix viewport settings for better mobile experience
    function fixViewport() {
        // Ensure viewport meta tag is correct
        let viewport = document.querySelector('meta[name="viewport"]');
        
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover');
        } else {
            viewport = document.createElement('meta');
            viewport.name = 'viewport';
            viewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover';
            document.head.appendChild(viewport);
        }

        // Add safe area support for notched devices
        const html = document.documentElement;
        html.style.setProperty('--safe-area-inset-top', 'env(safe-area-inset-top)');
        html.style.setProperty('--safe-area-inset-right', 'env(safe-area-inset-right)');
        html.style.setProperty('--safe-area-inset-bottom', 'env(safe-area-inset-bottom)');
        html.style.setProperty('--safe-area-inset-left', 'env(safe-area-inset-left)');
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileResponsive);
    } else {
        initMobileResponsive();
    }

    // Expose functions globally for debugging
    window.mobileResponsive = {
        openSidebar: openSidebar,
        closeSidebar: closeSidebar,
        init: initMobileResponsive
    };
})();
