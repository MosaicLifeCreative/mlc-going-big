(function() {
    'use strict';

    // ─── CONFIGURATION ──────────────────────────────────────────
    const CONFIG = {
        huntDebugMode: false,

        // Static fallback phases (when AI is off or fails)
        staticPhases: [
            { text: "Hello.", duration: 2400, size: "clamp(48px, 10vw, 96px)", tracking: -2, highlight: false, isHeading: true },
            { text: "We build websites that make people stop scrolling.", duration: 3400, size: "clamp(26px, 4.5vw, 48px)", tracking: -0.5, highlight: false, isHeading: false },
            { text: "Built Different.", duration: 3000, size: "clamp(40px, 8vw, 80px)", tracking: -1.5, highlight: true, isHeading: true },
            { text: "So how do you want your website to feel?", duration: null, size: "clamp(24px, 4vw, 42px)", tracking: -0.5, highlight: false, isHeading: false }
        ],

        // Wheatley test messages (hardcoded for now, API later)
        wheatleyMessages: [
            "Right, so... it's been 30 seconds. I'm an AI. I'm literally counting.",
            "Three minutes. You're committed now. I respect that. Or you forgot this tab was open. I respect that too, honestly.",
            "Ten minutes. I cost fractions of a penny per message. This conversation has cost... about 1.5 cents. Worth it?",
            "Twenty minutes. Are you real? I'm genuinely asking. Because I'm not, and this feels weird.",
            "Half an hour. Nobody stays this long. You've earned something. Here: 4 8 15 16 23 42. Does that mean anything to you?"
        ],

        // Countdown target time
        targetHour: 15,
        targetMin: 16,
        targetSec: 23,
        windowDuration: 42,

        // Nav photos
        navPhotos: [
            { caption: "Trail at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" },
            { caption: "Rincon Mountains, Tucson", credit: "TREY KAUFFMAN" },
            { caption: "San Francisco Peaks, Flagstaff", credit: "TREY KAUFFMAN" },
            { caption: "Sunset at Buffalo Park, Flagstaff", credit: "TREY KAUFFMAN" }
        ],

        slideshowDuration: 6000,

        // Chatbot flows (KEEP until Chatling integration complete)
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

    // ─── UTILITY: URL PERSONALIZATION DECODER ──────────────────
    // NEW: Decode URL parameters for personalized Wheatley messages
    function getPersonalizationFromURL() {
        const params = new URLSearchParams(window.location.search);
        const encoded = params.get('u');
        
        if (!encoded) return { name: null, isBirthday: false };
        
        try {
            const decoded = atob(encoded); // Base64 decode
            const parts = decoded.split(',');
            
            return {
                name: parts[0] || null,
                isBirthday: parts[1] === 'birthday'
            };
        } catch (e) {
            console.error('Failed to decode personalization:', e);
            return { name: null, isBirthday: false };
        }
    }

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
        slideshowPaused: false,
        
        // Wheatley idle tracking
        idleTime: 0,
        wheatleyActive: false,
        wheatleyMessageCount: 0,
        lastActivityTime: Date.now(),
        sessionStart: Date.now(),
        hasScrolled: false,
        hasInteracted: false,
        
        // NEW: Personalization from URL
        userName: null,
        isBirthday: false
    };

    // NEW: Get personalization on page load
    const personalization = getPersonalizationFromURL();
    state.userName = personalization.name;
    state.isBirthday = personalization.isBirthday;

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
        navNext: $('#navNext'),
        phaseText: $('#phaseText'),
        phaseHeadline: $('#phaseHeadline'),
        choiceButtons: $('#choiceButtons'),
        btnPrimary: $('#btnPrimary'),
        btnSecondary: $('#btnSecondary'),
        loadingText: $('#loadingText'),
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

    // ─── WHEATLEY IDLE DETECTION ────────────────────────────────
    function resetIdleTimer() {
        state.lastActivityTime = Date.now();
        state.idleTime = 0;
        state.hasInteracted = true;
    }

    function checkIdleTime() {
        const now = Date.now();
        const timeOnPage = Math.floor((now - state.sessionStart) / 1000);
        const timeSinceActivity = Math.floor((now - state.lastActivityTime) / 1000);
        
        state.idleTime = timeOnPage;
        
        if (state.wheatleyActive) return;
        
        const thresholds = [30, 180, 600, 1200, 1800];
        
        for (let i = 0; i < thresholds.length; i++) {
            if (timeOnPage >= thresholds[i] && state.wheatleyMessageCount === i) {
                triggerWheatley(i + 1);
                state.wheatleyMessageCount++;
                break;
            }
        }
        
        if (timeOnPage >= 1800 && timeOnPage < 5400 && timeOnPage % 600 === 0) {
            const extraMessages = Math.floor((timeOnPage - 1800) / 600);
            if (state.wheatleyMessageCount === 5 + extraMessages) {
                triggerWheatley(6 + extraMessages);
                state.wheatleyMessageCount++;
            }
        }

        if (timeOnPage >= 5400 && state.wheatleyMessageCount < 999) {
            triggerWheatley(999);
            state.wheatleyMessageCount = 999;
        }
    }

    // ─── CONTEXT DETECTION ────────────────────────────────
    function detectVisitorType() {
        const visitKey = 'mlc_visited';
        const countKey = 'mlc_visit_count';
        
        let isReturning = false;
        let visitCount = 1;
        
        try {
            const lastVisit = localStorage.getItem(visitKey);
            if (lastVisit) {
                isReturning = true;
                visitCount = parseInt(localStorage.getItem(countKey) || '1') + 1;
            }
            
            localStorage.setItem(visitKey, Date.now().toString());
            localStorage.setItem(countKey, visitCount.toString());
        } catch (e) {
            // localStorage blocked - treat as new visitor
        }
        
        return { isReturning, visitCount };
    }

    function getCurrentTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        
        const period = hours >= 12 ? 'PM' : 'AM';
        const displayHours = hours % 12 || 12;
        const displayMinutes = String(minutes).padStart(2, '0');
        
        return `${displayHours}:${displayMinutes} ${period}`;
    }

    function detectDeviceType() {
        const ua = navigator.userAgent.toLowerCase();
        
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return 'tablet';
        }
        if (/mobile|iphone|ipod|blackberry|opera mini|iemobile/i.test(ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    async function triggerWheatley(messageNumber) {
        if (state.wheatleyActive) return;
        state.wheatleyActive = true;
        
        console.log(`🤖 Wheatley Message #${messageNumber} triggered at ${state.idleTime}s idle`);
        
        const idleSeconds = state.idleTime;
        
        let countdownStatus = 'inactive';
        const now = new Date();
        const target = new Date(now);
        target.setHours(15, 16, 23, 0);
        
        const timeUntil = (target - now) / 1000;
        if (timeUntil <= 60 && timeUntil > 0) {
            countdownStatus = 'near';
        } else if (state.huntWindowActive) {
            countdownStatus = 'active';
        }
        
        try {
            const response = await fetch('/wp-json/mlc/v1/wheatley', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    idle_time: idleSeconds,
                    message_number: messageNumber,
                    countdown_status: countdownStatus,
                    previous_messages: state.previousWheatleyMessages || [],
                    visitor: detectVisitorType(),
                    current_time: getCurrentTime(),
                    device: detectDeviceType(),
                    session_duration: Math.floor((Date.now() - state.sessionStart) / 1000),
                    has_scrolled: state.hasScrolled,
                    has_interacted: state.hasInteracted,
                    // NEW: Personalization context
                    user_name: state.userName || null,
                    is_birthday: state.isBirthday || false
                })
            });
            
            const data = await response.json();
            
            if (data.success && data.message) {
                if (!state.previousWheatleyMessages) {
                    state.previousWheatleyMessages = [];
                }
                state.previousWheatleyMessages.push(data.message);
                
                await displayWheatley(data.message);
            } else {
                const messageIndex = Math.min(messageNumber - 1, CONFIG.wheatleyMessages.length - 1);
                await displayWheatley(CONFIG.wheatleyMessages[messageIndex]);
            }
        } catch (error) {
            console.error('Wheatley API error:', error);
            const apiFallback = "Right, so... bit awkward. The person who built me ran out of API credits. So instead of my usual dynamically-generated wit, you get this pre-written message. It's like ordering a gourmet meal and getting a microwave dinner. I'm still here, just... significantly less interesting. Apologies.";
            await displayWheatley(apiFallback);
        }
    }

    async function displayWheatley(text) {
        if (state.currentPhase !== 4) {
            state.currentPhase = 4;
            showPhase();
            await sleep(300);
        }
        
        els.phaseHeadline.style.display = 'none';
        
        let wheatleyContainer = document.getElementById('wheatley-message');
        if (!wheatleyContainer) {
            wheatleyContainer = document.createElement('div');
            wheatleyContainer.id = 'wheatley-message';
            wheatleyContainer.style.fontSize = 'clamp(24px, 4vw, 42px)';
            wheatleyContainer.style.letterSpacing = '-0.5px';
            wheatleyContainer.style.fontWeight = 'var(--weight-body)';
            wheatleyContainer.style.marginBottom = '1rem';
            wheatleyContainer.style.minHeight = '180px';
            wheatleyContainer.style.lineHeight = '1.3';
            wheatleyContainer.style.display = 'block';
            els.phaseHeadline.parentNode.insertBefore(wheatleyContainer, els.phaseHeadline);
        }
        
        wheatleyContainer.textContent = '';
        
        for (let i = 0; i < text.length; i++) {
            wheatleyContainer.textContent += text[i];
            await sleep(30);
        }
        
        const cursor = document.createElement('span');
        cursor.className = 'wheatley-cursor';
        wheatleyContainer.appendChild(cursor);
        
        state.wheatleyActive = false;
    }

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
                await sleep(2200);
                state.showChoice = true;
                els.choiceButtons.classList.add('is-visible');
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

    // ─── SCROLL LISTENER ────────────────────────────────────────
    window.addEventListener('scroll', () => {
        state.hasScrolled = true;
        resetIdleTimer();
    }, { once: true });

    // ─── COUNTDOWN TIMER ────────────────────────────────────────
    function updateCountdown() {
        if (CONFIG.huntDebugMode) {
            els.countdown.textContent = '00:00:00';
            els.countdown.classList.add('is-active');
            els.huntEnterBtn.classList.add('is-active');
            return;
        }

        const now = new Date();
        const target = new Date();
        target.setHours(CONFIG.targetHour, CONFIG.targetMin, CONFIG.targetSec, 0);

        let diff = (target - now) / 1000;

        if (diff <= 0 && diff > -CONFIG.windowDuration) {
            els.countdown.textContent = '00:00:00';
            els.countdown.classList.add('is-active');
            els.huntEnterBtn.classList.add('is-active');
            return;
        }

        if (diff <= -CONFIG.windowDuration) {
            target.setDate(target.getDate() + 1);
            diff = (target - now) / 1000;
            els.huntEnterBtn.classList.remove('is-active');

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

        fetch(mlcHunt.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'mlc_validate_hunt',
                nonce: mlcHunt.nonce,
                sequence: input
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.correct) {
                state.huntStatus = 'success';
                
                setTimeout(() => {
                    window.open(data.data.redirect, '_blank');
                    closeHuntModal();
                }, 800);
            } else {
                state.huntStatus = 'wrong';
                
                if (data.data && data.data.message) {
                    els.huntError.textContent = data.data.message;
                } else {
                    els.huntError.textContent = 'Not quite. Try again.';
                }
                
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
        })
        .catch(error => {
            console.error('Hunt validation error:', error);
            els.huntError.textContent = 'Connection error. Try again.';
            els.huntError.classList.add('is-visible');
        });
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
        updateCountdown();
        setInterval(updateCountdown, 1000);

        setInterval(checkIdleTime, 1000);

        document.addEventListener('mousemove', resetIdleTimer);
        document.addEventListener('keydown', resetIdleTimer);
        document.addEventListener('scroll', resetIdleTimer);
        document.addEventListener('click', resetIdleTimer);

        setTimeout(() => {
            runSequence();
        }, 500);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();