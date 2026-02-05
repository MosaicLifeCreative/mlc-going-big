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
- [x] URL personalization system (name + birthday flag encoding)
- [x] Cost control: ~$0.0015 per message, max 11 messages per visitor (~$0.015/engaged visitor)

### Design & Planning
- [x] Services page mockup/vision
- [x] Snake 451 game prototype (Fahrenheit 451 reference for hunt)
- [x] CSS v1.2.1, JS v1.4.3 deployed

## 📋 Next Up (Priority Order)

### 1. Priority Fixes
- [ ] **Early button click handler:** If user clicks "Like nothing else" before 30s (before Wheatley appears), trigger special Wheatley welcome message, THEN open Chatling
  - Check `state.wheatleyMessageCount === 0` in button handler
  - Special message: "Brilliant choice. Right, let me just—there we go. Chat window, bottom right. That's me in there. Properly interactive now."
  - After typewriter completes → open Chatling

### 2. Chatling Integration - READY FOR AFTERNOON SESSION
- [ ] Replace hardcoded chatbot with Chatling widget
- [ ] "Like nothing else" button → Wheatley transition → Chatling opens (coordinated with early button fix)
- [ ] Unified Wheatley personality across homepage + chat widget
- [ ] Chatling personality prompt configuration with Aperture Science backstory

### 3. Wheatley Enhancements
- [ ] **Real math calculations:** Use actual session metrics in 10m-20m messages
  - Example: "600 seconds, 4 scrolls, 0 clicks. 600 ÷ 4 = 150 seconds per scroll. That's either thorough reading or excellent procrastination."
  - Pull from state: session_duration, scroll count, interaction count
  - Present in Wheatley's conversational voice (not clinical)
  - Keep under 37 words
- [ ] **Name parameter personalization:** URL like `?u=U2FyYWg=` (Base64 encoded name)
  - JavaScript reads URLSearchParams on page load
  - Decode Base64, store in state, pass to API
  - Wheatley's first message: "Right, so—Sarah, is it? Someone sent you here specifically. Interesting."
  - Don't store in localStorage (privacy)
- [ ] **Share button with URL obfuscation:**
  - UI: Input name → generates `?u=Base64` link
  - "Copy Link" button
  - Clean shareable URLs without obvious parameters
  - Option to upgrade to short code backend later for analytics

### 4. Aperture Science Integration
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

### 5. Quest Site Build (4815162342.quest)
**Hunt Flow:**
1. ✅ Countdown: 3:16:23 PM daily (42-second window)
2. ✅ Sequence Input: 4815162342
3. ✅ Combination Lock: 2237
4. 🚧 Snake 451 Game:
   - Target score: 451 (Fahrenheit 451 reference)
   - Escalating point values per apple (easier progression)
   - Door appears at 451 on random edge
   - Must navigate to door without dying to win
   - Fail states: exceed 451, hit wall, hit self
5. ⏳ GLaDOS Testing Chamber (optional)
6. ⏳ Final stage: TBD (Anorak AI guide?)

### 6. Site Pages
- [ ] Services page build (from mockup)
- [ ] How We Work page (timeline/stepper design)
- [ ] Examples page (card grid, not portfolio)
- [ ] Let's Talk page (Calendly + simple form)
- [ ] About page (personal story, values)
- [ ] Contact page (form + info)

### 7. Advanced Wheatley Features (Future)
- [ ] Hunt meta-commentary based on countdown proximity
- [ ] Session memory (sessionStorage tracking across visits)
- [ ] Tab visibility detection ("You switched back. I was still here.")
- [ ] More console easter eggs (hidden commands, keystroke combos)

### 8. Final Polish + Launch
- [ ] Performance optimization
- [ ] SEO implementation
- [ ] Cross-browser testing
- [ ] Mobile QA
- [ ] Analytics setup
- [ ] Launch checklist execution

## 💡 Ideas / Nice-to-Have
- Custom plugin to add/edit nav photos (show off travels)
- Award submission preparation (Awwwards, CSS Design Awards, FWA)
- Case study documentation for hunt system
- Video walkthrough of interactive features
- Behind-the-scenes blog post about building Wheatley
- Personalized URL generator tool for client demos and hunt invitations
- Share link analytics (track who's sharing, conversion rates)
- Upgrade URL obfuscation to true short codes with backend
- Real-time GLaDOS TTS with personalization (vs pre-generated audio)