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

        // Countdown target time
        targetHour: 15,
        targetMin: 16,
        targetSec: 23,
        windowDuration: 42
    };

    // ─── UTILITY: URL PERSONALIZATION DECODER ──────────────────
    function getPersonalizationFromURL() {
        const params = new URLSearchParams(window.location.search);
        const encoded = params.get('u');
        
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
        userContext: null
    };

    // Get personalization on page load
    const personalization = getPersonalizationFromURL();
    state.userName = personalization.name;
    state.userContext = personalization.context;

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
        huntSubmit: $('#huntSubmit')
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
        
        if (state.currentPhase !== 4) {
            state.currentPhase = 4;
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