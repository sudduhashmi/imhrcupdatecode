/**
 * IMHRC Admin Panel - Sidebar Overlap Fix
 * Forces proper sidebar positioning and prevents content overlap
 */

(function() {
    'use strict';
    
    // Force sidebar positioning on page load
    function forceSidebarLayout() {
        const mainContainer = document.querySelector('.main-container');
        const leftSidebar = document.querySelector('.left-side-bar');
        const header = document.querySelector('.header');
        
        if (mainContainer) {
            mainContainer.style.marginLeft = '280px';
            mainContainer.style.width = 'calc(100% - 280px)';
            mainContainer.style.position = 'relative';
            mainContainer.style.zIndex = '1';
        }
        
        if (leftSidebar) {
            leftSidebar.style.position = 'fixed';
            leftSidebar.style.left = '0';
            leftSidebar.style.top = '0';
            leftSidebar.style.width = '280px';
            leftSidebar.style.zIndex = '1000';
        }
        
        if (header) {
            header.style.position = 'fixed';
            header.style.left = '280px';
            header.style.width = 'calc(100% - 280px)';
            header.style.zIndex = '999';
        }
    }
    
    // Mobile menu toggle
    function initMobileMenu() {
        const menuIcon = document.querySelector('.menu-icon');
        const mobileOverlay = document.querySelector('.mobile-menu-overlay');
        const leftSidebar = document.querySelector('.left-side-bar');
        
        if (menuIcon) {
            menuIcon.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    document.body.classList.toggle('sidebar-enable');
                    if (leftSidebar) {
                        leftSidebar.classList.toggle('open');
                    }
                }
            });
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                document.body.classList.remove('sidebar-enable');
                if (leftSidebar) {
                    leftSidebar.classList.remove('open');
                }
            });
        }
    }
    
    // Responsive handler
    function handleResize() {
        const mainContainer = document.querySelector('.main-container');
        const header = document.querySelector('.header');
        const leftSidebar = document.querySelector('.left-side-bar');
        
        if (window.innerWidth > 1024) {
            // Desktop: force sidebar layout
            if (mainContainer) {
                mainContainer.style.marginLeft = '280px';
                mainContainer.style.width = 'calc(100% - 280px)';
            }
            if (header) {
                header.style.left = '280px';
                header.style.width = 'calc(100% - 280px)';
            }
            if (leftSidebar) {
                leftSidebar.style.left = '0';
            }
            document.body.classList.remove('sidebar-enable');
        } else {
            // Mobile: hide sidebar by default
            if (mainContainer) {
                mainContainer.style.marginLeft = '0';
                mainContainer.style.width = '100%';
            }
            if (header) {
                header.style.left = '0';
                header.style.width = '100%';
            }
            if (leftSidebar && !document.body.classList.contains('sidebar-enable')) {
                leftSidebar.style.left = '-280px';
            }
        }
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            forceSidebarLayout();
            initMobileMenu();
            handleResize();
        });
    } else {
        forceSidebarLayout();
        initMobileMenu();
        handleResize();
    }
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleResize, 250);
    });
    
    // Force layout on page show (handles back/forward browser navigation)
    window.addEventListener('pageshow', forceSidebarLayout);
    
})();
