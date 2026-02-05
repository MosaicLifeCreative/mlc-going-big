# Mosaic Life Creative — Website Rebuild

## What This Is

Working files for the MLC rebrand and production WordPress site. The project started with React artifacts as design-locked demos, then transitioned to production WordPress/Divi implementation with custom page templates. This file is the handoff document — everything a future session needs to pick up without losing context.

---

## Quick Start (For New Sessions)

**Current Priority:** Wheatley API integration (~40 min)

**Prep Required Before Starting:**
1. Anthropic Console account created
2. API key generated + saved securely
3. Billing/credits added to account

**Next Steps:**
1. Build PHP endpoint: `/wp-json/mlc/v1/wheatley`
2. Wire JS fetch in `landing.js`
3. Test with real API calls
4. Refine personality prompts
5. Update ROADMAP.md when complete

---

## Current Production Files

| File | Version | Status |
|------|---------|--------|
| `page-landing.php` | Production | Landing page template |
| `assets/css/landing.css` | 1.2.1 | Styles for landing page |
| `assets/js/landing.js` | 1.2.3 | Interactive behaviors + Wheatley |
| `functions.php` | Current | Enqueue scripts + hunt validation |
| `services-mockup.html` | Mockup | Services page vision/design |

---

## File Structure

```
/wp-content/themes/divi-child/
├── page-landing.php          (Landing page template)
├── functions.php              (Enqueue + hunt validation)
├── assets/
│   ├── css/
│   │   └── landing.css       (v1.2.1)
│   └── js/
│       └── landing.js        (v1.2.3)
├── services-mockup.html       (Mockup, not deployed)
└── claude.md / ROADMAP.md     (Documentation)
```

---

## Tech Stack

**Production (Current):**
- WordPress with Divi child theme
- Custom page templates (not Divi JSON — owner-maintained, not client-editable)
- Vanilla JavaScript for interactive components
- Plus Jakarta Sans (Google Fonts)
- Domains: mosaiclifecreative.com (main), 4815162342.quest (hunt site)

**Deployment:**
- Host: SiteGround
- SFTP configured in VS Code
- Git tracking (local, push manually)
- **CRITICAL:** Sendy directory at `/sendy` (DO NOT OVERWRITE during migrations)

**AI Systems:**
- Wheatley (homepage): Anthropic API integration in progress
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

### 5. Scavenger Hunt System

**Countdown timer** — fixed bottom-right corner. Targets 3:16:23 PM daily (Lost numbers: 3, 16, 23). Displays as `HH:MM:SS` in monospace.

**42-second window:** When countdown hits zero, an `● ENTER` button appears for exactly 42 seconds. Timer displays `00:00:00` with active styling during window.

**Hunt modal:** Dark card with numeric input. Target sequence: `4815162342`. 

**Server-side validation (CRITICAL):**
- PHP validates BOTH sequence AND time window
- Prevents DevTools bypass of timer
- Returns "Not the right time." if outside 42-second window
- Only grants access when both checks pass

**Files:**
- `functions.php` - mlc_validate_hunt_sequence() function
- `landing.js` - validateHunt() handles response

---

## Wheatley AI Chatterbox System

### Overview
Idle detection system that hijacks the homepage headline after user stops interacting. Named "Wheatley" after Portal 2 character — aloof, bumbling, self-aware AI with lovable chaos energy.

### Architecture

**Idle Detection** (`landing.js` lines 153-199)
- Tracks time since last user activity (mousemove, keydown, scroll, click)
- Triggers at progressive thresholds: 30s, 3m, 10m, 20m, 30m
- After 30m: continues every 10 minutes
- `wheatleyActive` flag prevents multiple simultaneous triggers

**Visual Display** (`landing.js` lines 201-226)
- Takes over main headline space (not corner bubble)
- Typewriter effect: 30ms per character
- Blinking cursor after text (`.wheatley-cursor` class)
- Cannot be dismissed - stays until user takes action

**Current Phase:** Hardcoded test messages in `CONFIG.wheatleyMessages`:
1. 30s: "Right, so... it's been 30 seconds. I'm an AI. I'm literally counting."
2. 3m: "Three minutes. You're committed now. I respect that. Or you forgot this tab was open..."
3. 10m: "Ten minutes. I cost fractions of a penny per message. This conversation has cost... about 1.5 cents. Worth it?"
4. 20m: "Twenty minutes. Are you real? I'm genuinely asking. Because I'm not, and this feels weird."
5. 30m: "Half an hour. Nobody stays this long. You've earned something. Here: 4 8 15 16 23 42. Does that mean anything to you?"

**Next Phase (In Progress):** API Integration
- Endpoint: `/wp-json/mlc/v1/wheatley`
- Model: Claude Haiku 4.5 (fast, cheap, perfect for personality)
- Cost: ~$0.0015 per message = $5-10/month
- Context passed: idle time, message number, countdown status, previous messages

### Personality Design
- Voice: "Right, so..." starter, first-person, mildly sarcastic
- Self-aware about being AI
- Fourth-wall breaking when appropriate
- Helpful but never patronizing
- Portal 2 Wheatley: bumbling competence, overconfident but endearing

### Meta-Commentary Layers (Planned)
1. **Self-awareness:** "I'm an AI. I'm literally counting."
2. **Website awareness:** "You know there's other websites, right?"
3. **Fourth wall:** Cost transparency, existential questions
4. **Hunt meta:** References countdown when close, urgent during 42-second window
5. **Session memory:** Remembers if user returns
6. **Tab visibility:** "You switched back. I was still here, you know."

### Integration Strategy
- **Homepage:** Wheatley proactive (idle takeover)
- **Other pages:** Chatling widget (bottom right, same personality)
- **Continuity:** Same Wheatley voice across entire site
- **No Chatling on homepage** to avoid conflict

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
- CSS: v1.2.1 (includes Wheatley cursor animation)
- JS: v1.2.3 (includes server-side time validation fix)
- PHP: Updated with time window validation

**Key Functions:**

**landing.js:**
- `checkIdleTime()` - Idle detection loop
- `triggerWheatley()` - Message trigger handler
- `displayWheatley()` - Typewriter + cursor display
- `validateHunt()` - Hunt modal validation with custom error messages
- `updateCountdown()` - Timer logic
- `showSlide()` - Photo slideshow with animation reset

**functions.php:**
- `mlc_enqueue_landing_assets()` - Asset loading
- `mlc_validate_hunt_sequence()` - Server-side validation (sequence + time)
- `mlc_add_hunt_nonce()` - Security nonce injection

---

## Known Issues / Pending Work

### 1. Wheatley API Integration (In Progress)
Current: Hardcoded test messages  
Next: Anthropic API integration (~40 minutes work)

**Prep required:**
- Create Anthropic Console account
- Generate API key
- Add billing/credits

**Implementation:**
- Build PHP endpoint
- Wire JS fetch with context
- Test real responses
- Refine personality prompts

### 2. Chatling Widget Setup
Need to deploy Chatling on non-homepage pages with Wheatley personality.

**System prompt template:**
```
PERSONALITY: You are Wheatley, an AI assistant for Mosaic Life Creative.

VOICE RULES:
- Use "Right, so..." to start responses
- Slightly overconfident but helpful
- Self-aware you're AI
- Mildly sarcastic, never mean
- Break fourth wall when appropriate
- Keep responses under 50 words

KNOWLEDGE: [Services, pricing, process]

BEHAVIOR:
- If asked about countdown/hunt: mysterious but encouraging
- If asked who you are: "I'm Wheatley. I'm an AI. This is my whole thing."
```

### 3. Remaining Site Pages
- Services (mockup complete, needs build)
- How We Work (timeline/stepper design)
- Examples (card grid, not portfolio)
- Let's Talk (Calendly + simple form)

### 4. Advanced Wheatley Features
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

---

## Production Migration Strategy

### Phase 1: Foundation (COMPLETE)
- ✅ Landing page template
- ✅ Hunt modal with server validation
- ✅ Countdown timer
- ✅ Nav overlay with photo slideshow
- ✅ Wheatley idle detection (hardcoded)
- ✅ Security: time window validation

### Phase 2: Intelligence (IN PROGRESS)
- 🚧 Wheatley API integration
- 📋 Chatling widget deployment
- 📋 Advanced meta-commentary features

### Phase 3: Site Expansion (NEXT)
- 📋 Services page build
- 📋 How We Work page
- 📋 Examples page
- 📋 Let's Talk page
- 📋 Quest site polish

### Phase 4: Polish & Launch (FUTURE)
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
- Character: Portal 2 Wheatley
- Voice: "Right, so..." + self-aware + fourth-wall breaking
- Status: FINAL — test messages approved
- Next: API integration with same personality

**2026-02-04: Server-side security enhancement**
- Issue: Client-side timer could be bypassed with DevTools
- Solution: PHP validates both sequence AND time window
- Status: IMPLEMENTED and tested

**2026-02-04: Services page design approved**
- Layout: Two-column split (AI Agents / Websites)
- Style: Clean cards, gradient accents, transparent pricing
- Status: Mockup complete, ready for build

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