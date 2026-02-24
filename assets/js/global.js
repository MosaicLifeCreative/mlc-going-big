// ════════════════════════════════════════════════════════════
// GLOBAL SCRIPTS - ALL PAGES
// Version: 1.5.0 | Last Updated: Feb 23, 2026
// ════════════════════════════════════════════════════════════

(function() {
    'use strict';

    // ─── NAV CONFIGURATION ──────────────────────────────────────
    // Photos come from MLC Toolkit plugin via wp_localize_script (mlcPhotos global)
    // Fallback to hardcoded defaults if plugin not active
    const fallbackPhotos = [
        { caption: "Trail at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" },
        { caption: "Rincon Mountains, Tucson", credit: "TREY KAUFFMAN" },
        { caption: "San Francisco Peaks, Flagstaff", credit: "TREY KAUFFMAN" },
        { caption: "Sunset at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" },
        { caption: "Dharma Initiative, South Pacific", credit: "HUGO REYES" }
    ];

    const CONFIG = {
        navPhotos: (typeof mlcPhotos !== 'undefined' && mlcPhotos.photos && mlcPhotos.photos.length > 0)
            ? mlcPhotos.photos
            : fallbackPhotos,
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
        // Wrap index to valid range
        var photoCount = CONFIG.navPhotos.length;
        if (photoCount === 0) return;
        index = ((index % photoCount) + photoCount) % photoCount;

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

        if (els.navCaption && els.navCaptionTitle && CONFIG.navPhotos[index]) {
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

    // ─── INITIALIZE NAV ─────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNav);
    } else {
        initNav();
    }

    // ─── HAMBURGER COLOR SWAP (Dark/Light Backgrounds) ────────
    // Watches dark sections and toggles hamburger to white lines
    function initHamburgerContrast() {
        const hamburger = document.getElementById('hamburgerBtn');
        if (!hamburger) return;

        const darkSections = document.querySelectorAll('.sp-hero, .sp-section--dark, .sp-cta, .sp-corrupted-inbox, .sp-terminal');
        if (!darkSections.length) return;

        // Core check — is the hamburger over a dark section right now?
        function updateContrast() {
            const hY = hamburger.getBoundingClientRect().top + hamburger.getBoundingClientRect().height / 2;
            let onDark = false;
            darkSections.forEach(s => {
                const rect = s.getBoundingClientRect();
                if (rect.top <= hY && rect.bottom >= hY) onDark = true;
            });
            hamburger.classList.toggle('hamburger--light', onDark);
        }

        // Set initial state
        updateContrast();

        // IntersectionObserver handles most transitions efficiently
        const observer = new IntersectionObserver(() => updateContrast(), {
            rootMargin: '-0px 0px -95% 0px',
            threshold: 0
        });
        darkSections.forEach(s => observer.observe(s));

        // Scroll listener catches fast scrolling the observer misses
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    updateContrast();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHamburgerContrast);
    } else {
        initHamburgerContrast();
    }

    // ─── SCROLL REVEAL ANIMATIONS ──────────────────────────────
    // Elements with .reveal class fade/slide in when entering viewport
    function initReveal() {
        const revealEls = document.querySelectorAll('.reveal');
        if (!revealEls.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target); // Only animate once
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(el => observer.observe(el));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReveal);
    } else {
        initReveal();
    }

    // ─── COUNTDOWN TIMER (NON-LANDING PAGES) ───────────────────
    // Landing page has its own countdown in landing.js — skip it here
    function initCountdown() {
        if (document.body.classList.contains('mlc-landing-page')) return;

        const countdown = document.getElementById('countdown');
        const huntEnterBtn = document.getElementById('huntEnterBtn');
        const huntModal = document.getElementById('huntModal');
        const huntCard = document.getElementById('huntCard');
        const huntClose = document.getElementById('huntClose');
        const huntInput = document.getElementById('huntInput');
        const huntSubmit = document.getElementById('huntSubmit');
        const huntError = document.getElementById('huntError');

        if (!countdown) return;

        const TARGET_HOUR = 15;
        const TARGET_MIN = 16;
        const TARGET_SEC = 23;
        const WINDOW_DURATION = 42;

        let huntModalOpen = false;

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function updateCountdown() {
            const now = new Date();
            const target = new Date();
            target.setHours(TARGET_HOUR, TARGET_MIN, TARGET_SEC, 0);

            let diff = (target - now) / 1000;

            // Currently in the 42-second window
            if (diff <= 0 && diff > -WINDOW_DURATION) {
                countdown.textContent = '00:00:00';
                countdown.classList.add('is-active');
                if (huntEnterBtn) huntEnterBtn.classList.add('is-active');
                return;
            }

            // Window has passed, reset to next day
            if (diff <= -WINDOW_DURATION) {
                target.setDate(target.getDate() + 1);
                diff = (target - now) / 1000;
                if (huntEnterBtn) huntEnterBtn.classList.remove('is-active');

                if (!huntModalOpen && huntInput) {
                    huntInput.value = '';
                    if (huntError) huntError.classList.remove('is-visible');
                }
            }

            const hours = Math.floor(diff / 3600);
            const mins = Math.floor((diff % 3600) / 60);
            const secs = Math.floor(diff % 60);

            countdown.textContent = `${pad(hours)}:${pad(mins)}:${pad(secs)}`;
            countdown.classList.remove('is-active');
        }

        // Hunt modal
        function openHuntModal() {
            huntModalOpen = true;
            if (huntModal) huntModal.classList.add('is-open');
            if (huntInput) huntInput.focus();
        }

        function closeHuntModal() {
            huntModalOpen = false;
            if (huntModal) huntModal.classList.remove('is-open');
            if (huntInput) huntInput.value = '';
            if (huntError) huntError.classList.remove('is-visible');
        }

        function validateHunt() {
            if (!huntInput || typeof mlcHunt === 'undefined') return;
            const input = huntInput.value;

            fetch(mlcHunt.ajaxurl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'mlc_validate_hunt',
                    nonce: mlcHunt.nonce,
                    sequence: input
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data.correct) {
                    setTimeout(() => {
                        window.open(data.data.redirect, '_blank');
                        closeHuntModal();
                    }, 800);
                } else {
                    if (data.data && data.data.message) {
                        huntError.textContent = data.data.message;
                    } else {
                        huntError.textContent = 'Not quite. Try again.';
                    }
                    huntError.classList.add('is-visible');
                    if (huntCard) {
                        huntCard.classList.add('shake');
                        setTimeout(() => huntCard.classList.remove('shake'), 500);
                    }
                    setTimeout(() => huntError.classList.remove('is-visible'), 1500);
                }
            })
            .catch(err => {
                console.error('Hunt validation error:', err);
                if (huntError) {
                    huntError.textContent = 'Connection error. Try again.';
                    huntError.classList.add('is-visible');
                }
            });
        }

        // Event listeners
        if (huntEnterBtn) huntEnterBtn.addEventListener('click', openHuntModal);
        if (huntClose) huntClose.addEventListener('click', closeHuntModal);
        if (huntSubmit) huntSubmit.addEventListener('click', validateHunt);
        if (huntInput) {
            huntInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            huntInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') validateHunt();
            });
        }

        // Start
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdown);
    } else {
        initCountdown();
    }

    // ─── WHEATLEY PAGE SECTIONS (Parallax Void) ────────────────
    // Triggers when the void section scrolls into view, fetches a
    // "bad salesman" Wheatley message, typewriter-displays it.
    // One message per page per session (cached in sessionStorage).

    function initWheatleySection() {
        // Skip on landing page (homepage Wheatley is separate)
        if (document.body.classList.contains('mlc-landing-page')) return;

        const section = document.querySelector('[data-wheatley-page]');
        if (!section) return;

        const pageSlug = section.dataset.wheatleyPage;
        const pageContext = section.dataset.wheatleyContext || '';
        const messageEl = document.getElementById('wheatleyPageMessage');
        if (!messageEl) return;

        // Get share context from sessionStorage (set by share link pipeline)
        const userName = sessionStorage.getItem('mlc_share_name') || null;

        // Visitor info from localStorage
        const visitCount = parseInt(localStorage.getItem('mlc_visit_count') || '1');
        const isReturning = visitCount > 1;

        let triggered = false;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !triggered) {
                    triggered = true;
                    observer.unobserve(section);
                    section.classList.add('is-triggered');
                    fetchWheatleyPageMessage(
                        pageSlug, pageContext, userName,
                        isReturning, visitCount, messageEl
                    );
                }
            });
        }, { threshold: 0.3 });

        observer.observe(section);
    }

    async function fetchWheatleyPageMessage(slug, context, userName, isReturning, visitCount, el) {
        try {
            const response = await fetch('/wp-json/mlc/v1/wheatley-page', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    page_slug: slug,
                    page_context: context,
                    user_name: userName,
                    visitor: { isReturning: isReturning, visitCount: visitCount },
                    current_time: new Date().toLocaleTimeString('en-US', {
                        hour: 'numeric', minute: '2-digit', hour12: true
                    }),
                    device: window.innerWidth <= 768 ? 'mobile'
                          : window.innerWidth <= 1024 ? 'tablet' : 'desktop'
                })
            });

            const data = await response.json();

            if (data.success && data.message) {
                await wheatleyTypewriter(el, data.message);
            } else {
                await wheatleyTypewriter(el,
                    "Right, so... the API budget ran out. I had a whole bit prepared about this. It was brilliant. You'll just have to imagine it."
                );
            }
        } catch (err) {
            console.error('Wheatley page section error:', err);
            await wheatleyTypewriter(el,
                "Right, so... bit of a connection issue on my end. The person who built me probably broke something. Again."
            );
        }
    }

    async function wheatleyTypewriter(el, text) {
        el.innerHTML = '';
        const textNode = document.createTextNode('');
        el.appendChild(textNode);

        for (let i = 0; i < text.length; i++) {
            textNode.nodeValue += text[i];
            await new Promise(r => setTimeout(r, 28));
        }

        // Add blinking cursor after typing
        const cursor = document.createElement('span');
        cursor.className = 'wheatley-void__cursor';
        el.appendChild(cursor);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWheatleySection);
    } else {
        initWheatleySection();
    }

    // ─── CHATLING FADE-IN OBSERVER (ALL PAGES) ─────────────────
    // Watch for Chatling elements being added to DOM and fade them in
    const chatlingObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.id && node.id.includes('chtl')) {
                    console.log('Chatling element detected:', node.id);
                    // Apply fade-in immediately
                    node.style.opacity = '0';
                    node.style.transition = 'opacity 0.5s ease-in';
                    setTimeout(() => {
                        node.style.opacity = '1';
                    }, 50);
                }
            });
        });
    });
    
    // Start observing when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            chatlingObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    } else {
        chatlingObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();