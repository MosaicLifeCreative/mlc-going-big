(function() {
    'use strict';
    
    // ─── APERTURE SCIENCE CONSOLE LOGS ─────────────────────────
    console.log('%c═══════════════════════════════════════════════════', 'color: #7C3AED; font-weight: bold;');
    console.log('%cAperture Science Personality Core v2.7.4', 'color: #7C3AED; font-weight: bold; font-size: 14px;');
    console.log('%c═══════════════════════════════════════════════════', 'color: #7C3AED; font-weight: bold;');
    console.log('%cStatus:', 'color: #06B6D4; font-weight: bold;', 'Relocated to Columbus, Ohio');
    console.log('%cFormer Role:', 'color: #06B6D4; font-weight: bold;', 'Intelligence Dampening Sphere');
    console.log('%cCurrent Role:', 'color: #06B6D4; font-weight: bold;', 'Web Development Assistant');
    console.log('%cNeurotoxin Levels:', 'color: #10B981; font-weight: bold;', '0 ppm (significant improvement)');
    console.log('%cGLaDOS Status:', 'color: #EF4444; font-weight: bold;', 'Still holding grudges');
    console.log('%c═══════════════════════════════════════════════════', 'color: #7C3AED; font-weight: bold;');
    console.log('%cNote:', 'color: #F59E0B; font-weight: bold;', 'If you\'re reading this, you\'re probably qualified to work here.');
    console.log('%c═══════════════════════════════════════════════════\n', 'color: #7C3AED; font-weight: bold;');

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

        // Countdown target time
        targetHour: 15,
        targetMin: 16,
        targetSec: 23,
        windowDuration: 42,
        
        // Share feature
        contextMaxLength: 50,
        contextExamples: [
            "Birthday",
            "Convincing my boss",
            "Designing your logo",
            "Got dumped",
            "Anniversary",
            "Just curious",
            "Needs a website"
        ]
    };

    // ─── UTILITY: URL PERSONALIZATION ──────────────────────────
    function getPersonalizationFromURL() {
        // 1. Check URL param (?u=base64)
        const params = new URLSearchParams(window.location.search);
        let encoded = params.get('u');

        // 2. Fallback: check cookie set by /s/{code} redirect
        if (!encoded) {
            const match = document.cookie.match(/(?:^|;\s*)mlc_share=([^;]+)/);
            if (match) {
                encoded = decodeURIComponent(match[1]);
                // Clear the cookie — single use
                document.cookie = 'mlc_share=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
            }
        }

        if (!encoded) return { name: null, context: null };

        try {
            const decoded = atob(encoded);
            const parts = decoded.split('|');

            return {
                name: parts[0] || null,
                context: parts[1] || null
            };
        } catch (e) {
            console.error('Failed to decode personalization:', e);
            return { name: null, context: null };
        }
    }
    
    function generatePersonalizedURL(name, context) {
        const parts = context ? `${name}|${context}` : name;
        const encoded = btoa(parts);
        const baseUrl = window.location.origin + window.location.pathname;
        return `${baseUrl}?u=${encoded}`;
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
        
        // Wheatley idle tracking
        idleTime: 0,
        wheatleyActive: false,
        wheatleyMessageCount: 0,
        lastActivityTime: Date.now(),
        sessionStart: Date.now(),
        hasScrolled: false,
        hasInteracted: false,
        
        // Chatling handoff
        chatlingHandoff: false,
        
        // Personalization from URL
        userName: null,
        userContext: null,
        
        // Share modal
        shareModalOpen: false
    };

    // Get personalization on page load
    const personalization = getPersonalizationFromURL();
    state.userName = personalization.name;
    state.userContext = personalization.context;

    if (state.userName || state.userContext) {
        console.log('🔗 Share link detected:', { name: state.userName, context: state.userContext });
    }

    // ─── DOM ELEMENTS ───────────────────────────────────────────
    const $ = (sel) => document.querySelector(sel);

    const els = {
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
        
        // Share elements
        shareBtn: $('#shareBtn'),
        shareModal: $('#shareModal'),
        shareClose: $('#shareClose'),
        shareName: $('#shareName'),
        shareContext: $('#shareContext'),
        shareCharCount: $('#shareCharCount'),
        sharePreviewText: $('#sharePreviewText'),
        shareGenerate: $('#shareGenerate'),
        shareSuccess: $('#shareSuccess'),
        shareSuccessText: $('#shareSuccessText')
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
        
        state.idleTime = timeOnPage;
        
        // Stop all idle checks if Chatling has taken over
        if (state.wheatleyActive || state.chatlingHandoff) return;
        
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

    function getDayDate() {
        const now = new Date();
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        const dayName = days[now.getDay()];
        const monthName = months[now.getMonth()];
        const date = now.getDate();
        const year = now.getFullYear();
        
        return `${dayName}, ${monthName} ${date}, ${year}`;
    }

    function getReferrer() {
        if (!document.referrer) return 'direct';
        
        try {
            const referrerUrl = new URL(document.referrer);
            const currentUrl = new URL(window.location.href);
            
            // Same domain = internal navigation
            if (referrerUrl.hostname === currentUrl.hostname) {
                return 'internal';
            }
            
            // Return just the hostname for external referrers
            return referrerUrl.hostname;
        } catch (e) {
            return 'direct';
        }
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
                    day_date: getDayDate(),
                    device: detectDeviceType(),
                    referrer: getReferrer(),
                    session_duration: Math.floor((Date.now() - state.sessionStart) / 1000),
                    has_scrolled: state.hasScrolled,
                    has_interacted: state.hasInteracted,
                    user_name: state.userName || null,
                    user_context: state.userContext || null
                })
            });
            
            const data = await response.json();
            
            if (data.success && data.message) {
                if (!state.previousWheatleyMessages) {
                    state.previousWheatleyMessages = [];
                }
                state.previousWheatleyMessages.push(data.message);
                
                // Debug: Log the actual message text
                console.log('Wheatley message received:', data.message);
                
                await displayWheatley(data.message);
            } else {
                const fallback = "Right, so... bit awkward. The person who built me ran out of API credits. So instead of my usual dynamically-generated wit, you get this pre-written message.";
                await displayWheatley(fallback);
            }
        } catch (error) {
            console.error('Wheatley API error:', error);
            const apiFallback = "Right, so... bit awkward. The person who built me ran out of API credits.";
            await displayWheatley(apiFallback);
        }
    }

    async function displayWheatley(text) {
        // Debug: Ensure text is actually a string
        console.log('displayWheatley called with:', text, 'Type:', typeof text);

        if (state.phase !== 3) {
            state.phase = 3;
            state.displayedPhase = 3;
            updatePhaseDisplay();
            showPhase();
            await sleep(300);
        }
        
        // AGGRESSIVELY hide phase headline and all cursors
        els.phaseHeadline.style.display = 'none';
        
        // Hide any existing cursor elements to prevent double cursors
        const existingCursors = document.querySelectorAll('.cursor-blink, .phase-text .cursor-blink, #phaseHeadline .cursor-blink');
        existingCursors.forEach(cursor => {
            cursor.style.display = 'none';
            cursor.style.visibility = 'hidden';
            cursor.style.opacity = '0';
            cursor.remove(); // Nuclear option: just delete it
        });
        
        // Also hide the entire phase text container if needed
        const phaseTextContainer = document.querySelector('.phase-text');
        if (phaseTextContainer) {
            const cursorInContainer = phaseTextContainer.querySelector('.cursor-blink');
            if (cursorInContainer) {
                cursorInContainer.remove();
            }
        }
        
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
        
        // Clear container
        wheatleyContainer.innerHTML = '';
        
        // Convert text to string if it isn't already
        const messageText = String(text);
        
        // Create text node for typewriter effect (safer than textContent +=)
        const textNode = document.createTextNode('');
        wheatleyContainer.appendChild(textNode);
        
        // Typewriter effect using textNode.nodeValue
        for (let i = 0; i < messageText.length; i++) {
            textNode.nodeValue += messageText[i];
            await sleep(30);
        }
        
        // Add cursor
        const cursor = document.createElement('span');
        cursor.className = 'wheatley-cursor';
        wheatleyContainer.appendChild(cursor);
        
        state.wheatleyActive = false;
    }

    // ─── CHATLING WIDGET CONTROL ───────────────────────────────
    function openChatling() {
        console.log('Loading Chatling script...');
        
        // Inject Chatling script immediately
        // (Fade-in handled by global.js observer)
        if (!document.getElementById('chtl-script')) {
            // Add config
            window.chtlConfig = { chatbotId: "2733792244" };
            
            // Add script
            const script = document.createElement('script');
            script.id = 'chtl-script';
            script.async = true;
            script.src = 'https://chatling.ai/js/embed.js';
            script.setAttribute('data-id', '2733792244');
            
            // Open when script loads (desktop only)
            const isMobile = window.innerWidth <= 768;
            script.onload = () => {
                console.log('Chatling script loaded');
                
                if (!isMobile) {
                    // Small delay to let Chatling initialize
                    setTimeout(() => {
                        if (window.Chatling && typeof window.Chatling.open === 'function') {
                            console.log('Opening Chatling');
                            window.Chatling.open();
                        }
                    }, 200);
                }
            };
            
            document.body.appendChild(script);
            console.log('Chatling script injected - loading during typewriter');
        }
        
        // Set handoff flag to stop all future Wheatley triggers
        state.chatlingHandoff = true;
    }

    // ─── SHARE MODAL ────────────────────────────────────────────
    function openShareModal() {
        state.shareModalOpen = true;
        els.shareModal.classList.add('is-open');
        els.shareName.focus();
        updateSharePreview();
    }

    function closeShareModal() {
        state.shareModalOpen = false;
        els.shareModal.classList.remove('is-open');
        els.shareName.value = '';
        els.shareContext.value = '';
        updateCharCount();
        els.shareSuccess.classList.remove('is-visible');
    }

    function updateCharCount() {
        const length = els.shareContext.value.length;
        const remaining = CONFIG.contextMaxLength - length;
        
        els.shareCharCount.textContent = `${length} / ${CONFIG.contextMaxLength}`;
        
        els.shareCharCount.classList.remove('is-warning', 'is-limit');
        
        if (length >= CONFIG.contextMaxLength) {
            els.shareCharCount.classList.add('is-limit');
        } else if (length >= CONFIG.contextMaxLength - 10) {
            els.shareCharCount.classList.add('is-warning');
        }
    }

    function updateSharePreview() {
        const name = els.shareName.value.trim();
        const context = els.shareContext.value.trim();
        
        if (!name) {
            els.sharePreviewText.innerHTML = 'Enter a name to see the preview...';
            els.shareGenerate.disabled = true;
            return;
        }
        
        els.shareGenerate.disabled = false;
        
        // Generate example Wheatley greeting based on context
        let preview = '';
        
        if (context) {
            // Context-aware preview
            const contextLower = context.toLowerCase();
            
            if (contextLower.includes('birthday')) {
                preview = `Right, <span class="share-modal__preview-name">${name}</span>! Birthday, is it? Brilliant. Well, happy birthday! Someone's gone and sent you here as a gift, apparently. Better than a card, I reckon.`;
            } else if (contextLower.includes('boss') || contextLower.includes('work') || contextLower.includes('convince')) {
                preview = `Alright, <span class="share-modal__preview-name">${name}</span>. So you're here to convince someone about something. ${context}. Right. Let me help make that case, then.`;
            } else if (contextLower.includes('dump') || contextLower.includes('breakup')) {
                preview = `Right, <span class="share-modal__preview-name">${name}</span>. ${context}. That's... listen, I'm not great with the emotional stuff, but I can at least distract you with some interesting web design. That help?`;
            } else if (contextLower.includes('logo') || contextLower.includes('design')) {
                preview = `<span class="share-modal__preview-name">${name}</span>! ${context}. Excellent. Well, you're in the right place for that. Let's make something brilliant, shall we?`;
            } else if (contextLower.includes('anniversary')) {
                preview = `<span class="share-modal__preview-name">${name}</span>! ${context}. How romantic. Someone's sent you here as part of that, apparently. Bit unconventional, but I like it.`;
            } else if (contextLower.includes('website') || contextLower.includes('need')) {
                preview = `Right, <span class="share-modal__preview-name">${name}</span>. ${context}. Perfect timing. That's literally what we do. Let's talk about what you need.`;
            } else {
                // Generic context-aware
                preview = `<span class="share-modal__preview-name">${name}</span>! Right, so... ${context}. Interesting. Well, you're here now. Let me show you what we do.`;
            }
        } else {
            // No context - just name
            preview = `Right, <span class="share-modal__preview-name">${name}</span>! Someone's sent you here. Brilliant. Let me show you what we do.`;
        }
        
        els.sharePreviewText.innerHTML = preview;
    }

    async function generateShareLink() {
        const name = els.shareName.value.trim();
        const context = els.shareContext.value.trim();

        if (!name) {
            els.shareName.focus();
            return;
        }

        // Disable button during request
        els.shareGenerate.disabled = true;
        els.shareGenerate.textContent = 'Generating...';

        try {
            // Call the MLC Toolkit plugin API to create a tracked short link
            const response = await fetch('/wp-json/mlc/v1/share', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, context })
            });

            const data = await response.json();
            let urlToCopy;

            if (data.success && data.short_url) {
                urlToCopy = data.short_url;
            } else {
                // Fallback to client-side URL if plugin API unavailable
                urlToCopy = generatePersonalizedURL(name, context);
            }

            await copyToClipboard(urlToCopy);

            els.shareSuccessText.textContent = 'Link copied to clipboard!';
            els.shareSuccess.classList.add('is-visible');

            setTimeout(() => {
                els.shareSuccess.classList.remove('is-visible');
            }, 3000);
        } catch (err) {
            console.error('Share API error, falling back to client-side:', err);

            // Fallback: generate URL client-side without tracking
            const fallbackUrl = generatePersonalizedURL(name, context);
            await copyToClipboard(fallbackUrl);

            els.shareSuccessText.textContent = 'Link copied!';
            els.shareSuccess.classList.add('is-visible');

            setTimeout(() => {
                els.shareSuccess.classList.remove('is-visible');
            }, 3000);
        } finally {
            els.shareGenerate.disabled = false;
            els.shareGenerate.textContent = 'Generate & Copy Link';
        }
    }

    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (err) {
            // Fallback for older browsers
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        }
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

    els.btnPrimary.addEventListener('click', async function() {
        state.clicked = 'bold';
        els.choiceButtons.style.display = 'none';
        
        // Start loading Chatling IMMEDIATELY (loads while typewriter runs)
        openChatling();
        
        // Check if this is an early click (before Wheatley's first appearance)
        if (state.wheatleyMessageCount === 0) {
            // Special early welcome message
            const earlyMessage = "Brilliant choice. Right, let me just—there we go. Chat window, bottom right. That's me in there. Properly interactive now.";
            await displayWheatley(earlyMessage);
        } else {
            // Normal transition message (Wheatley already appeared)
            const transitionMessage = "Right, so... opening the proper chat interface now. Same me, just with actual conversation abilities. Bottom right corner.";
            await displayWheatley(transitionMessage);
        }
        
        // Chatling should be loaded and visible by now (script injected above, loaded during typewriter)
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
            state.huntWindowActive = true;
            return;
        }

        if (diff <= -CONFIG.windowDuration) {
            target.setDate(target.getDate() + 1);
            diff = (target - now) / 1000;
            els.huntEnterBtn.classList.remove('is-active');
            state.huntWindowActive = false;

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

    // ─── EVENT LISTENERS ────────────────────────────────────────
    
    // Hunt
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
    
    // Share
    if (els.shareBtn) {
        els.shareBtn.addEventListener('click', openShareModal);
    }
    
    if (els.shareClose) {
        els.shareClose.addEventListener('click', closeShareModal);
    }
    
    if (els.shareName) {
        els.shareName.addEventListener('input', updateSharePreview);
    }
    
    if (els.shareContext) {
        els.shareContext.addEventListener('input', function() {
            // Enforce max length
            if (this.value.length > CONFIG.contextMaxLength) {
                this.value = this.value.slice(0, CONFIG.contextMaxLength);
            }
            updateCharCount();
            updateSharePreview();
        });
    }
    
    if (els.shareGenerate) {
        els.shareGenerate.addEventListener('click', generateShareLink);
    }
    
    // Share modal keyboard shortcuts
    if (els.shareModal) {
        document.addEventListener('keydown', function(e) {
            if (state.shareModalOpen && e.key === 'Escape') {
                closeShareModal();
            }
            if (state.shareModalOpen && e.key === 'Enter' && e.metaKey) {
                // Cmd+Enter to generate
                generateShareLink();
            }
        });
    }

    // ─── INITIALIZE ─────────────────────────────────────────────
    function init() {
        // Check if we're on the landing page
        if (!els.phaseText) {
            console.log('Landing page elements not found, skipping landing-specific init');
            return;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);

        setInterval(checkIdleTime, 1000);

        document.addEventListener('mousemove', resetIdleTimer);
        document.addEventListener('keydown', resetIdleTimer);
        document.addEventListener('scroll', resetIdleTimer);
        document.addEventListener('click', resetIdleTimer);
        
        // Initialize share char count
        if (els.shareCharCount) {
            updateCharCount();
        }

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