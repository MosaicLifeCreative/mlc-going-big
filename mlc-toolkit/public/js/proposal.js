/**
 * MLC Proposal Frontend v3
 * PIN gate, interactive service selection, client notes, auto-save,
 * dynamic totals, accept button, Wheatley API calls with inline typewriter + cursor.
 */
(function () {
    'use strict';

    var config = window.mlcProposal || {};
    var validatedPin = '';

    // ─── SELECTION STATE ────────────────────────────────────
    var selections = {};
    var notes = {};
    var saveTimer = null;
    var isSaving = false;
    var wheatleyFired = { intro: false, total: false, accept: false };

    function initSelections() {
        var serviceData = config.serviceData || {};
        var savedSelections = config.clientSelections || {};
        var savedNotes = config.clientNotes || {};
        var hasSaved = savedSelections && Object.keys(savedSelections).length > 0;

        Object.keys(serviceData).forEach(function (slug) {
            selections[slug] = hasSaved ? !!savedSelections[slug] : true;
            notes[slug] = savedNotes[slug] || '';
        });
    }

    // ─── PIN GATE ───────────────────────────────────────────
    var gate     = document.getElementById('proposal-gate');
    var pinInput = document.getElementById('proposal-pin');
    var pinBtn   = document.getElementById('proposal-pin-submit');
    var pinError = document.getElementById('proposal-pin-error');
    var content  = document.getElementById('proposal-content');

    // Skip gate if already accepted
    if (config.status === 'accepted') {
        if (gate) gate.style.display = 'none';
        if (content) content.classList.remove('proposal-content--hidden');
        initSelections();
        triggerWheatley('intro');
        triggerWheatley('total');
        // Hide the post-accept Wheatley box (only relevant during live accept)
        var wheatleyAcceptBox = document.getElementById('wheatley-accept');
        if (wheatleyAcceptBox) wheatleyAcceptBox.style.display = 'none';
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
        gate.classList.add('proposal-gate--unlocked');

        setTimeout(function () {
            gate.style.display = 'none';
            content.classList.remove('proposal-content--hidden');

            initSelections();
            bindServiceInteractions();
            triggerWheatley('intro');
            observeTotalSection();
        }, 600);
    }

    // ─── SERVICE INTERACTIONS ───────────────────────────────

    function bindServiceInteractions() {
        // Checkbox toggles
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('proposal-service__checkbox')) return;

            var slug = e.target.dataset.slug;
            var card = e.target.closest('.proposal-service');
            selections[slug] = e.target.checked;

            if (e.target.checked) {
                card.classList.remove('proposal-service--dimmed');
            } else {
                card.classList.add('proposal-service--dimmed');
            }

            recalcTotals();
            debounceSave();
        });

        // Note inputs
        document.addEventListener('input', function (e) {
            if (!e.target.classList.contains('proposal-service__note-input')) return;

            var slug = e.target.dataset.slug;
            notes[slug] = e.target.value;
            debounceSave();
        });
    }

    // ─── CLIENT-SIDE TOTAL RECALCULATION ────────────────────

    function getSelectedTotals() {
        var serviceData = config.serviceData || {};
        var customItems = config.customItems || [];
        var totals = { one_time: 0, monthly: 0, annual: 0, setup: 0 };

        Object.keys(serviceData).forEach(function (slug) {
            if (!selections[slug]) return;
            var svc = serviceData[slug];
            var amount = parseFloat((svc.price || '0').replace(/[^0-9.]/g, '')) || 0;
            var type = svc.price_type || 'monthly';

            if (type === 'one-time') totals.one_time += amount;
            else if (type === 'annual') totals.annual += amount;
            else totals.monthly += amount;

            var setupAmt = parseFloat((svc.setup_price || '0').replace(/[^0-9.]/g, '')) || 0;
            if (setupAmt > 0) totals.setup += setupAmt;
        });

        customItems.forEach(function (item) {
            var amount = parseFloat((item.price || '0').replace(/[^0-9.]/g, '')) || 0;
            var type = item.price_type || 'one-time';
            if (type === 'one-time') totals.one_time += amount;
            else if (type === 'annual') totals.annual += amount;
            else totals.monthly += amount;
        });

        return totals;
    }

    function recalcTotals() {
        var totals = getSelectedTotals();
        updateRow('summary-setup', totals.setup, '');
        updateRow('summary-onetime', totals.one_time, '');
        updateRow('summary-monthly', totals.monthly, '/mo');
        updateRow('summary-annual', totals.annual, '/yr');
    }

    function updateRow(id, amount, suffix) {
        var row = document.getElementById(id);
        if (!row) return;
        if (amount > 0) {
            row.style.display = '';
            var strong = row.querySelector('strong');
            if (strong) {
                strong.textContent = '$' + amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + suffix;
            }
        } else {
            row.style.display = 'none';
        }
    }

    // ─── DEBOUNCED AUTO-SAVE ────────────────────────────────

    function debounceSave() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            saveSelections();
        }, 1500);
    }

    function saveSelections() {
        if (isSaving || !validatedPin) return;
        isSaving = true;
        showSaveIndicator('saving');

        fetch(config.ajaxUrl + 'proposal/save-selections', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                proposal_id: config.proposalId,
                pin: validatedPin,
                selections: selections,
                notes: notes
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            isSaving = false;
            if (data.success) {
                showSaveIndicator('saved');
            } else {
                showSaveIndicator('error');
            }
        })
        .catch(function () {
            isSaving = false;
            showSaveIndicator('error');
        });
    }

    function showSaveIndicator(state) {
        var el = document.getElementById('proposal-save-indicator');
        if (!el) return;

        if (state === 'saving') {
            el.textContent = 'Saving\u2026';
            el.className = 'proposal-save-indicator proposal-save-indicator--saving';
            el.style.opacity = '1';
        } else if (state === 'saved') {
            el.textContent = 'Saved';
            el.className = 'proposal-save-indicator proposal-save-indicator--saved';
            el.style.opacity = '1';
            setTimeout(function () { el.style.opacity = '0'; }, 2000);
        } else {
            el.textContent = 'Save failed';
            el.className = 'proposal-save-indicator proposal-save-indicator--error';
            el.style.opacity = '1';
            setTimeout(function () { el.style.opacity = '0'; }, 3000);
        }
    }

    // ─── ACCEPT BUTTON ─────────────────────────────────────

    var acceptBtn = document.getElementById('proposal-accept-btn');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function () {
            var generalNote = '';
            var gnEl = document.getElementById('proposal-general-note');
            if (gnEl) generalNote = gnEl.value || '';

            if (!confirm('Send your selections and notes to Mosaic Life Creative? Trey will follow up to discuss next steps.')) {
                return;
            }

            acceptBtn.disabled = true;
            acceptBtn.textContent = 'Sending\u2026';

            fetch(config.ajaxUrl + 'proposal/accept', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    proposal_id: config.proposalId,
                    pin: validatedPin,
                    selections: selections,
                    notes: notes,
                    general_note: generalNote
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    var section = document.getElementById('proposal-accept-section');
                    section.innerHTML =
                        '<div class="proposal-accepted__badge">Interested</div>' +
                        '<p>Thank you. Trey will be in touch shortly to discuss next steps.</p>';
                    section.className = 'proposal-summary__accepted';

                    // Disable all checkboxes and note inputs
                    var checkboxes = document.querySelectorAll('.proposal-service__checkbox');
                    checkboxes.forEach(function (cb) { cb.disabled = true; });
                    var noteInputs = document.querySelectorAll('.proposal-service__note-input');
                    noteInputs.forEach(function (n) { n.readOnly = true; });

                    var wheatleyAccept = document.getElementById('wheatley-accept');
                    if (wheatleyAccept) {
                        wheatleyAccept.style.display = 'block';
                        triggerWheatley('accept');
                    }
                } else {
                    acceptBtn.disabled = false;
                    acceptBtn.textContent = 'I\u2019m Interested. Let\u2019s Talk.';
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(function () {
                acceptBtn.disabled = false;
                acceptBtn.textContent = 'I\u2019m Interested. Let\u2019s Talk.';
                alert('Something went wrong. Please try again.');
            });
        });
    }

    // ─── WHEATLEY COMMENTARY ────────────────────────────────

    function triggerWheatley(position) {
        if (wheatleyFired[position]) return;
        wheatleyFired[position] = true;

        if (!config.wheatleyUrl) return;

        // Build selected service names
        var serviceData = config.serviceData || {};
        var selectedNames = [];
        var deselectedCount = 0;
        Object.keys(serviceData).forEach(function (slug) {
            if (selections[slug]) {
                selectedNames.push(serviceData[slug].name);
            } else {
                deselectedCount++;
            }
        });

        var selectedTotals = getSelectedTotals();

        fetch(config.wheatleyUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                position: position,
                client_name: config.clientName || '',
                services: selectedNames.join(', '),
                total_onetime: selectedTotals.one_time,
                total_monthly: selectedTotals.monthly,
                total_annual: selectedTotals.annual,
                total_setup: selectedTotals.setup,
                deselected_count: deselectedCount
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

        var cursor = document.createElement('span');
        cursor.className = 'proposal-wheatley__cursor';
        textEl.textContent = '';
        textEl.appendChild(cursor);

        var i = 0;

        function type() {
            if (i < text.length) {
                cursor.insertAdjacentText('beforebegin', text.charAt(i));
                i++;
                setTimeout(type, 28);
            }
        }

        type();
    }

    // ─── TOTAL SECTION OBSERVER ─────────────────────────────
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
