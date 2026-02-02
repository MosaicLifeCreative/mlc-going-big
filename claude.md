# Mosaic Life Creative — Website Rebuild

## What This Is

Working prototype files for the MLC rebrand. Two React artifacts exist as design-locked demos. The next phase is migrating them into a production WordPress/Divi environment. This file is the handoff document — everything a future session needs to pick up without losing context.

---

## Files

| File | Lines | Status |
|------|-------|--------|
| `mosaic_demo.jsx` | 1165 | Landing page — fully functional except nav photos (see Known Issues) |
| `quest_lock.jsx` | 483 | Quest site combination lock — fully functional |

---

## Tech Stack

**Development:**
- React (artifacts/demos only)
- Anthropic API (claude-sonnet-4-20250514) for AI text streaming
- Plus Jakarta Sans (Google Fonts)
- SF Mono / Fira Code (monospace fallback for counters/code)

**Production Target:**
- WordPress with Divi child theme
- Custom page templates (not Divi JSON — owner-maintained, not client-editable)
- Vanilla JS or bundled React for interactive components
- Domains: mosaiclifecreative.com (main), 4815162342.quest (hunt site)

**AI Agent Platform:**
- Chatling (client deployments at $99/mo)

**Development Tools:**
- VS Code with SFTP extension
- Folder Templates for project scaffolding
- Git for version control

---

## Design System (LOCKED — do not change)

**Palette: Royal Digital**
- Primary: `#7C3AED` (purple)
- Secondary: `#06B6D4` (cyan)
- Accent: `#EC4899` (pink)
- Dark: `#18181B`
- Light: `#FAFAFA`

**Typography: Plus Jakarta Sans**
- Single font family, all weights
- Weight preset in use: Bold (`heading: 700, body: 400, highlight: 700`)
- Monospace for code/counters: `SF Mono` → `Fira Code` fallback

**Tagline: "Built Different"**
- Triple meaning: Apple adjacent without legal conflict, Gen X/millennial sports culture, literal business statement
- Phase 3 in the text sequence — gets gradient treatment (`#7C3AED` → `#06B6D4`)

---

## Common Tasks

**Fix nav photos not loading:**
- File: `mosaic_demo.jsx` lines 445-449
- Issue: Facebook CDN blocked, need base64 or hosted URLs
- See: Known Issues #1

**Change button style:**
- File: `mosaic_demo.jsx` lines 378-411 (button style functions)
- Decision needed — four options in demo, none selected
- Current options: Current (gradient float), Squishy (mechanical press), Soft UI (neumorphism), Raised (physical depth)

**Adjust AI text generation:**
- File: `mosaic_demo.jsx` lines 149-224 (generatePhases function)
- Prompt engineering: lines 152-169
- Visitor scenarios: lines 68-99
- Model: claude-sonnet-4-20250514

**Modify countdown target time:**
- File: `mosaic_demo.jsx` line 471 (setHours call)
- Current: 3:16:23 PM (Lost numbers: 3, 16, 23)
- Window duration: 42 seconds (line 475)

**Change quest lock code:**
- File: `quest_lock.jsx` line 6 (TARGET array)
- Current: [2, 2, 3, 7]

**Modify chatbot conversation flow:**
- File: `mosaic_demo.jsx` lines 104-144 (CHAT_FLOWS object)
- Three-layer structure: opener → deeper → reward

**Update hamburger navigation menu:**
- File: `mosaic_demo.jsx` lines 452-461 (NAV_PAGES array)
- Animation code: lines 669-730 (hamburger), 733-849 (nav overlay)

---

## mosaic_demo.jsx — Architecture

### 1. Text Sequence (AI-Driven)

Four phases display one at a time, large and centered, each fading before the next. The visual structure (sizes, timing, tracking) is fixed in `PHASE_TEMPLATE`. The AI fills in the text.

**Phase structure:**
1. Greeting — 1-3 words, huge (`clamp(48px, 10vw, 96px)`), heading weight
2. What we do — one sentence, smaller (`clamp(26px, 4.5vw, 48px)`), body weight
3. Brand statement — 2-4 words, large (`clamp(40px, 8vw, 80px)`), gradient highlight treatment
4. Setup for choice — under 15 words, stays on screen, triggers the two buttons

**AI streaming:** Calls `claude-sonnet-4-20250514` with visitor context, streams SSE, splits on `|||` delimiter. Five visitor scenario presets provide different context to the model (Chamber referral, direct returning, Google evening, AmSpirit, Instagram curious). A blinking cursor tracks the actively-streaming phase. Falls back to static text if AI is off or errors.

**State machine:** `phase` + `seqState` (showing/hiding) drive transitions. Each phase waits for its `.text` to be non-empty before advancing — this is how it syncs with streaming. The 60ms gap on transition (`setTimeout(() => setSeqState("showing"), 60)`) prevents React from collapsing the hide→show into one render.

### 2. Choice Buttons

After Phase 4 holds for 2.2s, two buttons appear:
- **"Like everyone else's"** (secondary) → opens Squarespace in new tab
- **"Like nothing else"** (primary) → opens the gatekeeper chatbot

Button styles are switchable in the demo via accordion control. Four styles exist: Current (gradient float), Squishy (mechanical press with recessed housing), Soft UI (neumorphism), Raised (physical depth). **No style has been selected as final yet.**

The Squishy style wraps each button in a recessed housing div (inset shadows, background `#d4d4d8`) so the button physically drops into it on press.

### 3. Gatekeeper Chatbot

Full-screen bottom sheet. Three-layer conversation flow, all scripted (no AI in the bot itself — the bot's job is to feel out the visitor, not generate text):

- **Layer 1 (opener):** "Interesting choice. Tell me something — what made you click that?" → three options: curious / bored / need a website
- **Layer 2 (deeper):** Each path leads to a question about whether a website can change perception → yes / no
- **Layer 3 (reward):** Both paths converge → "Show me." → "Welcome to the interesting side."

After reward, a card appears explaining that production branches here into adventure/services/mystery. Typing indicator with staggered bouncing dots between each bot message.

### 4. Hamburger Navigation

Fixed top-right. Frosted glass pill with:
- Spinning conic-gradient border (`#7C3AED` → `#06B6D4` → `#EC4899`), 4s loop, accelerates to 1.2s on hover
- Magnetic hover: lines follow cursor ±6px via imperative `mousemove` listener
- Line morph: on hover, three lines animate toward an X (top/bottom rotate ±45°, middle scales to 0)
- Breathing pulse on the inner glass layer

Opens a full-screen nav overlay:
- **Left panel (55%):** Numbered nav items (01–08), jumbo text (`clamp(32px, 5vw, 52px)`), weight shifts 300→700 on hover, color shifts `#9a9aa8`→`#fff`, staggered entrance animation
- **Right panel:** Photo panel with left-edge gradient blend into nav. Currently swaps photos on hover (see Known Issues — this should be single-random-photo-per-open)

**Nav pages:** Home, Website Design, Hosting, Maintenance, Email Marketing, AI Chat Agents, About, Contact

### 5. Scavenger Hunt System

**Countdown timer** — fixed bottom-right corner. Targets 3:16:23 PM daily (Lost numbers: 3, 16, 23). Displays as `HH:MM:SS` in dim monospace. Turns pink when the window opens.

**42-second window:** When countdown hits zero, an `● ENTER` button appears for exactly 42 seconds. The timer displays `00:00:00` in pink during this window. After 42s, button disappears and timer resets to next day's target.

**Hunt modal:** Dark card (`#12121a`) with backdrop blur. Numeric input, monospace, letter-spacing. Target sequence: `4815162342`. On wrong entry: pink border flash + "Incorrect." + shake animation on the card. On correct: "ACCESS GRANTED" in cyan → "Redirecting to 4815162342.quest..."

**Bug that was fixed:** The countdown `setInterval` closure was resetting `huntInput` and `huntStatus` every second, clearing the modal while the user was typing. Fix: `modalOpenRef` (a ref synced to `showHuntModal` state) is checked before clearing hunt state. The countdown only clears hunt state when the modal is closed.

**Debug shortcut:** `◇ Hunt Modal` button in controls panel force-opens the modal regardless of countdown state.

### 6. Controls Panel (Demo Only)

Accordion panels in top-left, frosted glass. Not for production — these are design exploration tools:
- **AI Text accordion:** Toggle AI on/off, select visitor scenario, regenerate button. Shows streaming status.
- **Button Style accordion:** Switch between the four button styles.
- **Debug row:** Hunt modal force-open, skip intro shortcut.

---

## quest_lock.jsx — Architecture

Standalone dark-theme page for `4815162342.quest`. Industrial aesthetic with subtle grid background and radial vignette.

### Combination Lock

Four independent scroll wheels. Target code: `2237`.

**Each wheel (`Wheel` component):**
- Displays 5 digits (2 above active, active center, 2 below)
- Active digit: larger (28px vs 22px), bolder (700 vs 400), full opacity, subtle purple text-shadow glow
- Inactive digits fade with distance (opacity drops 0.35 per step)
- Selection lines: horizontal gradient lines above and below the active slot (`#7C3AED` at 44%–88% opacity, transparent edges)
- Center glow: subtle purple radial behind active digit

**Input methods:**
- Scroll wheel (with `snapTo` animation — ease-out cubic, 180ms)
- Mouse drag (vertical, threshold-based delta)
- Touch drag (same logic, `preventDefault` on `touchmove`)
- Up/down arrow buttons above and below each wheel

**Auto-detect:** If all four wheels match `TARGET`, success triggers after 400ms delay (so user sees the digit land). Submit button also validates manually.

**Wrong attempts:** Shake animation on the lock housing, "✕ INCORRECT" in pink, attempt counter shown. No lockout — attempts are tracked but don't block further tries. (The summary mentioned showing a hint after 3 wrong attempts — this is NOT implemented in the current file. Could be added.)

**Success state:** "✓ ACCESS GRANTED" in cyan, submit button replaced with "Proceeding..." text.

---

## Known Issues / Pending Work

### 1. Nav Photos Don't Load (mosaic_demo.jsx)
The `NAV_PHOTOS` array contains Facebook CDN URLs. These are blocked by the artifact sandbox's CSP. The photos need to be converted to base64 data URIs, or (in production) hosted on MLC's own server. This was the task in progress when the session broke.

The four photo URLs are in lines 445–449 of `mosaic_demo.jsx`. They are real MLC photos (Trey's own Facebook uploads).

### 2. Nav Photo Behavior Should Change
Current behavior: photo panel swaps on hover (each nav item maps to a photo via `hoveredNav % NAV_PHOTOS.length`). When nothing is hovered, Photo 01 shows at 40% opacity.

**Intended behavior:** Pick one random photo when the nav overlay opens. Show it the entire time the nav is open. Don't swap on hover. The photo choice should feel curated, not mechanical.

### 3. Hint System Not Implemented (quest_lock.jsx)
The design called for showing a hint after 3 wrong attempts instead of a hard lockout. The attempt counter exists (`attempts` state) but no hint logic is wired. This needs a hint message and trigger condition.

### 4. Button Style Not Finalized
Four styles exist in the demo. No final selection has been made. This decision should happen before production build.

### 5. Production Migration Not Started
Both files are React artifacts. The production target is WordPress/Divi. The landing page interactive elements (orbs, text sequence, chatbot, countdown) will need to be built as a custom WordPress page with vanilla JS or a bundled React component injected into the page. The quest site (`4815162342.quest`) is a standalone site and could ship as-is with minor adjustments.

---

## Troubleshooting

### Countdown resets hunt modal input while typing
**Symptom:** User types in the hunt modal, but the input field clears itself every second.

**Cause:** The countdown `setInterval` closure captures stale state. Every tick, it calls `setHuntInput("")` and `setHuntStatus("idle")` unconditionally.

**Fix:** Created `modalOpenRef` (a ref) synced to `showHuntModal` state. The countdown checks `modalOpenRef.current` before clearing hunt state — only clears when modal is closed.

**Location:** `mosaic_demo.jsx` lines 441 (ref declaration), 464 (sync effect), 488 (check before clear)

### Text sequence phases flash/skip
**Symptom:** When one phase finishes and the next appears, they both render in the same frame, causing a flash or skip.

**Cause:** React batches the hide→show state updates into one render when they happen immediately.

**Fix:** Added 60ms `setTimeout` between setting `seqState` from "hiding" to "showing". This forces React to render the hide state first, then the show state.

**Location:** `mosaic_demo.jsx` line 611

### AI streaming shows all phases at once
**Symptom:** Instead of displaying one phase at a time, all four phases appear simultaneously when AI text arrives.

**Cause:** The state machine was advancing phases before their text was populated. Empty phases were being shown.

**Fix:** Each phase now waits for non-empty `.text` before advancing. The state machine checks `if (!current?.text) return;` to block progression until AI fills the phase.

**Location:** `mosaic_demo.jsx` line 593

### Nav photos don't load in artifact
**Symptom:** Photo panel in navigation shows broken images.

**Cause:** Facebook CDN URLs are blocked by Content Security Policy in the artifact sandbox.

**Fix:** Not yet implemented. Need to convert to base64 data URIs or self-host the images.

**Location:** `mosaic_demo.jsx` lines 445-449 (NAV_PHOTOS array)

### Hamburger lines don't morph on hover
**Symptom:** The hamburger icon doesn't animate to an X when hovering.

**Cause:** Event listener tries to query `.hb-line` elements before they exist in the DOM, or CSS transitions aren't applied.

**Fix:** Use imperative event listeners (`onMouseEnter`/`onMouseLeave`) on the container, then query child elements. Ensure transition properties are set on the line elements.

**Location:** `mosaic_demo.jsx` lines 696-715 (hover handlers)

---

## Production Migration Strategy

### Development Workflow
```
Artifact (design decisions, visual verification)
    ↓
claude.md (this file — carries context between sessions)
    ↓
Code environment (persistent filesystem, production build)
    ↓
Production (WordPress/Divi for main site, standalone for quest)
```

### Phase 1: Photo Fix (Immediate)
**Goal:** Get nav photos loading in the artifact

**Tasks:**
- Fetch the four Facebook CDN URLs from lines 445-449
- Convert each to base64 data URI
- Replace URLs in `NAV_PHOTOS` array
- Verify images load in artifact
- Test file size impact (base64 increases size ~33%)

**Alternative:** Upload photos to MLC server, use direct URLs

**Deliverable:** Updated `mosaic_demo.jsx` with working photos

### Phase 2: Nav Behavior Change
**Goal:** Implement single-random-photo-per-open

**Tasks:**
- Add `useState` for selected photo index (initialized with random on nav open)
- Remove hover-based photo swapping logic
- Keep gradient overlay and caption structure
- Test that same photo persists while nav is open
- Verify new random photo on each subsequent open

**Deliverable:** Updated nav overlay behavior

### Phase 3: Template Creation
**Goal:** Port React demos to WordPress page templates

**File structure:**
```
wp-content/themes/divi-child/
├── page-landing.php          ← Custom landing page template
├── page-quest.php            ← Quest lock template (or standalone)
├── assets/
│   ├── js/
│   │   └── landing.js        ← All interactive behaviors
│   ├── css/
│   │   └── landing.css       ← Styles for landing page
│   └── fonts/                ← Plus Jakarta Sans (if self-hosted)
├── functions.php             ← Enqueue scripts/styles
└── style.css                 ← Child theme base
```

**Landing page template tasks:**
- Create minimal HTML structure (no Divi builder markup)
- Enqueue Plus Jakarta Sans via Google Fonts API
- Port gradient orbs to vanilla JS or leave as CSS animations
- Port text sequence state machine to vanilla JS
- Port chatbot interaction to vanilla JS
- Port hamburger nav and overlay to vanilla JS
- Port countdown timer and hunt modal to vanilla JS
- Bundle all JS with webpack or load as modules

**Quest template tasks:**
- Port `quest_lock.jsx` to standalone HTML file
- Self-contained CSS (no WordPress dependencies)
- Deploy to 4815162342.quest subdomain or separate hosting

### Phase 4: API Integration
**Goal:** Connect AI text generation to production

**Options:**

**Option A: Server-side pre-generation**
- Generate all 5 visitor scenarios on page load
- Cache in WordPress transients (1 hour)
- No client-side API calls
- Fast, predictable, no API key exposure

**Option B: Client-side streaming (current demo)**
- AJAX endpoint in WordPress (`wp-json` REST route)
- Proxy to Anthropic API
- API key stored in `wp-config.php` or environment variable
- Rate limiting per visitor IP
- Caching for repeat visitors

**Recommendation:** Option A for launch (simpler, faster). Option B for future iteration if real-time personalization is needed.

**Tasks:**
- Add Anthropic API key to `wp-config.php`
- Create REST endpoint: `/wp-json/mlc/v1/generate-text`
- Implement rate limiting (e.g., 10 requests per IP per hour)
- Add response caching in transients
- Update frontend JS to call WordPress endpoint instead of Anthropic directly

### Phase 5: Testing & Optimization
**Goal:** Production-ready performance and reliability

**Tasks:**
- Performance audit (Lighthouse, GTmetrix)
- Image optimization (photos, orb gradients)
- JS bundle size optimization
- Font loading strategy (preload, font-display: swap)
- Mobile testing (iOS Safari, Android Chrome)
- Cross-browser testing (Firefox, Edge)
- Countdown timer edge cases (midnight, DST changes)
- Hunt modal keyboard accessibility (Enter to submit, Esc to close)
- SEO: Meta tags, Open Graph, schema markup

**Performance targets:**
- Lighthouse Performance: 90+
- First Contentful Paint: <1.5s
- Time to Interactive: <3.5s
- Total bundle size: <200KB gzipped

### Phase 6: Deployment
**Goal:** Live site launch

**Pre-launch checklist:**
- [ ] Backup current site
- [ ] Test all interactive elements in staging
- [ ] Verify AI text generation works
- [ ] Verify countdown timer accuracy
- [ ] Verify hunt modal sequence validation
- [ ] Test chatbot conversation flows
- [ ] Verify hamburger nav on mobile
- [ ] Test nav photos load correctly
- [ ] SSL certificate valid for both domains
- [ ] Analytics tracking installed
- [ ] Error monitoring installed (e.g., Sentry)

**Launch sequence:**
1. Deploy to staging environment
2. Full QA pass
3. Deploy to production during low-traffic window
4. Monitor for errors in first 24 hours
5. Gather user feedback
6. Iterate

---

## Decision Log

**2025-02-01: Royal Digital palette locked**
- Alternatives considered: Deep Tech, Electric Minimalist
- Rationale: Purple differentiates from tech blue clichés, pink accent adds warmth
- Status: FINAL — do not change

**2025-02-01: Plus Jakarta Sans selected**
- Alternatives: Sora, Outfit, DM Sans
- Rationale: Approachable without sacrificing tech credibility, excellent weight range (200-800)
- Status: FINAL — do not change

**2025-02-01: "Built Different" tagline**
- Alternative: "Intelligent Connection" (relegated to internal value prop)
- Rationale: Triple meaning (Apple-adjacent, cultural weight, literal), memorable, positions against generic competition
- Status: FINAL — primary brand voice

**2025-02-01: Template approach over Divi JSON**
- Context: Owner-maintained site with complex interactions
- Rationale: Performance priority, pixel-perfect control, no need for visual builder, heavy custom JS
- Note: Client sites still use Divi JSON for editability
- Status: FINAL for MLC site

**2025-02-01: AI text streaming architecture**
- Model: claude-sonnet-4-20250514
- Five visitor scenarios with contextual prompts
- Four-phase structure with fixed visual design, AI-filled text
- Status: Proven in demo, needs production API integration decision (Option A vs B)

**2025-02-01: Scavenger hunt countdown mechanics**
- Target time: 3:16:23 PM (Lost numbers: 3, 16, 23)
- Window: 42 seconds
- Sequence: 4815162342
- Status: FINAL — these are thematically locked

**PENDING: Button style selection**
- Four options exist: Current, Squishy, Soft UI, Raised
- No selection made yet
- Decision needed before production build

**PENDING: Quest site hint system**
- Attempt counter exists
- Hint logic not implemented
- Decision needed: What hint to show? After how many attempts?

---

## Business Context (Quick Reference)

- **Company:** Mosaic Life Creative, Columbus OH (Grove City area)
- **Pivot:** Web design agency → AI agent infrastructure provider for service businesses
- **Target clients:** HVAC contractors, trades companies, local service businesses
- **AI agents:** Built on Chatling. $500 setup (waived with websites) + $99/mo. Goal: 50 clients = $50k+ ARR
- **Live agent examples:** blackburnschimney.com, ohiopropertybrothers.com, noebull.com
- **Networking:** Grove City Chamber (marketing committee chair), AmSpirit (claimed "AI" category)
- **Core positioning:** Client ownership over rental models. Demonstrate capability through experience, not case studies. The mystery/scavenger hunt IS the portfolio.
- **Quest domain:** 4815162342.quest (owned). Lost numbers theme. Anorak/Ready Player One elements planned for deeper puzzles (not yet built).
