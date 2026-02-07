(function() {
    'use strict';

    // ─── NAV CONFIGURATION ──────────────────────────────────────
    const CONFIG = {
        navPhotos: [
            { caption: "Trail at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" },
            { caption: "Rincon Mountains, Tucson", credit: "TREY KAUFFMAN" },
            { caption: "San Francisco Peaks, Flagstaff", credit: "TREY KAUFFMAN" },
            { caption: "Sunset at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" },
            { caption: "Dharma Intiative, South Pacific", credit: "HUGO REYES" }
        ],
        slideshowDuration: 6000
    };

    // ─── NAV STATE ──────────────────────────────────────────────
    const state = {
        navOpen: false,
        slideshowIndex: 0,
        slideshowInterval: null,
        slideshowPaused: false
    };

    // ─── DOM ELEMENTS ───────────────────────────────────────────
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    const els = {
        hamburger: $('#hamburgerBtn'),
        navOverlay: $('#navOverlay'),
        navClose: $('#navClose'),
        navItems: $$('.nav-item'),
        navPhotos: $$('.nav-photo[data-photo]'),
        navPhotoDefault: $('#navPhotoDefault'),
        navCaption: $('#navCaption'),
        navCaptionTitle: $('#navCaptionTitle'),
        navCaptionCredit: $('.nav-caption__credit'),
        navPrev: $('#navPrev'),
        navNext: $('#navNext')
    };

    // ─── NAV PHOTO SLIDESHOW ────────────────────────────────────
    function showSlide(index) {
        if (els.navPhotoDefault) {
            els.navPhotoDefault.classList.remove('is-visible');
        }

        els.navPhotos.forEach(p => {
            p.classList.remove('is-visible');
        });

        const targetPhoto = document.querySelector(`.nav-photo[data-photo="${index}"]`);
        if (targetPhoto) {
            targetPhoto.style.animation = 'none';
            targetPhoto.offsetHeight;
            targetPhoto.style.animation = '';
            targetPhoto.classList.add('is-visible');
        }

        if (els.navCaption && els.navCaptionTitle) {
            els.navCaption.classList.add('is-visible');
            els.navCaptionTitle.textContent = CONFIG.navPhotos[index].caption;
            
            if (els.navCaptionCredit) {
                els.navCaptionCredit.textContent = CONFIG.navPhotos[index].credit;
            }
        }

        state.slideshowIndex = index;
    }

    function nextSlide() {
        const nextIndex = (state.slideshowIndex + 1) % CONFIG.navPhotos.length;
        showSlide(nextIndex);
        resetSlideshowTimer();
    }

    function prevSlide() {
        const prevIndex = state.slideshowIndex === 0 
            ? CONFIG.navPhotos.length - 1 
            : state.slideshowIndex - 1;
        showSlide(prevIndex);
        resetSlideshowTimer();
    }

    function startSlideshow() {
        if (state.slideshowInterval) return;
        
        showSlide(0);
        
        state.slideshowInterval = setInterval(() => {
            if (!state.slideshowPaused) {
                nextSlide();
            }
        }, CONFIG.slideshowDuration);
    }

    function stopSlideshow() {
        if (state.slideshowInterval) {
            clearInterval(state.slideshowInterval);
            state.slideshowInterval = null;
        }
        
        els.navPhotos.forEach(p => p.classList.remove('is-visible'));
        if (els.navPhotoDefault) {
            els.navPhotoDefault.classList.add('is-visible');
        }
        if (els.navCaption) {
            els.navCaption.classList.remove('is-visible');
        }
        
        state.slideshowIndex = 0;
        state.slideshowPaused = false;
    }

    function resetSlideshowTimer() {
        if (state.slideshowInterval) {
            clearInterval(state.slideshowInterval);
            state.slideshowInterval = setInterval(() => {
                if (!state.slideshowPaused) {
                    nextSlide();
                }
            }, CONFIG.slideshowDuration);
        }
    }

    // ─── HAMBURGER NAV ──────────────────────────────────────────
    function openNav() {
        state.navOpen = true;
        els.navOverlay.classList.add('is-open');
        startSlideshow();
    }

    function closeNav() {
        state.navOpen = false;
        els.navOverlay.classList.remove('is-open');
        stopSlideshow();
    }

    // ─── EVENT LISTENERS ────────────────────────────────────────
    function initNav() {
        if (!els.hamburger || !els.navOverlay) {
            console.warn('Nav elements not found - global.js will not initialize nav');
            return;
        }

        // Hamburger magnetic hover
        els.hamburger.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = (e.clientX - cx) / rect.width * 6;
            const dy = (e.clientY - cy) / rect.height * 6;
            this.style.transform = `translate(${dx}px, ${dy}px) scale(1.08)`;
        });

        els.hamburger.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0, 0) scale(1)';
        });

        els.hamburger.addEventListener('click', openNav);
        els.navClose.addEventListener('click', closeNav);

        if (els.navPrev) {
            els.navPrev.addEventListener('click', prevSlide);
        }
        if (els.navNext) {
            els.navNext.addEventListener('click', nextSlide);
        }
    }

    // ─── INITIALIZE ─────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNav);
    } else {
        initNav();
    }
})();