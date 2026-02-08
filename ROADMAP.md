# Mosaic Life Creative - Roadmap

## Completed

### Landing Page Foundation
- [x] Text sequence with four phases
- [x] Choice buttons (Like everyone else's / Like nothing else)
- [x] Hamburger nav with photo slideshow
- [x] Hunt modal with server-side validation
- [x] Countdown timer (3:16:23 PM, 42-second window)
- [x] Security: Server-side time validation (both sequence AND time window)
- [x] Obfuscated config (removed obvious hunt comments)

### Wheatley AI System (v1.4.3) - Feb 5, 2026
- [x] Anthropic API integration with Claude Haiku 4.5
- [x] Context-aware personality (visitor type, exact time, device, activity)
- [x] Time-based triggers (30s, 3m, 10m, 20m, 30m, +10m intervals)
- [x] 90-minute finale message with automatic shutoff
- [x] API failure fallback with self-aware error messaging
- [x] Clean UI: CTA text hidden when Wheatley appears, buttons remain
- [x] URL personalization system (name + context encoding)
- [x] Cost control: ~$0.0015 per message, max 11 messages per visitor (~$0.015/engaged visitor)
- [x] Chatling fade-in animation on homepage

### Shared Component Architecture (v1.6.0) - Feb 7, 2026
- [x] Global nav injection via `mlc_inject_nav_html()` (wp_body_open hook)
- [x] Reusable gradient background via `mlc_render_gradient_blobs()`
- [x] Standalone page template pattern established (no Divi header/footer)
- [x] Global CSS unified (nav + landing styles in single file)
- [x] Asset enqueue strategy (global.js site-wide, landing.js page-specific)
- [x] 8-item nav menu with proper URLs
- [x] Service page styles added to landing.css (glassmorphism cards, responsive)

### Chatling Integration (v1.6.1) - Feb 7, 2026
- [x] On-demand script injection (loads only when button clicked)
- [x] Parallel loading during Wheatley typewriter animation
- [x] Z-index hierarchy (Chatling forced to 999999, menu at 2147483646)
- [x] Wheatley personality configuration in Chatling dashboard
- [x] LORE knowledge base created (Aperture backstory, GLaDOS tension, redemption arc)

### Share Feature - Feb 7, 2026
- [x] Share button and modal UI
- [x] URL encoding system (Base64: `name|context` → `?u=encoded`)
- [x] Preview section showing sample Wheatley greeting
- [x] Generate & Copy button with clipboard functionality
- [x] Mobile-responsive modal (full-screen on mobile)

### Aperture Science Easter Eggs
- [x] Console personality core logs on page load (v2.7.4)

### Homepage CTA Fixes - Feb 8, 2026
- [x] Reduced padding between phase text and CTA buttons (40px → 16px)
- [x] Fixed mobile button shrink/expand bug when Wheatley triggers
- [x] Reduced mobile CTA spacing further (min-height 200→100px, margin 16→8px)

### MLC Toolkit Plugin (v1.0.0) - Feb 8, 2026
- [x] Plugin scaffold with admin menu
- [x] **Photo Slideshow Management:** Admin interface to add, remove, reorder photos
- [x] **Photo Randomization:** Photos shuffled on each page load
- [x] **Mobile Slideshow:** "View Pretty Photos" as 9th nav menu item (mobile only), full-screen overlay with swipe + arrow navigation
- [x] **Share URL Shortener:** `/s/{code}` short URLs with sessionStorage bridge page + SiteGround cache-busting headers
- [x] **Share Analytics Tracking:** Records link creation and click events
- [x] **Share Analytics Dashboard:** Summary stats, link table, top contexts, recent activity feed
- [x] **Dashboard Widget:** Share Link Metrics widget on WP Dashboard (Screen Options)
- [x] **Share Personalization:** Context-aware Wheatley first messages via share URL personalization
- [x] **Session Persistence:** Share personalization survives page reloads (sessionStorage)
- [x] **Legacy Domain Redirect:** `MLC_OLD_DOMAINS` constant in wp-config.php for smooth migration
- [x] **Theme Integration:** Dynamic photo rendering in nav, JS localization, graceful fallback if plugin disabled

---

## Next Up (Priority Order)

### 1. Wheatley Enhancements
- [ ] **HUNT knowledge base** for Chatling
  - Scavenger hunt context and clues
  - Quest page integration
  - Context-aware responses based on {{webpage_url}}
- [ ] **Weather API context** for Wheatley conversation
  - Fetch current weather for Columbus, OH
  - Pass to Wheatley system prompt for natural commentary
- [ ] **Real math calculations:** Use actual session metrics in 10m-20m messages
  - Pull from state: session_duration, scroll count, interaction count
  - Present in Wheatley's conversational voice
  - Keep under 37 words

### 2. Context Integration
- [ ] **Context variable integration in Chatling**
  - Use {{webpage_url}} and {{webpage_title}} for page-specific responses
  - Different behavior on quest pages vs service pages
  - Quest page hints and guidance from Wheatley

### 3. Share Feature Improvements
- [x] ~~Context-Aware Wheatley Messages~~ (DONE — personalization block in system prompt)
- [x] ~~Session persistence for share context~~ (DONE — survives page reloads)
- [ ] **Gallery page:** "How Others Are Sharing This" (curated contexts)
- [ ] **Manual curation interface** for admin
- [ ] **Profanity filter** for auto-display

### 4. Photo Slideshow Enhancements
- [ ] **Expand photo library to 50-100 photos**
  - Mix personal travel photos with hunt clues
  - Hunt clue examples: Dharma Initiative logo, Aperture Science, Ready Player One references, Fahrenheit 451 book cover
  - Upload via MLC Toolkit admin page
- [ ] **Custom photo captions and credits** per image

### 5. Site Pages
- [ ] **Website Design page** (placeholder deployed, needs full content build)
- [ ] **Services overview page** (from mockup - two-column AI Agents + Websites)
- [ ] **How We Work page** (timeline/stepper design)
- [ ] **Examples page** (card grid, not portfolio)
- [ ] **Let's Talk page** (Calendly + simple form)
- [ ] **About page** (personal story, values)
- [ ] **Contact page** (form + info)

### 6. Advanced Wheatley Features (Future)
- [ ] Hunt meta-commentary based on countdown proximity
- [ ] Session memory (sessionStorage tracking across visits)
- [ ] Tab visibility detection ("You switched back. I was still here.")
- [ ] More console easter eggs (hidden commands, keystroke combos)

### 7. Final Polish + Launch
- [ ] Performance optimization
- [ ] SEO implementation
- [ ] Cross-browser testing
- [ ] Mobile QA
- [ ] Analytics setup
- [ ] Launch checklist execution

---

## Ideas / Nice-to-Have
- Award submission preparation (Awwwards, CSS Design Awards, FWA)
- Case study documentation for hunt system
- Video walkthrough of interactive features
- Behind-the-scenes blog post about building Wheatley
- GLaDOS voice TTS for quest puzzles (Uberduck/FakeYou/Eleven Labs)
- Real-time GLaDOS TTS with personalization (vs pre-generated audio)

---

## Technical Foundation

### Architecture Pattern
All new page templates follow this structure:
1. Standalone HTML (no Divi header/footer)
2. Call `<?php wp_body_open(); ?>` to inject nav
3. Call `<?php mlc_render_gradient_blobs(); ?>` for background
4. Single CSS file (landing.css) for all styles
5. Conditional JS loading (global.js everywhere, landing.js only on landing page)

### File Versions
- **landing.css:** v1.3.3 (nav + landing + services + share + CTA fixes + mobile spacing)
- **landing.js:** v1.7.2 (Wheatley AI, Chatling, hunt, nav, share API, session-persistent personalization)
- **global.js:** v1.2.0 (nav with dynamic photo support, Chatling fade-in)
- **functions.php:** v1.8.1 (dynamic photo rendering, plugin integration, 9-item nav with mobile photos)
- **mlc-toolkit plugin:** v1.0.1 (photo management, share analytics, URL shortener, dashboard widget, legacy domain redirect)

### MLC Toolkit Plugin
- **Location:** `/wp-content/plugins/mlc-toolkit/`
- **Admin pages:** MLC Toolkit > Slideshow Photos, MLC Toolkit > Share Analytics
- **REST endpoint:** `POST /wp-json/mlc/v1/share` (create tracked short link)
- **Short URLs:** `/s/{code}` → sessionStorage bridge page + SiteGround cache-busting
- **Dashboard widget:** Share Link Metrics (Screen Options toggle on WP Dashboard)
- **Legacy redirect:** `MLC_OLD_DOMAINS` in wp-config.php for domain migration
- **DB tables:** `mlc_share_links`, `mlc_share_clicks`
- **Photo storage:** WordPress options API (`mlc_slideshow_photos`)

### Shared Components
- `mlc_render_gradient_blobs()` - Animated gradient background (3 orbs)
- `mlc_inject_nav_html()` - Full nav overlay with dynamic photos + 9th "View Pretty Photos" menu item (mobile)

### Hunt System (Context — lives at 4815162342.quest)
- Countdown: Daily 3:16:23 PM (Lost numbers: 3, 16, 23)
- 42-second entry window
- Sequence: 4815162342
- Server validates both sequence AND time
- Leads to 4815162342.quest domain
- Further puzzles (combination lock, Snake 451, etc.) managed in quest project
