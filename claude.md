# Mosaic Life Creative — Website Rebuild

## What This Is

Working files for the MLC rebrand and production WordPress site. The project started with React artifacts as design-locked demos, then transitioned to production WordPress/Divi implementation with custom page templates. This file is the handoff document — everything a future session needs to pick up without losing context.

---

## Quick Start (For New Sessions)

**Current Status:** Service pages rebuilt with new `sp-` design system (full-bleed, scroll reveals). Wheatley page sections live (parallax void with bad salesman personality). Global countdown timer on all pages. AI Chat Agents page complete as pilot. 6 remaining page templates created (need content/design refinement).

**Next Priorities:**
1. Apply sp- design + Wheatley sections to remaining 6 page templates
2. Page-specific Wheatley treatments (blue/orange portals, maintenance hatch, corrupted email, etc.)
3. Photo slideshow expansion (50-100 photos with hunt clues)
4. HUNT knowledge base for Chatling
5. Quest site deployment (4815162342.quest)

---

## Current Production Files

| File | Version | Status |
|------|---------|--------|
| `page-landing.php` | v2 | Landing page template with share feature |
| `page-ai-chat-agents.php` | v1 | AI Chat Agents — pilot sp- design + Wheatley void |
| `page-website-design.php` | v1 | Website Design service page (sp- structure) |
| `page-hosting.php` | v1 | Hosting service page (sp- structure) |
| `page-maintenance.php` | v1 | Maintenance service page (sp- structure) |
| `page-email-marketing.php` | v1 | Email Marketing service page (sp- structure) |
| `page-about.php` | v1 | About page (sp- structure) |
| `page-contact.php` | v1 | Contact page (sp- structure) |
| `assets/css/landing.css` | 1.4.2 | All styles + sp- service page system + Wheatley void + global footer |
| `assets/js/landing.js` | 1.7.2 | Interactive behaviors + Wheatley + share API + session persistence |
| `assets/js/global.js` | 1.5.0 | Nav + Chatling + scroll reveals + countdown + Wheatley page sections |
| `functions.php` | 1.9.0 | Enqueue + hunt + Wheatley APIs (homepage + page) + gradient blobs + countdown footer |
| `mlc-toolkit/` | 1.0.1 | Plugin: photo management, share analytics, URL shortener, dashboard widget |
| `services-mockup.html` | Mockup | Original services vision (superseded by sp- system) |
| `snake-451.html` | Prototype | Hunt game (needs point system refinement) |

---

## File Structure

```
/wp-content/themes/divi-child/
├── page-landing.php          (Landing page template with share feature)
├── page-ai-chat-agents.php   (AI Chat Agents — pilot sp- design + Wheatley void)
├── page-website-design.php   (Website Design service page)
├── page-hosting.php          (Hosting service page)
├── page-maintenance.php      (Maintenance service page)
├── page-email-marketing.php  (Email Marketing service page)
├── page-about.php            (About page)
├── page-contact.php          (Contact page)
├── functions.php              (v1.9.0 - Enqueue + hunt + Wheatley APIs + gradient blobs + countdown)
├── assets/
│   ├── css/
│   │   └── landing.css       (v1.4.2 - sp- system + Wheatley void + global footer)
│   └── js/
│       ├── global.js         (v1.5.0 - nav + scroll reveals + countdown + Wheatley page sections)
│       └── landing.js        (v1.7.2 - Wheatley + share API + session persistence)
├── services-mockup.html       (Original mockup, superseded by sp- system)
├── snake-451.html             (Hunt game prototype)
└── CLAUDE.md / ROADMAP.md     (Documentation)

/wp-content/plugins/mlc-toolkit/
├── mlc-toolkit.php            (Main plugin: share URL handler, REST API, dashboard widget, legacy redirect)
├── includes/
│   ├── class-mlc-photos.php   (Photo management via WP options API)
│   └── class-mlc-share.php    (Share links + click tracking, 2 DB tables)
├── admin/
│   ├── class-mlc-admin.php    (Admin menus + save handlers)
│   ├── views/photos.php       (Drag-and-drop photo management)
│   ├── views/share-analytics.php (Analytics dashboard)
│   ├── css/admin.css
│   └── js/admin-photos.js
└── public/
    ├── css/mobile-slideshow.css
    └── js/mobile-slideshow.js
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
- Wheatley (homepage): Anthropic API (Claude Haiku 4.5) via `/wp-json/mlc/v1/wheatley` - PRODUCTION
- Wheatley (service pages): Anthropic API via `/wp-json/mlc/v1/wheatley-page` - PRODUCTION (bad salesman personality)
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

**Photo slideshow mechanics (desktop + mobile):**
- Ken Burns animation (scale 1→1.05 over 6s, `forwards` fill to prevent snap-back)
- Auto-advances every 6 seconds
- Manual controls: prev/next arrows (desktop), arrows + swipe (mobile)
- Timer resets on manual navigation
- Resets animation on each transition (force reflow technique)

**Nav items:** Home, Website Design, Hosting, Maintenance, Email Marketing, AI Chat Agents, About, Contact, View Pretty Photos (mobile only)

**Mobile "View Pretty Photos":** Opens full-screen overlay via MLC Toolkit plugin (`mobile-slideshow.js` + `mobile-slideshow.css`). Same photo set, same Ken Burns + auto-advance behavior as desktop nav.

### 5. Share Feature (v1.7.2 — Fully Integrated)

**Button:** Fixed position, glassmorphism styling
- Desktop: bottom-left corner (20px, 20px)
- Mobile: centered above countdown (50%, bottom: 60px)

**Modal:** Full-screen on mobile, centered card on desktop
- Name field (required)
- Context field (optional, 50 char limit with counter)
- Preview section showing sample Wheatley greeting
- Calls MLC Toolkit REST API (`POST /wp-json/mlc/v1/share`) to create tracked short link
- Copies short URL (`/s/{code}`) to clipboard

**Short URL Pipeline:**
1. `/s/{code}` → PHP handler on `init` hook (priority 1) checks `$_SERVER['REQUEST_URI']`
2. Records click in DB, sends SiteGround cache-busting headers
3. Renders bridge HTML page that sets `sessionStorage.setItem('mlc_share', encoded)`
4. Client-side redirect to homepage
5. `landing.js` reads sessionStorage, decodes name + context
6. Stores decoded values in `mlc_share_name` / `mlc_share_context` (survives reloads)
7. Passes `user_name` + `user_context` to Wheatley API
8. `functions.php` builds `CRITICAL — PERSONALIZED VISITOR` block in system prompt

**MLC Toolkit Plugin:**
- Admin: MLC Toolkit > Slideshow Photos (drag-and-drop reorder)
- Admin: MLC Toolkit > Share Analytics (stats, link table, activity feed)
- Dashboard: Share Link Metrics widget (Screen Options toggle)
- Legacy domain redirect: `define('MLC_OLD_DOMAINS', '...')` in wp-config.php

### 6. Scavenger Hunt System

**Countdown timer** — Global on all pages. Static footer at bottom of page content (NOT fixed/sticky). Dark background with light text. Targets 3:16:23 PM daily (Lost numbers: 3, 16, 23). Displays as `HH:MM:SS` in monospace.
- Landing page: countdown lives in `page-landing.php` footer, logic in `landing.js`
- All other pages: countdown injected via `mlc_inject_countdown_footer()` wp_footer hook, logic in `global.js`
- global.js skips countdown init on landing page (checks `mlc-landing-page` body class)

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
- **Homepage:** Wheatley proactive (time-based takeover of headline)
- **Service pages:** Wheatley page sections — parallax void windows embedded in page content
- **Other pages:** Chatling widget (bottom right, same personality)
- **Continuity:** Same Wheatley voice across entire site
- **No Chatling on homepage** to avoid conflict

### Wheatley Page Sections (v1.5.0 — PRODUCTION)

**Concept:** Full-width parallax "void" sections embedded in service pages. Wheatley appears as a bad salesman — terrible at selling, doing it for the paycheck. Triggered when the section scrolls into view.

**Architecture:**
- HTML: `<section class="wheatley-void" data-wheatley-page="..." data-wheatley-context="...">`
- JS: IntersectionObserver (threshold 0.3) triggers API call when visible
- API: `/wp-json/mlc/v1/wheatley-page` with bad salesman system prompt
- Display: Typewriter effect at 28ms/char with blinking cursor
- No caching — fresh message on every page load

**Visual Design (CSS):**
- `.wheatley-void` — full-width, dark bg, `background-attachment: fixed` for parallax
- Perspective grid overlay (`transform: perspective(300px) rotateX(40deg)`)
- Radial glow (purple/cyan), scanlines, edge vignette
- Label: "MLC Personality Core v2.7.4" (monospace)
- Mobile: `background-attachment: scroll` fallback (iOS Safari limitation)

**Page-Specific Treatments (PLANNED):**
- AI Chat Agents: Raw void (COMPLETE — pilot)
- Website Design: Blue portal → click navigates to Hosting
- Hosting: Orange portal → click navigates to Website Design
- Maintenance: Maintenance hatch or terminal screen (TBD)
- Email Marketing: Corrupted email UI
- About: Quiet, softer void
- Contact: Minimal treatment

**Bad Salesman Personality:**
- Terrible at sales — undersells, gets distracted, says the quiet part out loud
- Self-aware about being bad at this ("I'm supposed to tell you this is amazing")
- British cadence, same Wheatley voice
- If share name exists: MUST use their name prominently
- No pricing context (owner still deciding on public pricing)

**Technical Notes:**
- `background-attachment: fixed` does NOT work with CSS `transform` on the same element (transforms create new containing blocks). Grid parallax is on `.wheatley-void` background, perspective overlay is a separate child div.
- `background-attachment: fixed` doesn't work on iOS Safari — CSS uses `@supports` or media query fallback to `scroll`
- Removed all IP references: no "Aperture Science", no "Portal 2 Wheatley" in visible UI or prompts

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

## Service Page Design System (`sp-` prefix)

**Status:** PRODUCTION — AI Chat Agents page complete as pilot. Replaces the old two-column card mockup.

### Design Philosophy
Full-bleed, full-width sections with dramatic scale. No more "cards in a container." Award-winning pages use edge-to-edge sections alternating dark/light, massive typography, split layouts, scroll-triggered reveals, and strategic whitespace.

### Section Types

**1. Hero (`sp-hero`)** — Full viewport width, dark bg, massive centered text
- Title: `clamp(60px, 14vw, 160px)`, bold, white
- Subtitle: `clamp(20px, 3vw, 28px)`, muted white
- Gradient blob background via `mlc_render_gradient_blobs()`

**2. Split Section (`sp-section` + `sp-split`)** — Text on one side, image on the other
- `sp-section--light` / `sp-section--dark` for alternating color rhythm
- `sp-split--reverse` for image-left/text-right
- Image placeholders in `sp-split__media`

**3. Features (`sp-features`)** — Large numbered items (01, 02, 03, 04)
- `sp-feature__number` — huge (120px+), 5% opacity background element
- Staggered reveal with `--delay` custom property

**4. Examples (`sp-examples`)** — Grid of clickable cards with hover lift
- Used on AI Chat Agents for live client links

**5. CTA (`sp-cta`)** — Full-width dark section, centered
- Title + subtitle + gradient button (purple → pink)

**6. Wheatley Void (`wheatley-void`)** — Parallax break section
- See "Wheatley Page Sections" above

### Scroll Animations
- `.reveal` class → IntersectionObserver (15% threshold) → `.in-view` class
- CSS: `opacity: 0` + `translateY(40px)` → `opacity: 1` + `translateY(0)`, 0.8s ease
- Staggered via `--delay` custom property on each element
- JS in `global.js` `initScrollReveal()`

### Color Rhythm (alternating sections)
- Hero: `#0A0A0A` (dark)
- Section 1: `#F8F8F8` (light)
- Section 2: `#0A0A0A` (dark)
- Features: `#F8F8F8` (light)
- Wheatley: `#0A0A0A` (void)
- CTA: `#0A0A0A` or gradient

### Page Template Pattern
Each service page follows: `sp-hero` → 2-3 `sp-section` splits → `sp-features` → `wheatley-void` → `sp-cta`

### Reference Files
- `page-ai-chat-agents.php` — Complete pilot, reference for all other pages
- `services-mockup.html` — Original two-column mockup (superseded, kept for reference)

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
- CSS: v1.4.2 (sp- service page system + Wheatley void + global footer + scroll reveals)
- JS (landing): v1.7.2 (share API integration + session-persistent personalization)
- JS (global): v1.5.0 (nav + scroll reveals + countdown + Wheatley page sections)
- PHP: v1.9.0 (Wheatley page API + gradient blobs + countdown footer injection)
- MLC Toolkit: v1.0.1 (photo management, share analytics, URL shortener, dashboard widget)

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

**global.js:**
- `initScrollReveal()` - IntersectionObserver for `.reveal` elements (15% threshold)
- `initCountdown()` - Global countdown timer + hunt modal (skips on landing page)
- `initWheatleySection()` - Finds `[data-wheatley-page]`, triggers on visibility (30% threshold)
- `fetchWheatleyPageMessage()` - API call to `/wp-json/mlc/v1/wheatley-page` with full context
- `wheatleyTypewriter()` - Character-by-character display at 28ms/char

**functions.php:**
- `mlc_enqueue_landing_assets()` - Asset loading with version cache busting
- `mlc_validate_hunt_sequence()` - Server-side validation (sequence + time)
- `mlc_add_hunt_nonce()` - Security nonce injection (global, all pages)
- `mlc_wheatley_respond()` - Homepage Wheatley API endpoint
- `mlc_wheatley_page_respond()` - Service page Wheatley API endpoint (bad salesman)
- `mlc_inject_nav_html()` - Global nav injection via wp_body_open (9 items, dynamic photos)
- `mlc_inject_countdown_footer()` - Global countdown timer via wp_footer (skips landing page)
- `mlc_render_gradient_blobs()` - Reusable gradient background for sp-hero sections

**mlc-toolkit plugin:**
- `mlc_toolkit_handle_share_url()` - `/s/{code}` handler (init hook, priority 1)
- `mlc_toolkit_create_share()` - REST endpoint for creating share links
- `mlc_toolkit_dashboard_widget_render()` - WP Dashboard metrics widget
- `mlc_toolkit_legacy_domain_redirect()` - Old domain 301 redirect

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

### ~~1. Share Feature Phase 2~~ — COMPLETE
- [x] Context-aware Wheatley messages (personalization block in system prompt)
- [x] Share analytics (MLC Toolkit plugin: link tracking, click recording, admin dashboard, WP dashboard widget)
- [x] URL shortener (`/s/{code}`) with session-persistent personalization
- [x] Legacy domain redirect support

### 3. Snake 451 Point System
Current prototype needs refinement:
- Escalating point values per apple (e.g., 1, 2, 3, 5, 8, 13, etc.)
- Makes 451 achievable while keeping Fahrenheit 451 reference
- Don't rebuild yet - refinement session needed to determine progression

### 4. Remaining Site Pages — sp- Design + Wheatley Treatments
All 7 page templates created. AI Chat Agents is the complete pilot. Remaining 6 need:
- Content refinement and image placeholders
- Page-specific Wheatley treatments:
  - Website Design: Blue portal → links to Hosting
  - Hosting: Orange portal → links to Website Design
  - Maintenance: Maintenance hatch or terminal (TBD)
  - Email Marketing: Corrupted email UI
  - About: Quiet, softer void
  - Contact: Minimal treatment

### 5. Advanced Wheatley Features
- Hunt meta-commentary based on countdown proximity
- Session memory (sessionStorage tracking)
- Tab visibility detection
- Console easter eggs
- Weather API context for Wheatley messages

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

### Phase 4: Integration (COMPLETE - Feb 8, 2026)
- ✅ Context-aware Wheatley messages from share URLs
- ✅ Share analytics (MLC Toolkit plugin with URL shortener, dashboard widget)
- ✅ Session-persistent personalization (survives page reloads)
- 📋 Chatling HUNT knowledge base

### Phase 5: Site Expansion (IN PROGRESS - Feb 23, 2026)
- ✅ sp- design system (full-bleed sections, scroll reveals, dark/light rhythm)
- ✅ AI Chat Agents page (pilot — complete with Wheatley void)
- ✅ Global countdown timer on all pages
- ✅ Wheatley page section API + bad salesman personality
- 📋 Remaining 6 pages: content refinement + page-specific Wheatley treatments
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

**2026-02-04: Services page design approved** *(SUPERSEDED by sp- system Feb 23)*
- Original: Two-column split cards (AI Agents / Websites)
- Replaced by: Full-bleed sp- section system with scroll reveals
- File `services-mockup.html` kept for reference

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

**2026-02-23: sp- Service Page Design System**
- Replaced old two-column card design with full-bleed section system
- New `sp-` CSS prefix to avoid conflicts with old `.service-*` styles
- Edge-to-edge sections, massive typography, split layouts, scroll reveals
- AI Chat Agents built as pilot — reference for all other pages
- Status: PRODUCTION (pilot complete)
- Date: Feb 23, 2026

**2026-02-23: Wheatley Page Sections — Bad Salesman**
- Wheatley appears in parallax "void" sections on service pages
- Personality: terrible salesman doing it for the paycheck (not hostile, just bad)
- New API endpoint: `/wp-json/mlc/v1/wheatley-page`
- No caching — fresh message on every page load
- Share name MUST be used prominently if available
- Status: PRODUCTION (AI Chat Agents pilot)
- Date: Feb 23, 2026

**2026-02-23: Global Countdown Timer**
- Hunt countdown now appears on all pages (not just landing)
- Static footer at bottom of content (NOT fixed/sticky)
- Landing page uses its own countdown (landing.js), global.js skips it
- Other pages: injected via `mlc_inject_countdown_footer()` wp_footer hook
- Status: IMPLEMENTED
- Date: Feb 23, 2026

**2026-02-23: Wheatley IP Cleanup**
- Removed "Aperture Science" from visible UI (now "MLC Personality Core v2.7.4")
- Removed "Portal 2 Wheatley" from API system prompts
- Console easter eggs in landing.js kept (developer-only, more defensible)
- Rationale: Reduce Valve trademark risk while preserving personality
- Status: IMPLEMENTED
- Date: Feb 23, 2026

**2026-02-23: Page-Specific Wheatley Treatments (PLANNED)**
- Website Design: Blue portal → click navigates to Hosting
- Hosting: Orange portal → click navigates to Website Design
- Maintenance: Maintenance hatch or terminal screen (TBD)
- Email Marketing: Corrupted email UI
- About: Quiet, softer void
- Contact: Minimal treatment
- Status: PLANNED — build after pilot approval
- Date: Feb 23, 2026

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