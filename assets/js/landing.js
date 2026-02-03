(function() {
    'use strict';

    // ─── CONFIGURATION ──────────────────────────────────────────
    const CONFIG = {
        // Static fallback phases (when AI is off or fails)
        staticPhases: [
            { text: "Hello.", duration: 2400, size: "clamp(48px, 10vw, 96px)", tracking: -2, highlight: false, isHeading: true },
            { text: "We build websites that make people stop scrolling.", duration: 3400, size: "clamp(26px, 4.5vw, 48px)", tracking: -0.5, highlight: false, isHeading: false },
            { text: "Built Different.", duration: 3000, size: "clamp(40px, 8vw, 80px)", tracking: -1.5, highlight: true, isHeading: true },
            { text: "So how do you want yours to feel?", duration: null, size: "clamp(24px, 4vw, 42px)", tracking: -0.5, highlight: false, isHeading: false }
        ],

        // Countdown target: 3:16:23 PM (Lost numbers: 3, 16, 23)
        targetHour: 15,
        targetMin: 16,
        targetSec: 23,
        windowDuration: 42, // seconds

        // Hunt sequence
        targetSequence: "4815162342",
        questDomain: "4815162342.quest",

        // Nav photos - captions to be added later
        navPhotos: [
            { caption: "Photo 01", credit: "MLC" },
            { caption: "Photo 02", credit: "MLC" },
            { caption: "Photo 03", credit: "MLC" },
            { caption: "Photo 04", credit: "MLC" }
        ],

        slideshowDuration: 6000, // 7 seconds per photo

        // Chatbot flows
        chatFlows: {
            opener: { from: "bot", text: "Interesting choice. Tell me something — what made you click that?" },
            answers: {
                curious: [
                    { from: "bot", text: "Hmm. That's honest. I like honest." },
                    { from: "bot", text: "Most people just click things. You actually stopped and thought about it." },
                    { from: "bot", text: "Let me ask you this — do you believe a website can actually change how people perceive a business?" }
                ],
                boredom: [
                    { from: "bot", text: "Fair enough. Nothing wrong with that." },
                    { from: "bot", text: "But you did click 'Interesting.' So something pulled you." },
                    { from: "bot", text: "What if I told you there's more to this page than meets the eye?" }
                ],
                needing: [
                    { from: "bot", text: "Good. Then you're in the right place." },
                    { from: "bot", text: "But before we talk business — I want to know something." },
                    { from: "bot", text: "What does your current website make you feel when you look at it?" }
                ]
            },
            deeper: {
                yes: [
                    { from: "bot", text: "Then you already understand more than most." },
                    { from: "bot", text: "Most people think a website is just a page with information on it." },
                    { from: "bot", text: "It's not. It's the first conversation your business has with a stranger." },
                    { from: "bot", text: "And right now, yours is boring." },
                    { from: "bot", text: "Want to see what it could be instead?" }
                ],
                no: [
                    { from: "bot", text: "Most people don't think so either." },
                    { from: "bot", text: "They're wrong." },
                    { from: "bot", text: "A website is the first conversation your business ever has with a stranger." },
                    { from: "bot", text: "And that conversation either pulls them in... or loses them in 3 seconds." },
                    { from: "bot", text: "Want to see the difference?" }
                ]
            },
            reward: [
                { from: "bot", text: "Smart." },
                { from: "bot", text: "Alright. You've earned it." },
                { from: "bot", text: "🎯 Welcome to the interesting side." }
            ]
        }
    };

    // ─── STATE ──────────────────────────────────────────────────
    const state = {
        phase: 0,
        seqState: 'showing',
        displayedPhase: 0,
        phases: CONFIG.staticPhases,
        showChoice: false,
        clicked: null,
        huntModalOpen: false,
        huntStatus: 'idle',
        navOpen: false,
        chatStage: 'open',
        chatTyping: false,
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
        navPrev: $('#navPrev'),
        navNext: $('#navNext'),
        phaseText: $('#phaseText'),
        phaseHeadline: $('#phaseHeadline'),
        choiceButtons: $('#choiceButtons'),
        btnPrimary: $('#btnPrimary'),
        btnSecondary: $('#btnSecondary'),
        loadingText: $('#loadingText'),
        scrollHint: $('#scrollHint'),
        countdown: $('#countdown'),
        huntEnterBtn: $('#huntEnterBtn'),
        huntModal: $('#huntModal'),
        huntCard: $('#huntCard'),
        huntClose: $('#huntClose'),
        huntInput: $('#huntInput'),
        huntError: $('#huntError'),
        huntSubmit: $('#huntSubmit'),
        chatbot: $('#chatbot'),
        chatbotClose: $('#chatbotClose'),
        chatMessages: $('#chatMessages')
    };

    // ─── UTILITY FUNCTIONS ──────────────────────────────────────
    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // ─── NAV PHOTO SLIDESHOW ────────────────────────────────────
    function showSlide(index) {
        // Hide default photo
        if (els.navPhotoDefault) {
            els.navPhotoDefault.classList.remove('is-visible');
        }

        // Hide all photos and clear their animations
        els.navPhotos.forEach(p => {
            p.classList.remove('is-visible');
        });

        // Show target photo
        const targetPhoto = document.querySelector(`.nav-photo[data-photo="${index}"]`);
        if (targetPhoto) {
            // Remove animation
            targetPhoto.style.animation = 'none';
            
            // Force reflow - this is the critical part
            targetPhoto.offsetHeight;
            
            // Re-apply animation - starts from beginning
            targetPhoto.style.animation = '';
            targetPhoto.classList.add('is-visible');
        }

        // Update caption
        if (els.navCaption && els.navCaptionTitle) {
            els.navCaption.classList.add('is-visible');
            els.navCaptionTitle.textContent = CONFIG.navPhotos[index].caption;
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
        
        // Show first slide immediately
        showSlide(0);
        
        // Start auto-advance
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
        
        // Reset all photos
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
        // Restart the interval when user manually navigates
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

    // Hamburger magnetic effect
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

    // Slideshow controls
    if (els.navPrev) {
        els.navPrev.addEventListener('click', prevSlide);
    }
    if (els.navNext) {
        els.navNext.addEventListener('click', nextSlide);
    }

    // ─── TEXT SEQUENCE ──────────────────────────────────────────
    function updatePhaseDisplay() {
        const current = state.phases[state.displayedPhase];
        if (!current || !current.text) return;

        els.phaseHeadline.textContent = current.text;
        els.phaseHeadline.style.fontSize = current.size;
        els.phaseHeadline.style.letterSpacing = current.tracking + 'px';
        els.phaseHeadline.style.fontWeight = current.isHeading ? 'var(--weight-heading)' : 'var(--weight-body)';

        if (current.highlight) {
            els.phaseHeadline.classList.add('is-highlight');
        } else {
            els.phaseHeadline.classList.remove('is-highlight');
        }
    }

    function showPhase() {
        els.phaseText.classList.add('is-visible');
    }

    function hidePhase() {
        els.phaseText.classList.remove('is-visible');
    }

    async function runSequence() {
        for (let i = 0; i < state.phases.length; i++) {
            state.phase = i;
            state.displayedPhase = i;

            updatePhaseDisplay();
            showPhase();

            const current = state.phases[i];

            if (current.duration === null) {
                // Last phase - show buttons after delay
                await sleep(2200);
                state.showChoice = true;
                els.choiceButtons.classList.add('is-visible');
                els.scrollHint.style.display = 'none';
                break;
            }

            await sleep(current.duration);
            hidePhase();
            await sleep(550);
        }
    }

    // ─── CHOICE BUTTONS ─────────────────────────────────────────
    els.btnSecondary.addEventListener('click', function() {
        state.clicked = 'safe';
        els.choiceButtons.style.display = 'none';
        els.loadingText.textContent = 'Redirecting...';
        els.loadingText.style.display = 'block';
        setTimeout(() => {
            window.open('https://www.squarespace.com', '_blank');
        }, 600);
    });

    els.btnPrimary.addEventListener('click', function() {
        state.clicked = 'bold';
        els.choiceButtons.style.display = 'none';
        els.loadingText.textContent = 'Opening...';
        els.loadingText.style.display = 'block';
        setTimeout(() => {
            openChatbot();
        }, 400);
    });

    // ─── COUNTDOWN TIMER ────────────────────────────────────────
    function updateCountdown() {
        const now = new Date();
        const target = new Date();
        target.setHours(CONFIG.targetHour, CONFIG.targetMin, CONFIG.targetSec, 0);

        let diff = (target - now) / 1000;

        // Check if we're in the 42-second window
        if (diff <= 0 && diff > -CONFIG.windowDuration) {
            els.countdown.textContent = '00:00:00';
            els.countdown.classList.add('is-active');
            els.huntEnterBtn.classList.add('is-active');
            return;
        }

        // Window expired - reset to next day
        if (diff <= -CONFIG.windowDuration) {
            target.setDate(target.getDate() + 1);
            diff = (target - now) / 1000;
            els.huntEnterBtn.classList.remove('is-active');

            // Only clear hunt state if modal isn't open
            if (!state.huntModalOpen) {
                els.huntInput.value = '';
                state.huntStatus = 'idle';
                els.huntError.classList.remove('is-visible');
            }
        }

        const hours = Math.floor(diff / 3600);
        const mins = Math.floor((diff % 3600) / 60);
        const secs = Math.floor(diff % 60);

        els.countdown.textContent = `${pad(hours)}:${pad(mins)}:${pad(secs)}`;
        els.countdown.classList.remove('is-active');
    }

    // ─── HUNT MODAL ─────────────────────────────────────────────
    function openHuntModal() {
        state.huntModalOpen = true;
        els.huntModal.classList.add('is-open');
        els.huntInput.focus();
    }

    function closeHuntModal() {
        state.huntModalOpen = false;
        els.huntModal.classList.remove('is-open');
        els.huntInput.value = '';
        state.huntStatus = 'idle';
        els.huntError.classList.remove('is-visible');
    }

    function validateHunt() {
        const input = els.huntInput.value;

        if (input === CONFIG.targetSequence) {
            state.huntStatus = 'success';
            
            // Redirect to quest site
            setTimeout(() => {
                window.location.href = 'https://' + CONFIG.questDomain;
            }, 800);
        } else {
            state.huntStatus = 'wrong';
            els.huntError.classList.add('is-visible');
            els.huntCard.classList.add('shake');

            setTimeout(() => {
                els.huntCard.classList.remove('shake');
            }, 500);

            setTimeout(() => {
                state.huntStatus = 'idle';
                els.huntError.classList.remove('is-visible');
            }, 1500);
        }
    }

    els.huntEnterBtn.addEventListener('click', openHuntModal);
    els.huntClose.addEventListener('click', closeHuntModal);
    els.huntSubmit.addEventListener('click', validateHunt);

    els.huntInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        state.huntStatus = 'idle';
        els.huntError.classList.remove('is-visible');
    });

    els.huntInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') validateHunt();
        if (e.key === 'Escape') closeHuntModal();
    });

    // ─── CHATBOT ────────────────────────────────────────────────
    function openChatbot() {
        els.chatbot.classList.add('is-open');
        state.chatStage = 'open';
        initChatbot();
    }

    function closeChatbot() {
        els.chatbot.classList.remove('is-open');
        els.chatMessages.innerHTML = '';
    }

    function addMessage(from, text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chat-message chat-message--${from}`;

        const bubble = document.createElement('div');
        bubble.className = 'chat-message__bubble';
        bubble.textContent = text;

        msgDiv.appendChild(bubble);
        els.chatMessages.appendChild(msgDiv);
        els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
    }

    function showTyping() {
        state.chatTyping = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message chat-message--bot';
        typingDiv.id = 'typingIndicator';

        const bubble = document.createElement('div');
        bubble.className = 'chat-message__bubble';
        bubble.innerHTML = '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>';

        typingDiv.appendChild(bubble);
        els.chatMessages.appendChild(typingDiv);
        els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
    }

    function hideTyping() {
        state.chatTyping = false;
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    function showOptions(options) {
        const optionsDiv = document.createElement('div');
        optionsDiv.className = 'chat-options';
        optionsDiv.id = 'chatOptions';

        options.forEach(opt => {
            const btn = document.createElement('button');
            btn.className = 'chat-option';
            btn.textContent = opt.label;
            btn.addEventListener('click', () => handleOption(opt.value));
            optionsDiv.appendChild(btn);
        });

        els.chatMessages.appendChild(optionsDiv);
        els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
    }

    function hideOptions() {
        const opts = document.getElementById('chatOptions');
        if (opts) opts.remove();
    }

    async function addMessagesSequence(messages, delay = 600) {
        for (let i = 0; i < messages.length; i++) {
            await sleep(delay + Math.random() * 400);
            showTyping();
            await sleep(800 + Math.random() * 400);
            hideTyping();
            addMessage(messages[i].from, messages[i].text);
        }
    }

    async function initChatbot() {
        showTyping();
        await sleep(1000);
        hideTyping();
        addMessage('bot', CONFIG.chatFlows.opener.text);

        await sleep(1200);
        showOptions([
            { label: "I was just curious", value: "curious" },
            { label: "I'm bored", value: "boredom" },
            { label: "I actually need a website", value: "needing" }
        ]);
    }

    async function handleOption(value) {
        hideOptions();

        if (state.chatStage === 'open') {
            const labels = {
                curious: "I was just curious",
                boredom: "I'm bored",
                needing: "I actually need a website"
            };
            addMessage('user', labels[value]);

            const flow = CONFIG.chatFlows.answers[value];
            await addMessagesSequence(flow, 400);

            await sleep(800);
            showOptions([
                { label: "Yeah, I think it can.", value: "yes" },
                { label: "Honestly? No.", value: "no" }
            ]);
            state.chatStage = 'deeper';

        } else if (state.chatStage === 'deeper') {
            addMessage('user', value === 'yes' ? "Yeah, I think it can." : "Honestly? No.");

            const flow = CONFIG.chatFlows.deeper[value];
            await addMessagesSequence(flow, 400);

            await sleep(600);
            showOptions([
                { label: "Show me.", value: "reward" }
            ]);
            state.chatStage = 'reward';

        } else if (state.chatStage === 'reward') {
            addMessage('user', "Show me.");

            await addMessagesSequence(CONFIG.chatFlows.reward, 400);

            await sleep(400);
            showDoneCard();
            state.chatStage = 'done';
        }
    }

    function showDoneCard() {
        const card = document.createElement('div');
        card.className = 'chat-done-card';
        card.innerHTML = `
            <div class="chat-done-card__title">This is where it gets real.</div>
            <div class="chat-done-card__desc">In production, this is where the bot branches — adventure, services, or deeper into the mystery. This is just the proof of concept.</div>
        `;
        els.chatMessages.appendChild(card);
        els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
    }

    els.chatbotClose.addEventListener('click', closeChatbot);

    // ─── INITIALIZE ─────────────────────────────────────────────
    function init() {
        // Start countdown timer
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Start text sequence
        setTimeout(() => {
            runSequence();
        }, 500);
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();