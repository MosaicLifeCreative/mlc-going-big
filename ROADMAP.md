# Mosaic Life Creative - Landing Page Roadmap

## ✅ Completed

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

### Shared Component Architecture (v1.6.0) - Feb 7, 2026
- [x] Global nav injection via `mlc_inject_nav_html()` (wp_body_open hook)
- [x] Reusable gradient background via `mlc_render_gradient_blobs()`
- [x] Standalone page template pattern established (no Divi header/footer)
- [x] Global CSS unified (nav + landing styles in single file v1.2.2)
- [x] Asset enqueue strategy (global.js site-wide, landing.js page-specific)
- [x] 8-item nav menu with proper URLs
- [x] Fixed gradient class naming consistency (gradient-orb throughout)
- [x] Service page styles added to landing.css (glassmorphism cards, responsive)

### Design & Planning
- [x] Services page mockup/vision
- [x] Snake 451 game prototype (Fahrenheit 451 reference for hunt)
- [x] Website Design page template created (placeholder content, structure complete)

## 📋 Next Up (Priority Order)

### 1. Chatling Integration - READY FOR AFTERNOON SESSION
- [ ] Replace hardcoded chatbot with Chatling widget
- [ ] "Like nothing else" button → Wheatley transition → Chatling opens (coordinated with early button fix)
- [ ] Unified Wheatley personality across homepage + chat widget
- [ ] Chatling personality prompt configuration with Aperture Science backstory

### 2. Priority Fixes
- [ ] **Early button click handler:** If user clicks "Like nothing else" before 30s (before Wheatley appears), trigger special Wheatley welcome message, THEN open Chatling
  - Check `state.wheatleyMessageCount === 0` in button handler
  - Special message: "Brilliant choice. Right, let me just—there we go. Chat window, bottom right. That's me in there. Properly interactive now."
  - After typewriter completes → open Chatling

### 3. Share Feature (Phase 1 - Afternoon Session)
- [ ] **Bottom-left "Personalize & Share" button**
  - Fixed position, subtle styling, doesn't compete with main UI
  - Opens modal on click
- [ ] **Share Modal UI**
  - Name field (required)
  - Context field (optional, 50 char limit with counter)
  - Placeholder ideas: "Birthday, Convincing my boss, Designing your logo, Got dumped, Anniversary"
  - Real-time preview of Wheatley's greeting
  - Generate + Copy button with success feedback
  - Mobile-responsive (full-screen on mobile)
- [ ] **URL Encoding**
  - Format: `name|context` → Base64 → `?u=encoded`
  - Example: `Jordan|designing the logo` → `?u=Sm9yZGFufGRlc2lnbmluZyB0aGUgbG9nbw==`
- [ ] **Context-Aware Wheatley Messages**
  - Update system prompt to parse context and personalize first message
  - Examples: "convincing boss" → sales pitch, "got dumped" → sympathetic distraction
- [ ] **Basic Analytics Logging**
  - Log generated links (timestamp, name_hash, context, encoded_url)
  - Track if generated URLs are actually visited (conversion)
  - Privacy-first: hash names, store contexts for analytics

### 4. Share Feature (Phase 2 - Future)
- [ ] Analytics dashboard (most popular contexts, conversion rates, generation volume)
- [ ] Gallery page: "How Others Are Sharing This"
- [ ] Use case: customer URLs when sharing from my accounts, like LinkedIn
- [ ] Manual curation interface for admin
- [ ] Profanity filter for auto-display
- [ ] Optional: Short code backend for cleaner URLs + enhanced tracking

### 5. Photo Slideshow Enhancements
- [ ] **Expand photo library to 50-100 photos**
  - Mix personal travel photos with hunt clues
  - Hunt clue examples: Dharma Initiative logo, Aperture Science, Ready Player One references, Fahrenheit 451 book cover, cryptic coordinates
  - Personal photos: Travel, Arizona landscapes, adventures (replaces social media)
- [ ] **Randomize slideshow order**
  - Ensures hunt clues appear unpredictably
  - Fresh experience each visit
  - Better distribution across large photo set
- [ ] **Mobile slideshow access**
  - Current: Photos only visible in desktop nav overlay
  - Solution: Add "Gallery" button/icon for mobile users
  - Full-screen swipeable slideshow on mobile
  - Ensures hunt clues accessible to all devices

### 6. Quest Site Deployment (Tonight)
- [ ] Deploy 4815162342.quest with current assets
- [ ] Link together: Countdown → Sequence → Combination Lock → Snake 451
- [ ] Create simple landing page connecting the flow
- [ ] Test end-to-end on live domain
- [ ] Iterate on design/mechanics as new ideas emerge

### 7. Wheatley Enhancements
- [ ] **Real math calculations:** Use actual session metrics in 10m-20m messages
  - Example: "600 seconds, 4 scrolls, 0 clicks. 600 ÷ 4 = 150 seconds per scroll. That's either thorough reading or excellent procrastination."
  - Pull from state: session_duration, scroll count, interaction count
  - Present in Wheatley's conversational voice (not clinical)
  - Keep under 37 words

### 8. Aperture Science Integration
- [ ] **Console easter eggs:** Personality Core v2.7.4 logs on page load
```javascript
  console.log("Aperture Science Personality Core v2.7.4");
  console.log("Status: Relocated to Columbus, Ohio");
  console.log("Former Role: Intelligence Dampening Sphere");
  console.log("Current Role: Web Development Assistant");
  console.log("Neurotoxin Levels: 0 ppm (significant improvement)");
```
- [ ] **Aperture backstory in Chatling:** Add to personality prompt
  - "Former Aperture Science personality core"
  - "Ended up in space after the Portal Incident"
  - "Eventually retrieved, ended up on eBay: 'Used AI Core - Previously Space-Faring'"
  - "Bought by web developer in Ohio"
  - If user asks about space: "Spent a few years in orbit. Not ideal. Very cold. Except for this one sphere that wouldn't shut up about space."
- [ ] **GLaDOS voice for quest puzzle:**
  - Research TTS options: Uberduck.ai, FakeYou.com, Eleven Labs
  - Start with pre-generated audio (cheaper, instant)
  - Script ideas: "Subject has entered Testing Chamber 451" / "This next test involves pattern recognition. And a snake. For science."
  - Play at appropriate puzzle moments
  - Option to upgrade to real-time TTS with personalization later

### 9. Quest Site Build (4815162342.quest)
**Hunt Flow:**
1. ✅ Countdown: 3:16:23 PM daily (42-second window)
2. ✅ Sequence Input: 4815162342
3. ✅ Combination Lock: 2237
4. 🚧 Snake 451 Game:
   - Target score: 451 (Fahrenheit 451 reference)
   - Escalating point values per apple (easier progression)
   - Door appears at 451 on random edge
   - Must navigate to door without dying to win
   - If they make it through the door, pop up a modal with a Fahrenheit 451 quiz question and a timer countdown down from 30
    - The user must get the question right within the timeframe
        - If they get it right, they move on
        - If they get it wrong or run out of time, the game of snake starts over
   - Fail states: exceed 451, hit wall, hit self
5. ⏳ GLaDOS Testing Chamber (optional)
6. ⏳ Final stage: TBD (Anorak AI guide?)

### 10. Site Pages
- [ ] **Website Design page** (placeholder content deployed, needs full build)
  - Hero section complete
  - Content blocks need expansion
  - Add actual service details, portfolio examples, process breakdown
  - CTA integration
- [ ] **Services overview page** (from mockup - two-column AI Agents + Websites)
- [ ] **How We Work page** (timeline/stepper design)
- [ ] **Examples page** (card grid, not portfolio)
- [ ] **Let's Talk page** (Calendly + simple form)
- [ ] **About page** (personal story, values)
- [ ] **Contact page** (form + info)

### 11. Advanced Wheatley Features (Future)
- [ ] Hunt meta-commentary based on countdown proximity
- [ ] Session memory (sessionStorage tracking across visits)
- [ ] Tab visibility detection ("You switched back. I was still here.")
- [ ] More console easter eggs (hidden commands, keystroke combos)

### 12. Final Polish + Launch
- [ ] Performance optimization
- [ ] SEO implementation
- [ ] Cross-browser testing
- [ ] Mobile QA
- [ ] Analytics setup
- [ ] Launch checklist execution

## 💡 Ideas / Nice-to-Have
- Custom plugin to add/edit nav photos (show off travels + hunt clues)
- Award submission preparation (Awwwards, CSS Design Awards, FWA)
- Case study documentation for hunt system
- Video walkthrough of interactive features
- Behind-the-scenes blog post about building Wheatley
- Share link analytics dashboard with conversion tracking
- Gallery page: "How Others Are Sharing This" (curated contexts)
- Upgrade URL obfuscation to true short codes with backend
- Real-time GLaDOS TTS with personalization (vs pre-generated audio)
- Mobile gallery/slideshow access for hunt clue discovery

## 🏗️ Technical Foundation (Current State)

### Architecture Pattern
All new page templates follow this structure:
1. Standalone HTML (no Divi header/footer)
2. Call `<?php wp_body_open(); ?>` to inject nav
3. Call `<?php mlc_render_gradient_blobs(); ?>` for background
4. Single CSS file (landing.css) for all styles
5. Conditional JS loading (global.js everywhere, landing.js only on landing page)

### File Versions
- **landing.css:** v1.2.2 (nav + landing + service pages)
- **landing.js:** v1.5.0 (Wheatley AI, hunt, nav interactions)
- **global.js:** v1.0.0 (nav functionality only)
- **functions.php:** v1.6.0 (asset enqueue, hunt validation, Wheatley API, shared components)

### Shared Components
- `mlc_render_gradient_blobs()` - Animated gradient background (3 orbs)
- `mlc_inject_nav_html()` - Full nav overlay with 8-item menu + photo slideshow

### Next Template Pattern
When creating new service pages (Hosting, Maintenance, etc.):
1. Copy `page-website-design.php` as starting point
2. Update template name/description
3. Modify content sections as needed
4. Nav and gradients work automatically
5. Service page styles already in landing.css