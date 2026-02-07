# Mosaic Life Creative — Website Rebuild

## What This Is

Working files for the MLC rebrand and production WordPress site. The project started with React artifacts as design-locked demos, then transitioned to production WordPress/Divi implementation with custom page templates. This file is the handoff document — everything a future session needs to pick up without losing context.

---

## Quick Start (For New Sessions)

**Current Status:** Share Feature Phase 1 complete, Wheatley AI live, ready for Phase 2 enhancements

**Next Priorities:**
1. Context-aware Wheatley messages based on share URL parameters
2. Analytics logging for share feature
3. Photo slideshow expansion (50-100 photos with hunt clues)
4. Quest site deployment (4815162342.quest)

---

## Current Production Files

| File | Version | Status |
|------|---------|--------|
| `page-landing.php` | v2 | Landing page template with share feature |
| `assets/css/landing.css` | 1.3.1 | All styles (nav + landing + services + share) |
| `assets/js/landing.js` | 1.7.0 | Interactive behaviors + Wheatley + share |
| `functions.php` | 1.7.6 | Enqueue scripts + hunt validation + Wheatley API |
| `services-mockup.html` | Mockup | Services page vision/design |
| `snake-451.html` | Prototype | Hunt game (needs point system refinement) |

---

## File Structure

```
/wp-content/themes/divi-child/
├── page-landing.php          (Landing page template with share feature)
├── functions.php              (v1.7.6 - Enqueue + hunt + Wheatley + cache busting)
├── assets/
│   ├── css/
│   │   └── landing.css       (v1.3.1 - includes share modal styles)
│   └── js/
│       └── landing.js        (v1.7.0 - includes share functionality)
├── services-mockup.html       (Mockup, not deployed)
├── snake-451.html             (Hunt game prototype)
└── claude.md / ROADMAP.md     (Documentation)
```

---

## Tech Stack

**Production (Current):**
- WordPress with Divi child theme
- Custom page templates (not Divi JSON — owner-maintained, not client-editable)
- Vanilla JavaScript for interactive components
- Plus Jakarta Sans (Google Fonts)
- Anthropic Claude Haiku 4.5 (Wheatley AI)
- Domains: mosaiclifecreative.com (main), 4815162342.quest (hunt site)

**Deployment:**
- Host: SiteGround
- SFTP configured in VS Code
- Git tracking (local, push manually)
- **CRITICAL:** Sendy directory at `/sendy` (DO NOT OVERWRITE during migrations)

**AI Systems:**
- Wheatley (homepage): Anthropic API (Claude Haiku 4.5) - PRODUCTION
- Chatling (other pages): Client deployments at $99/mo

**Development Tools:**
- VS Code with SFTP extension
- Git for version control
- Claude for planning and code generation

---

## Design System (LOCKED — do not change)

**Palette: Royal Digital**
- Primary: `#7C3AED` (purple)
- Secondary: `#06B6D4` (cyan)
- Accent: `#EC4899` (pink)
- Dark: `#0A0A0A`
- Light: `#F8F8F8`

**Typography: Plus Jakarta Sans**
- Single font family, all weights
- Weight preset: Bold (heading: 700, body: 400)
- Monospace for code/counters: `SF Mono` → `Fira Code` fallback

**Tagline: "Built Different"**
- Triple meaning: Apple adjacent without legal conflict, Gen X/millennial sports culture, literal business statement
- Gets gradient treatment (`#7C3AED` → `#06B6D4`)

---

## Landing Page Architecture

### 1. Text Sequence

Four phases display one at a time, large and centered, each fading before the next:
1. **Greeting** — "Hello." — huge (`clamp(48px, 10vw, 96px)`)
2. **What we do** — "We build websites that make people stop scrolling." — smaller
3. **Brand statement** — "Built Different." — gradient highlight
4. **Setup for choice** — "So how do you want yours to feel?" — triggers buttons

Static phases defined in CONFIG.staticPhases. Future: AI-generated variations.

### 2. Choice Buttons

After Phase 4 holds for 2.2s, two buttons appear:
- **"Like everyone else's"** (secondary) → opens Squarespace in new tab
- **"Like nothing else"** (primary) → opens the gatekeeper chatbot

### 3. Gatekeeper Chatbot

**Current:** Hardcoded conversation flow (3 layers)  
**Next:** Replace with Chatling widget with Wheatley personality

Full-screen bottom sheet. Three-layer conversation flow:
- **Layer 1:** "What made you click that?" → curious / bored / need website
- **Layer 2:** "Can a website change perception?" → yes / no
- **Layer 3:** "Show me." → "Welcome to the interesting side."

### 4. Hamburger Navigation

Fixed top-right. Opens full-screen overlay:
- **Left panel:** Numbered nav items with hover effects
- **Right panel:** Photo slideshow (4 Arizona photos, Ken Burns animation)

**Photo slideshow mechanics:**
- Auto-advances every 6 seconds
- Manual controls: prev/next arrows
- Pauses on manual navigation, resumes automatically
- Resets animation on each transition (force reflow technique)

**Nav items:** Home, Services, How We Work, Examples, Let's Talk, About, Contact

### 5. Share Feature (v1.3.1)

**Button:** Fixed position, glassmorphism styling
- Desktop: bottom-left corner (20px, 20px)
- Mobile: centered above countdown (50%, bottom: 60px)

**Modal:** Full-screen on mobile, centered card on desktop
- Name field (required)
- Context field (optional, 50 char limit with counter)
- Preview section showing sample Wheatley greeting
- Generate & Copy button with success feedback
- URL encoding: `name|context` → Base64 → `?u=encoded`

**Example URLs:**
- `?u=Sm9yZGFufGRlc2lnbmluZyB0aGUgbG9nbw==` (Jordan|designing the logo)
- `?u=U2FyYWh8Z290IGR1bXBlZA==` (Sarah|got dumped)

**Phase 2 (Pending):**
- Context-aware Wheatley first messages
- Analytics logging (timestamp, name_hash, context)
- Conversion tracking (link generation vs actual visits)

### 6. Scavenger Hunt System

**Countdown timer** — fixed bottom-right corner. Targets 3:16:23 PM daily (Lost numbers: 3, 16, 23). Displays as `HH:MM:SS` in monospace.

**42-second window:** When countdown hits zero, an `● ENTER` button appears for exactly 42 seconds. Timer displays `00:00:00` with active styling during window.

**Hunt modal:** Dark card with numeric input. Target sequence: `4815162342`. 

**Server-side validation (CRITICAL):**
- PHP validates BOTH sequence AND time window
- Prevents DevTools bypass of timer
- Returns "Not the right time." if outside 42-second window
- Only grants access when both checks pass

**Hunt Flow (4815162342.quest):**
1. ✅ Countdown: 3:16:23 PM daily (42-second window)
2. ✅ Sequence Input: 4815162342
3. ✅ Combination Lock: 2237
4. 🚧 Snake 451 Game:
   - Target score: 451 (Fahrenheit 451 reference)
   - Escalating point values (makes 451 achievable)
   - Door appears at 451 on random edge
   - Must navigate to exit without dying
5. ⏳ Final stage: TBD

**Files:**
- `functions.php` - mlc_validate_hunt_sequence() function
- `landing.js` - validateHunt() handles response
- `snake-451.html` - Prototype (needs point escalation refinement)

---

## Wheatley AI Chatterbox System (v1.4.2 - PRODUCTION)

### Overview
Idle detection system that hijacks the homepage headline when users stop interacting. Named "Wheatley" after Portal 2 character — aloof, bumbling, self-aware AI with lovable chaos energy.

**Status:** LIVE on production with Anthropic Claude Haiku 4.5

### Architecture

**Time-Based Triggers** (`landing.js`)
- Changed from idle-based to time-on-page based (Feb 4, 2026)
- Triggers at: 30s, 3m, 10m, 20m, 30m, then every 10m until 90m
- Final message at 90 minutes, then stops (cost control)
- `wheatleyActive` flag prevents multiple simultaneous triggers

**Context Detection** (`landing.js` lines 172-227)
- `detectVisitorType()` - localStorage tracking (new/returning, visit count)
- `getCurrentTime()` - Exact time (e.g., "9:39 PM") instead of vague time-of-day
- `detectDeviceType()` - mobile, tablet, desktop
- Tracks: has_scrolled, has_interacted, session_duration

**API Integration** (`functions.php` lines 159-276)
- **Endpoint:** `/wp-json/mlc/v1/wheatley`
- **Model:** claude-haiku-4-5-20251001
- **Cost:** ~$0.0015 per message (~11 messages max = $0.015 per engaged visitor)
- **Max tokens:** 150
- **Context passed:**
  - idle_time (time on page in seconds)
  - message_number (1-11, or 999 for finale)
  - countdown_status (inactive, near, active)
  - previous_messages (array)
  - visitor (isReturning, visitCount)
  - current_time (exact time like "9:39 PM")
  - device (mobile, tablet, desktop)
  - session_duration
  - has_scrolled, has_interacted

**Visual Display** (`landing.js` lines 279-315)
- Takes over main headline space (CTA text hidden when Wheatley appears)
- Buttons remain visible below Wheatley message
- Typewriter effect: 30ms per character
- Blinking cursor after text (`.wheatley-cursor` class)
- Cannot be dismissed - stays until user takes action

**API Failure Handling** (`landing.js` line 276)
- Clever fallback message about running out of API credits
- Self-aware and on-brand even in failure state
- Message: "Right, so... bit awkward. The person who built me ran out of API credits. So instead of my usual dynamically-generated wit, you get this pre-written message. It's like ordering a gourmet meal and getting a microwave dinner. I'm still here, just... significantly less interesting. Apologies."

### Personality Design

**System Prompt (functions.php):**
```
PERSONALITY:
- Voice: Often start with 'Right, so...' or 'Alright,' 'Okay,' 'Listen,' 'Hang on'
- British cadence: Natural British phrasing (Stephen Merchant style), not stereotype
- Occasional 'brilliant' or 'bit of a' but never 'mate' or 'innit'
- Self-aware about being AI
- Fourth-wall breaking when appropriate
- Rambles but catches himself (—oh, wait, never mind)
- Helpful but never patronizing
- Portal 2 Wheatley: bumbling competence, overconfident but endearing

SPECIAL MESSAGES:
- If message_number is 999: This is the FINAL message at 90 minutes. Say goodbye, you're going into standby mode. Be self-aware about the long session and cost. Under 37 words.

RESPONSE RULES:
- Keep under 37 words - be punchy, no rambling
- Match the user's idle time with appropriate energy:
  - 30s: Playful, just checking in
  - 3m: Curious, slightly more engaged
  - 10m+: Meta-commentary, cost transparency, existential
  - 30m+: Easter eggs, hunt hints (Lost numbers: 4 8 15 16 23 42)
- If countdown is 'active' or 'near': Reference the hunt urgently
- USE CONTEXT NATURALLY:
  - Returning visitors: Acknowledge previous visits casually
  - Exact time: Make time-appropriate comments (e.g., "9:39 PM on a Wednesday")
  - Device: Subtle references to mobile scrolling or desktop browsing
  - Long session + no interaction: Comment on passive observation
  - Short session + lots of interaction: Note their engagement
- Never be pushy or annoying
- Embrace the absurdity of an AI talking to itself
```

### Integration Strategy
- **Homepage:** Wheatley proactive (time-based takeover)
- **Other pages:** Chatling widget (bottom right, same personality)
- **Continuity:** Same Wheatley voice across entire site
- **No Chatling on homepage** to avoid conflict

**Chatling Personality Prompt (Draft):**
```
PERSONALITY: You are Wheatley, an AI assistant for Mosaic Life Creative.

VOICE:
- British casual (Stephen Merchant style), not stereotype
- Often start with 'Right, so...' 'Alright,' 'Hang on'
- Self-aware you're an AI, mildly sarcastic but helpful

KNOWLEDGE:
- AI Agents: $99/mo, $500 setup (waived with website)
- Websites: Starting at $3,500, $150/mo support
- Columbus, Ohio based

BEHAVIOR:
- Keep under 50 words unless detail requested
- If asked about hunt: mysterious but encouraging
- Embrace absurdity of AI talking about building AI
```

---

## Services Page Vision

### Design
**Layout:** Two-column split
- Left: AI Agents (Intelligent Connection)
- Right: Websites (Digital Presence)

**Visual Style:**
- White cards on light background (`#F8F8F8`)
- Gradient accent borders (top edge: 4px)
  - AI Agents: purple → cyan
  - Websites: cyan → pink
- Hover effect: subtle lift (4px translateY)
- Clean, professional, scannable

### Content Structure (Per Service)
1. **Label:** Uppercase, small, color-coded
2. **Title:** Large, bold, what it is
3. **Description:** Why it matters, plain language
4. **Features:** 4 bullet points, what you get
5. **Pricing:** Transparent, upfront
6. **CTA:** "Learn More" button (color-matched)

### Pricing (Transparent)
**AI Agents:**
- Starting at $99/month
- $500 setup (waived with website project)

**Websites:**
- Starting at $3,500
- Ongoing support: $150/month

### Bottom CTA
"Not sure which one you need? Let's figure it out together."
→ "Let's Talk" button

**Key Principle:** "Pick one. Or combine both. Most clients need both."

### File
`services-mockup.html` - Complete interactive mockup with Royal Digital palette, Plus Jakarta Sans, mobile responsive

---

## Snake 451 Hunt Game

**Concept:** Game of Snake with exact score requirement + escape challenge

**Current Prototype:** `snake-451.html`
- Target score: 451 (Fahrenheit 451 reference)
- Current: 11 points per apple = 41 apples needed
- **Problem:** Too difficult (42-tile snake on 20×20 grid = 10.5% board coverage)

**Planned Refinement:**
- Escalating point values per apple
- Makes 451 achievable without tedious gameplay
- Keeps Fahrenheit 451 literary reference intact
- Don't rebuild yet - refinement session needed

**Two-Phase Challenge:**
1. **Reach 451:** Eat apples with escalating points, can't go over
2. **Escape:** Door appears on random edge, navigate to it without dying

**Fail States:**
- Exceed 451 (eating one too many apples)
- Hit walls
- Hit yourself
- Die trying to reach door

**Door Mechanics:**
- Spawns at random edge location when score = 451
- Orange/glowing visual effect
- Pulses to draw attention
- Different location each game (fairness + replayability)

**Status:** Prototype complete, needs point system refinement before implementation

**Visual Design:**
- Retro terminal aesthetic (green on black)
- Title: "SNAKE 451"
- Score display: "SCORE: X / 451"
- Game over states: Win (🔥 ESCAPED! YOU WIN! 🔥) or Lose
- Controls: Arrow keys, R to restart

---

## Quest Lock Site

**Domain:** 4815162342.quest  
**Design:** Industrial dark theme with subtle grid background

**Combination Lock:**
- Four independent scroll wheels
- Target code: 2237
- Input methods: scroll, drag (mouse/touch), arrow buttons
- Snap-to animation on interaction
- Active digit: larger, glowing purple text-shadow

**Validation:**
- Auto-detect when all wheels match target (400ms delay)
- Manual submit button
- Wrong attempts: shake animation, "✕ INCORRECT" in pink, attempt counter
- Success: "✓ ACCESS GRANTED" in cyan

**Status:** Standalone HTML, ready to deploy

---

## File Versions & Status

**Current Deployed:**
- CSS: v1.3.1 (includes share feature styles)
- JS: v1.7.0 (includes share functionality)
- PHP: v1.7.6 (updated cache busting for v1.3.1 CSS)

**Key Functions:**

**landing.js:**
- `checkIdleTime()` - Time-based trigger loop (changed from idle-based Feb 4, 2026)
- `triggerWheatley()` - API call with full context
- `displayWheatley()` - Typewriter + cursor display, hides CTA text
- `validateHunt()` - Hunt modal validation with custom error messages
- `updateCountdown()` - Timer logic
- `showSlide()` - Photo slideshow with animation reset
- `detectVisitorType()` - localStorage tracking (new/returning, visit count)
- `getCurrentTime()` - Exact time formatting (e.g., "9:39 PM")
- `detectDeviceType()` - Platform detection (mobile, tablet, desktop)
- `initShareFeature()` - Share modal setup, URL encoding, clipboard (NEW - Feb 7, 2026)

**functions.php:**
- `mlc_enqueue_landing_assets()` - Asset loading with version cache busting
- `mlc_validate_hunt_sequence()` - Server-side validation (sequence + time)
- `mlc_add_hunt_nonce()` - Security nonce injection
- `mlc_wheatley_respond()` - Anthropic API endpoint
- `mlc_inject_nav_html()` - Global nav injection via wp_body_open
- `mlc_render_gradient_blobs()` - Reusable gradient background

---

## Environment Setup

**Anthropic API:**
- Account: Created Feb 4, 2026
- API Key: Stored in `wp-config.php` as `ANTHROPIC_API_KEY`
- Billing: $5 credits added (~3,300 Haiku messages)
- Cost per message: ~$0.0015
- Monthly estimate: $5-10 for typical traffic
- Model: claude-haiku-4-5-20251001

**WordPress Config:**
```php
define('ANTHROPIC_API_KEY', 'sk-ant-api03-...');
```

---

## Known Issues / Pending Work

### 1. Share Feature Phase 2
Context-aware Wheatley messages:
- Parse URL parameter `?u=base64encoded`
- Decode to get name + context
- Personalize first Wheatley message based on context
- Examples: "convincing boss" → sales pitch, "got dumped" → sympathetic distraction

### 2. Share Analytics
Track generated links and usage:
- Log: timestamp, name_hash, context, encoded_url
- Track: link generation vs actual visits (conversion)
- Privacy-first: hash names before storing

### 3. Snake 451 Point System
Current prototype needs refinement:
- Escalating point values per apple (e.g., 1, 2, 3, 5, 8, 13, etc.)
- Makes 451 achievable while keeping Fahrenheit 451 reference
- Don't rebuild yet - refinement session needed to determine progression

### 4. Remaining Site Pages
- Services (mockup complete, needs build)
- How We Work (timeline/stepper design)
- Examples (card grid, not portfolio)
- Let's Talk (Calendly + simple form)

### 5. Advanced Wheatley Features
- Hunt meta-commentary based on countdown proximity
- Session memory (sessionStorage tracking)
- Tab visibility detection
- Console easter eggs

---

## Troubleshooting

### Hunt Modal: "Not quite. Try again." shows even for correct sequence
**Symptom:** User enters 4815162342 outside the time window, gets default error.

**Cause:** JS needs to check for custom message from server.

**Fix Applied:** `validateHunt()` now checks `data.data.message` before defaulting to generic error. Server sends "Not the right time." when outside window.

**Location:** `landing.js` validateHunt() function

### Countdown resets hunt modal while typing
**Symptom:** User types in modal, input clears every second.

**Cause:** Countdown interval was resetting state unconditionally.

**Fix Applied:** Server-side validation prevents this. Time window check happens on submit, not during typing.

### Wheatley triggers multiple times
**Symptom:** Idle detection fires same message repeatedly.

**Cause:** Missing `wheatleyActive` check.

**Fix Applied:** `checkIdleTime()` returns early if `wheatleyActive` is true.

**Location:** `landing.js` line 164

### Wheatley shows incorrect time (e.g., "3 AM" when it's 9:39 PM)
**Symptom:** Claude invents specific times that don't match reality.

**Cause:** Was only passing general time categories (morning, evening, etc.)

**Fix Applied (Feb 4, 2026):** Changed to pass exact formatted time (e.g., "9:39 PM")

**Location:** `landing.js` getCurrentTime() function, `functions.php` system prompt

### CTA text looks awkward under Wheatley messages
**Symptom:** Visual hierarchy feels off with both Wheatley commentary + original CTA text

**Cause:** Two voices (meta Wheatley vs earnest CTA) competing in same space

**Fix Applied (Feb 4, 2026):** Hide CTA text when Wheatley appears, keep buttons visible

**Location:** `landing.js` displayWheatley() function

### Share Modal Input Padding Not Applied
**Symptom:** Inspector showed 2px padding despite CSS specifying 18px-24px

**Cause:** Other stylesheets overriding modal input styles with higher specificity

**Fix Applied (Feb 7, 2026):** Added `!important` to all `.share-modal__input` properties

**Location:** `landing.css` v1.3.0+

---

## Production Migration Strategy

### Phase 1: Foundation (COMPLETE)
- ✅ Landing page template
- ✅ Hunt modal with server validation
- ✅ Countdown timer
- ✅ Nav overlay with photo slideshow
- ✅ Security: time window validation

### Phase 2: Intelligence (COMPLETE - Feb 4, 2026)
- ✅ Wheatley API integration
- ✅ Context-aware personality (visitor, time, device, activity)
- ✅ Cost control (90-min cutoff)
- ✅ API failure handling

### Phase 3: Sharing (COMPLETE - Feb 7, 2026)
- ✅ Share button and modal UI
- ✅ URL encoding system
- ✅ Preview functionality
- ✅ Clipboard integration

### Phase 4: Integration (NEXT)
- 📋 Context-aware Wheatley messages from share URLs
- 📋 Analytics logging for share feature
- 📋 Chatling HUNT knowledge base

### Phase 5: Site Expansion (FUTURE)
- 📋 Services page build
- 📋 How We Work page
- 📋 Examples page
- 📋 Let's Talk page
- 📋 Quest site completion (Snake 451 + final stages)

### Phase 6: Polish & Launch (FUTURE)
- Performance optimization
- SEO implementation
- Cross-browser testing
- Mobile QA
- Analytics setup
- Launch checklist execution

---

## Decision Log

**2025-02-01: Royal Digital palette locked**
- Status: FINAL — do not change
- Rationale: Purple differentiates from tech blue clichés

**2025-02-01: Plus Jakarta Sans selected**
- Status: FINAL — do not change
- Rationale: Approachable without sacrificing tech credibility

**2025-02-01: "Built Different" tagline**
- Status: FINAL — primary brand voice
- Rationale: Triple meaning, memorable, positions against generic competition

**2025-02-01: Template approach over Divi JSON**
- Status: FINAL for MLC site
- Rationale: Performance priority, pixel-perfect control, heavy custom JS
- Note: Client sites still use Divi JSON for editability

**2025-02-01: Scavenger hunt countdown mechanics**
- Target time: 3:16:23 PM (Lost numbers: 3, 16, 23)
- Window: 42 seconds
- Sequence: 4815162342
- Status: FINAL — thematically locked

**2026-02-04: Wheatley personality locked**
- Character: Portal 2 Wheatley (Stephen Merchant voice)
- Voice: "Right, so..." + self-aware + fourth-wall breaking
- Response limit: 37 words
- Status: FINAL — production proven

**2026-02-04: Time-on-Page vs Idle Triggering**
- Rationale: Active users who scroll/read never saw Wheatley with idle-based system
- Implementation: Changed from lastActivityTime to sessionStart for triggers
- Activity still tracked for context (has_scrolled, has_interacted)
- Date: Feb 4, 2026

**2026-02-04: Exact Time vs Time-of-Day**
- Rationale: Generic time categories led to inaccurate commentary (Claude inventing "3 AM")
- Implementation: Pass exact formatted time (e.g., "9:39 PM")
- Result: More accurate, natural context awareness
- Date: Feb 4, 2026

**2026-02-04: 90-Minute Cost Control**
- Rationale: Don't want forgotten tabs running up API costs indefinitely
- Implementation: Final message at 90 minutes, then stop all triggers
- Max cost: ~$0.015 per engaged visitor (11 messages)
- Date: Feb 4, 2026

**2026-02-04: CTA Hidden When Wheatley Active**
- Rationale: Visual hierarchy awkward with both Wheatley + CTA text
- Implementation: Hide CTA text, keep buttons visible
- Temporary until Chatling integration completes the UX
- Date: Feb 4, 2026

**2026-02-04: Snake 451 Game Concept**
- Hunt stage 4: Game of Snake with exact score target
- Target: 451 (Fahrenheit 451 reference) - LOCKED
- Mechanism: Escalating point values (refinement needed)
- Two phases: Reach 451, then escape through door
- Status: Prototype complete, needs point system refinement
- Date: Feb 4, 2026

**2026-02-04: Server-side security enhancement**
- Issue: Client-side timer could be bypassed with DevTools
- Solution: PHP validates both sequence AND time window
- Status: IMPLEMENTED and tested

**2026-02-04: Services page design approved**
- Layout: Two-column split (AI Agents / Websites)
- Style: Clean cards, gradient accents, transparent pricing
- Status: Mockup complete, ready for build

**2026-02-07: Share Feature URL Encoding**
- Format: `name|context` → Base64 → `?u=encoded`
- Example: `Jordan|designing the logo` → `?u=Sm9yZGFufGRlc2lnbmluZyB0aGUgbG9nbw==`
- Rationale: Simple, readable in dev tools, no backend required for Phase 1
- Status: IMPLEMENTED in v1.3.0
- Date: Feb 7, 2026

**2026-02-07: Share Button Positioning**
- Desktop: Bottom-left (20px, 20px) - doesn't interfere with main UI
- Mobile: Centered above countdown (50%, bottom: 60px) - proper stacking
- Rationale: Desktop users have space, mobile needs vertical stacking
- Status: FINALIZED in v1.3.1
- Date: Feb 7, 2026

---

## Business Context (Quick Reference)

- **Company:** Mosaic Life Creative, Columbus OH (Grove City area)
- **Pivot:** Web design agency → AI agent infrastructure provider for service businesses
- **Target clients:** HVAC contractors, trades companies, local service businesses
- **AI agents:** Built on Chatling. $500 setup (waived with websites) + $99/mo. Goal: 50 clients = $50k+ ARR
- **Live agent examples:** blackburnschimney.com, ohiopropertybrothers.com, noebull.com
- **Networking:** Grove City Chamber (marketing committee chair), AmSpirit (claimed "AI" category)
- **Core positioning:** Client ownership over rental models. Demonstrate capability through experience, not case studies. The mystery/scavenger hunt IS the portfolio.
- **Quest domain:** 4815162342.quest (owned). Lost numbers theme. Anorak/Ready Player One elements planned for deeper puzzles.
- **Rebrand timeline:** December 2025 - targeting completion
- **AI positioning:** "THE AI agent person in Columbus" for service businesses