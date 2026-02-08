/**
 * MLC Toolkit — Mobile Full-Screen Slideshow
 * "View Pretty Photos" button in mobile nav opens a full-screen overlay
 * with the same photo set (randomized), forward/back navigation.
 */
(function () {
    'use strict';

    // Get photo data from the localized variable (set by MLC Toolkit plugin)
    // Falls back to empty array — overlay won't render if no photos
    var photos = (typeof mlcPhotos !== 'undefined' && mlcPhotos.photos)
        ? mlcPhotos.photos
        : [];

    if (photos.length === 0) return;

    var currentIndex = 0;
    var overlayEl = null;
    var photoContainer = null;
    var captionTitle = null;
    var captionCredit = null;
    var counterEl = null;
    var autoAdvanceTimer = null;
    var SLIDESHOW_DURATION = 6000;

    /**
     * Build the overlay DOM (once, on first open)
     */
    function buildOverlay() {
        if (overlayEl) return;

        overlayEl = document.createElement('div');
        overlayEl.className = 'mlc-slideshow-overlay';
        overlayEl.innerHTML =
            '<button class="mlc-slideshow-overlay__close" aria-label="Close slideshow">&times;</button>' +
            '<div class="mlc-slideshow-overlay__counter" id="mlcSlideshowCounter"></div>' +
            '<div class="mlc-slideshow-overlay__photo" id="mlcSlideshowPhoto"></div>' +
            '<div class="mlc-slideshow-overlay__caption">' +
                '<div class="mlc-slideshow-overlay__caption-title" id="mlcSlideshowCaptionTitle"></div>' +
                '<div class="mlc-slideshow-overlay__caption-credit" id="mlcSlideshowCaptionCredit"></div>' +
            '</div>' +
            '<button class="mlc-slideshow-overlay__nav mlc-slideshow-overlay__nav--prev" aria-label="Previous photo">' +
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>' +
            '</button>' +
            '<button class="mlc-slideshow-overlay__nav mlc-slideshow-overlay__nav--next" aria-label="Next photo">' +
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>' +
            '</button>';

        document.body.appendChild(overlayEl);

        photoContainer = document.getElementById('mlcSlideshowPhoto');
        captionTitle   = document.getElementById('mlcSlideshowCaptionTitle');
        captionCredit  = document.getElementById('mlcSlideshowCaptionCredit');
        counterEl      = document.getElementById('mlcSlideshowCounter');

        // Pre-build photo divs
        photos.forEach(function (photo, i) {
            var div = document.createElement('div');
            div.className = 'mlc-slideshow-overlay__img';
            div.dataset.index = i;
            div.style.backgroundImage = 'url(' + photo.url + ')';
            photoContainer.appendChild(div);
        });

        // Event listeners
        overlayEl.querySelector('.mlc-slideshow-overlay__close').addEventListener('click', closeOverlay);
        overlayEl.querySelector('.mlc-slideshow-overlay__nav--prev').addEventListener('click', prevPhoto);
        overlayEl.querySelector('.mlc-slideshow-overlay__nav--next').addEventListener('click', nextPhoto);

        // Swipe support
        var touchStartX = 0;
        overlayEl.addEventListener('touchstart', function (e) {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        overlayEl.addEventListener('touchend', function (e) {
            var diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) nextPhoto();
                else prevPhoto();
            }
        }, { passive: true });

        // Keyboard navigation
        document.addEventListener('keydown', function (e) {
            if (!overlayEl || !overlayEl.classList.contains('is-open')) return;
            if (e.key === 'Escape') closeOverlay();
            if (e.key === 'ArrowLeft') prevPhoto();
            if (e.key === 'ArrowRight') nextPhoto();
        });
    }

    function showPhoto(index) {
        index = ((index % photos.length) + photos.length) % photos.length;
        currentIndex = index;

        var allImgs = photoContainer.querySelectorAll('.mlc-slideshow-overlay__img');
        allImgs.forEach(function (img) {
            img.classList.remove('is-visible');
        });

        var target = photoContainer.querySelector('[data-index="' + index + '"]');
        if (target) {
            // Force animation restart for Ken Burns
            target.style.animation = 'none';
            target.offsetHeight;
            target.style.animation = '';
            target.classList.add('is-visible');
        }

        if (captionTitle) captionTitle.textContent = photos[index].caption || '';
        if (captionCredit) captionCredit.textContent = photos[index].credit || '';
        if (counterEl) counterEl.textContent = (index + 1) + ' / ' + photos.length;
    }

    function startAutoAdvance() {
        stopAutoAdvance();
        autoAdvanceTimer = setInterval(function () {
            showPhoto(currentIndex + 1);
        }, SLIDESHOW_DURATION);
    }

    function stopAutoAdvance() {
        if (autoAdvanceTimer) {
            clearInterval(autoAdvanceTimer);
            autoAdvanceTimer = null;
        }
    }

    function resetAutoAdvance() {
        startAutoAdvance();
    }

    function nextPhoto() {
        showPhoto(currentIndex + 1);
        resetAutoAdvance();
    }

    function prevPhoto() {
        showPhoto(currentIndex - 1);
        resetAutoAdvance();
    }

    function openOverlay() {
        buildOverlay();
        currentIndex = 0;
        showPhoto(0);
        overlayEl.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        startAutoAdvance();
    }

    function closeOverlay() {
        stopAutoAdvance();
        if (overlayEl) {
            overlayEl.classList.remove('is-open');
        }
        document.body.style.overflow = '';
    }

    // Bind to the "View Pretty Photos" button
    function init() {
        var btn = document.getElementById('navPhotosBtn');
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openOverlay();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
