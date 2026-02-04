# Mosaic Life Creative - Landing Page Roadmap

## ✅ Completed
- [x] Landing page foundation (text sequence, choice buttons)
- [x] Hunt modal with server-side validation
- [x] Countdown timer (3:16:23 PM, 42-second window)
- [x] Hamburger nav with photo slideshow
- [x] Wheatley idle detection + typewriter effect + blinking cursor
- [x] Security: Server-side time validation (both sequence AND time window)
- [x] Services page mockup/vision
- [x] CSS v1.2.1, JS v1.2.3 deployed
- [x] Obfuscated config (removed obvious hunt comments)

## 🚧 In Progress (Next Session)
- [ ] Wheatley API integration (Anthropic) - ~40 minutes
  - [ ] Set up Anthropic Console account (prep before session)
  - [ ] Generate API key + add billing (prep before session)
  - [ ] Build PHP endpoint `/wp-json/mlc/v1/wheatley` (20 min)
  - [ ] Wire up JS fetch call with context passing (10 min)
  - [ ] Test real AI responses (5 min)
  - [ ] Refine personality prompts (5 min)

## 📋 Next Up (Priority Order)
1. [ ] Chatling widget setup (other pages - same Wheatley personality)
2. [ ] Services page build (from mockup)
3. [ ] How We Work page (timeline/stepper design)
4. [ ] Examples page (card grid, not portfolio)
5. [ ] Let's Talk page (Calendly + simple form)
6. [ ] Quest site content (4815162342.quest)
7. [ ] Advanced Wheatley features (hunt hints, session memory, tab visibility)
8. [ ] Final polish + launch

## 💡 Ideas / Nice-to-Have
- Hunt meta-commentary from Wheatley during countdown
- Session memory (Wheatley remembers if you return)
- Tab visibility detection
- Easter eggs in console
- Custom plugin to add/edit photos to the desktop menu to use as a place to show off travels
- Wheatley drops Lost numbers sequence after 30+ minutes idle (IMPLEMENTED in hardcoded messages)