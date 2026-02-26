/**
 * MLC Proposal Frontend v2
 * PIN gate, accept button, Wheatley API calls with inline typewriter + cursor.
 */
(function () {
    'use strict';

    var config = window.mlcProposal || {};
    var validatedPin = '';

    // ─── PIN GATE ────────────────────────────────────────────
    var gate     = document.getElementById('proposal-gate');
    var pinInput = document.getElementById('proposal-pin');
    var pinBtn   = document.getElementById('proposal-pin-submit');
    var pinError = document.getElementById('proposal-pin-error');
    var content  = document.getElementById('proposal-content');

    // Skip gate if already accepted
    if (config.status === 'accepted') {
        if (gate) gate.style.display = 'none';
        if (content) content.classList.remove('proposal-content--hidden');
        triggerWheatley('intro');
        return;
    }

    // Skip gate if expired
    if (config.isExpired) return;

    if (pinBtn) {
        pinBtn.addEventListener('click', validatePin);
    }

    if (pinInput) {
        pinInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validatePin();
            }
        });
        pinInput.focus();
    }

    function validatePin() {
        var pin = (pinInput.value || '').trim();
        if (!pin) {
            showError('Please enter your PIN.');
            return;
        }

        pinBtn.disabled = true;
        pinBtn.textContent = 'Checking...';

        fetch(config.ajaxUrl + 'proposal/validate-pin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                proposal_id: config.proposalId,
                pin: pin
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                validatedPin = pin;
                unlockProposal();
            } else if (data.error === 'expired') {
                showError('This proposal has expired. Please contact us for an updated proposal.');
            } else {
                showError('Incorrect PIN. Please try again.');
                pinBtn.disabled = false;
                pinBtn.textContent = 'View Proposal';
                pinInput.value = '';
                pinInput.focus();
            }
        })
        .catch(function () {
            showError('Something went wrong. Please try again.');
            pinBtn.disabled = false;
            pinBtn.textContent = 'View Proposal';
        });
    }

    function showError(msg) {
        if (pinError) {
            pinError.textContent = msg;
            pinError.style.display = 'block';
        }
    }

    function unlockProposal() {
        // Animate gate away
        gate.classList.add('proposal-gate--unlocked');

        setTimeout(function () {
            gate.style.display = 'none';
            content.classList.remove('proposal-content--hidden');

            // Trigger Wheatley intro
            triggerWheatley('intro');

            // Set up total observer
            observeTotalSection();
        }, 600);
    }

    // ─── ACCEPT BUTTON ───────────────────────────────────────
    var acceptBtn = document.getElementById('proposal-accept-btn');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function () {
            if (!confirm('Accept this proposal? This will notify Mosaic Life Creative that you\'re ready to move forward.')) {
                return;
            }

            acceptBtn.disabled = true;
            acceptBtn.textContent = 'Accepting...';

            fetch(config.ajaxUrl + 'proposal/accept', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    proposal_id: config.proposalId,
                    pin: validatedPin
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    // Replace accept section with confirmation
                    var section = document.getElementById('proposal-accept-section');
                    section.innerHTML =
                        '<div class="proposal-accepted__badge">Accepted</div>' +
                        '<p>Thank you. Trey will be in touch shortly.</p>';
                    section.className = 'proposal-summary__accepted';

                    // Show Wheatley post-accept
                    var wheatleyAccept = document.getElementById('wheatley-accept');
                    if (wheatleyAccept) {
                        wheatleyAccept.style.display = 'block';
                        triggerWheatley('accept');
                    }
                } else {
                    acceptBtn.disabled = false;
                    acceptBtn.textContent = 'Accept Proposal';
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(function () {
                acceptBtn.disabled = false;
                acceptBtn.textContent = 'Accept Proposal';
                alert('Something went wrong. Please try again.');
            });
        });
    }

    // ─── WHEATLEY COMMENTARY ─────────────────────────────────
    var wheatleyFired = { intro: false, total: false, accept: false };

    function triggerWheatley(position) {
        if (wheatleyFired[position]) return;
        wheatleyFired[position] = true;

        if (!config.wheatleyUrl) return;

        // Build service names for context
        var serviceNames = (config.services || []).join(', ');

        fetch(config.wheatleyUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                position: position,
                client_name: config.clientName || '',
                services: serviceNames,
                total_onetime: config.totalOnce || 0,
                total_monthly: config.totalMonthly || 0,
                total_annual: config.totalAnnual || 0,
                total_setup: config.totalSetup || 0
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.message) {
                typewriterEffect(position, data.message);
            }
        })
        .catch(function () {
            // Silent fail. Wheatley commentary is enhancement, not critical.
        });
    }

    function typewriterEffect(position, text) {
        var textEl = document.getElementById('wheatley-' + position + '-text');
        if (!textEl) return;

        // Create thin bar cursor inline within the text element (matches site cursor)
        var cursor = document.createElement('span');
        cursor.className = 'proposal-wheatley__cursor';
        textEl.textContent = '';
        textEl.appendChild(cursor);

        var i = 0;

        function type() {
            if (i < text.length) {
                // Insert character before the cursor
                cursor.insertAdjacentText('beforebegin', text.charAt(i));
                i++;
                setTimeout(type, 28);
            }
        }

        type();
    }

    // ─── TOTAL SECTION OBSERVER ──────────────────────────────
    function observeTotalSection() {
        var totalSection = document.getElementById('wheatley-total');
        if (!totalSection) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    triggerWheatley('total');
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        observer.observe(totalSection);
    }

})();
