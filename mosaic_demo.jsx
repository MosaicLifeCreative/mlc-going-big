import { useState, useEffect, useRef, useCallback } from "react";

// ─── COLOR PALETTES ─────────────────────────────────────────
const PALETTES = {
  "Deep Tech": {
    orbs: ["#00D4FF", "#FF6B9D", "#0A2540", "#00D4FF"],
    primary: "#00D4FF",
    buttonGradient: "linear-gradient(135deg, #00D4FF, #FF6B9D)",
    buttonShadow: "rgba(0, 212, 255, 0.3)",
    botGradient: "linear-gradient(135deg, #00D4FF, #FF6B9D)",
    botAccentBg: "rgba(0, 212, 255, 0.15)",
    botAccentBorder: "rgba(0, 212, 255, 0.3)",
    botAccentText: "#67e8f9",
    icon: "linear-gradient(135deg, #00D4FF, #FF6B9D)",
    solidDark: "#0088a3",
  },
  "Electric Minimalist": {
    orbs: ["#6366F1", "#00E5FF", "#F59E0B", "#6366F1"],
    primary: "#00E5FF",
    buttonGradient: "linear-gradient(135deg, #6366F1, #00E5FF)",
    buttonShadow: "rgba(99, 102, 241, 0.3)",
    botGradient: "linear-gradient(135deg, #6366F1, #00E5FF)",
    botAccentBg: "rgba(99, 102, 241, 0.15)",
    botAccentBorder: "rgba(99, 102, 241, 0.3)",
    botAccentText: "#a5b4fc",
    icon: "linear-gradient(135deg, #6366F1, #00E5FF)",
    solidDark: "#4338ca",
  },
  "Royal Digital": {
    orbs: ["#7C3AED", "#06B6D4", "#EC4899", "#7C3AED"],
    primary: "#7C3AED",
    buttonGradient: "linear-gradient(135deg, #7C3AED, #06B6D4)",
    buttonShadow: "rgba(124, 58, 237, 0.3)",
    botGradient: "linear-gradient(135deg, #7C3AED, #EC4899)",
    botAccentBg: "rgba(124, 58, 237, 0.15)",
    botAccentBorder: "rgba(124, 58, 237, 0.3)",
    botAccentText: "#c4b5fd",
    icon: "linear-gradient(135deg, #7C3AED, #EC4899)",
    solidDark: "#5b21b6",
  },
};

// ─── WEIGHT PRESETS ─────────────────────────────────────────
const WEIGHT_PRESETS = {
  "Light": { heading: 300, body: 300, highlight: 600 },
  "Medium": { heading: 600, body: 300, highlight: 700 },
  "Bold": { heading: 700, body: 400, highlight: 700 },
  "Heavy": { heading: 700, body: 300, highlight: 800 },
};

// ─── STATIC FALLBACK PHASES ─────────────────────────────────
const STATIC_PHASES = [
  { text: "Hello.", duration: 2400, size: "clamp(48px, 10vw, 96px)", trackingRaw: -2, highlight: false, isHeading: true },
  { text: "We build websites that make people stop scrolling.", duration: 3400, size: "clamp(26px, 4.5vw, 48px)", trackingRaw: -0.5, highlight: false, isHeading: false },
  { text: "Built Different.", duration: 3000, size: "clamp(40px, 8vw, 80px)", trackingRaw: -1.5, highlight: true, isHeading: true },
  { text: "So how do you want yours to feel?", duration: null, size: "clamp(24px, 4vw, 42px)", trackingRaw: -0.5, highlight: false, isHeading: false },
];

// Visual structure — AI fills in .text, layout/timing is fixed
const PHASE_TEMPLATE = [
  { duration: 2800, size: "clamp(48px, 10vw, 96px)", trackingRaw: -2, highlight: false, isHeading: true },
  { duration: 3600, size: "clamp(26px, 4.5vw, 48px)", trackingRaw: -0.5, highlight: false, isHeading: false },
  { duration: 3200, size: "clamp(40px, 8vw, 80px)", trackingRaw: -1.5, highlight: true, isHeading: true },
  { duration: null, size: "clamp(24px, 4vw, 42px)", trackingRaw: -0.5, highlight: false, isHeading: false },
];

// ─── VISITOR SCENARIOS ──────────────────────────────────────
const VISITOR_SCENARIOS = {
  "Chamber Referral · New": {
    referral: "a link shared by the Grove City Area Chamber of Commerce",
    returning: false,
    timeOfDay: "Tuesday morning",
    description: "A local business owner who saw something posted by the Chamber and clicked through. They probably run a trades company or service business in the Columbus area.",
  },
  "Direct · Returning": {
    referral: "typing the URL directly into their browser",
    returning: true,
    timeOfDay: "Wednesday afternoon",
    description: "Someone who has visited before and remembers the site. They're coming back because something stuck with them last time.",
  },
  "Google · New · Evening": {
    referral: "a Google search",
    returning: false,
    timeOfDay: "Thursday evening",
    description: "A business owner researching web design options late at night. They've probably looked at 3-4 other sites already and are getting tired of the same generic stuff.",
  },
  "AmSpirit · New": {
    referral: "meeting someone at an AmSpirit Business Connections networking event",
    returning: false,
    timeOfDay: "Friday morning",
    description: "A business owner who exchanged cards at a networking event yesterday and is finally checking out the website the next morning.",
  },
  "Instagram · Curious": {
    referral: "an Instagram post or ad",
    returning: false,
    timeOfDay: "Saturday afternoon",
    description: "Someone scrolling casually on a weekend who stumbled across a post and tapped through out of curiosity. Not actively looking for anything — just intrigued.",
  },
};

const DELIMITER = "|||";

// ─── CHAT FLOWS ─────────────────────────────────────────────
const CHAT_FLOWS = {
  opener: { from: "bot", text: "Interesting choice. Tell me something — what made you click that?" },
  answers: {
    curious: [
      { from: "bot", text: "Hmm. That's honest. I like honest." },
      { from: "bot", text: "Most people just click things. You actually stopped and thought about it." },
      { from: "bot", text: "Let me ask you this — do you believe a website can actually change how people perceive a business?" },
    ],
    boredom: [
      { from: "bot", text: "Fair enough. Nothing wrong with that." },
      { from: "bot", text: "But you did click 'Interesting.' So something pulled you." },
      { from: "bot", text: "What if I told you there's more to this page than meets the eye?" },
    ],
    needing: [
      { from: "bot", text: "Good. Then you're in the right place." },
      { from: "bot", text: "But before we talk business — I want to know something." },
      { from: "bot", text: "What does your current website make you feel when you look at it?" },
    ],
  },
  deeper: {
    yes: [
      { from: "bot", text: "Then you already understand more than most." },
      { from: "bot", text: "Most people think a website is just a page with information on it." },
      { from: "bot", text: "It's not. It's the first conversation your business has with a stranger." },
      { from: "bot", text: "And right now, yours is boring." },
      { from: "bot", text: "Want to see what it could be instead?" },
    ],
    no: [
      { from: "bot", text: "Most people don't think so either." },
      { from: "bot", text: "They're wrong." },
      { from: "bot", text: "A website is the first conversation your business ever has with a stranger." },
      { from: "bot", text: "And that conversation either pulls them in... or loses them in 3 seconds." },
      { from: "bot", text: "Want to see the difference?" },
    ],
  },
  reward: [
    { from: "bot", text: "Smart." },
    { from: "bot", text: "Alright. You've earned it." },
    { from: "bot", text: "🎯 Welcome to the interesting side." },
  ],
};

const FONT = "'Plus Jakarta Sans', sans-serif";

// ─── AI STREAMING ───────────────────────────────────────────
async function generatePhases(scenario, onUpdate) {
  const s = VISITOR_SCENARIOS[scenario];

  const prompt = `You are writing a short, punchy 4-part opening text sequence for a website. The site belongs to Mosaic Life Creative — a Columbus, Ohio company that builds AI-powered websites and deploys AI chat agents for local service businesses like HVAC contractors and trades companies. The brand positioning is "Built Different." The tone is confident, slightly mysterious, never corporate. Think: the kind of thing that makes someone lean forward.

The person visiting arrived via: ${s.referral}
They are a: ${s.returning ? "returning visitor" : "first-time visitor"}
It is: ${s.timeOfDay}
Who they probably are: ${s.description}

Write exactly 4 phases of text. The sequence should feel like the page is talking directly to this person — aware of how they got here, aware of what they might be feeling. Each phase is displayed one at a time, large on screen, then fades before the next appears.

Phase 1 — A greeting or opening (1-3 words max). Not always "Hello." Make it feel personal to how they arrived. Could be a single word. Should feel like the page noticed them.

Phase 2 — One sentence, max 12 words. What Mosaic does or what's about to happen. Should feel relevant to this visitor's situation — not generic.

Phase 3 — A brand statement, 2-4 words. This is the visual centerpiece — it gets a gradient highlight treatment. Punchy. Memorable. The thing that sticks.

Phase 4 — A closing line that sets up a choice: talk to an AI, or leave. Under 15 words. Should feel like an invitation, not a pitch. Slightly playful.

Output ONLY the four text phases separated by ${DELIMITER} — nothing else. No labels, no quotes, no explanations.`;

  const response = await fetch("https://api.anthropic.com/v1/messages", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      model: "claude-sonnet-4-20250514",
      max_tokens: 300,
      stream: true,
      messages: [{ role: "user", content: prompt }],
    }),
  });

  if (!response.ok) {
    const err = await response.text();
    throw new Error(`API ${response.status}: ${err}`);
  }

  const reader = response.body.getReader();
  const decoder = new TextDecoder();
  let fullText = "";
  let lastEmittedParts = 0;

  while (true) {
    const { done, value } = await reader.read();
    if (done) break;

    const lines = decoder.decode(value, { continuous: true }).split("\n");
    for (const line of lines) {
      if (!line.startsWith("data: ")) continue;
      const raw = line.slice(6).trim();
      if (raw === "[DONE]") continue;

      try {
        const evt = JSON.parse(raw);
        if (evt.type === "content_block_delta" && evt.delta?.type === "text_delta") {
          fullText += evt.delta.text;

          // Parse current state of all phases
          const parts = fullText.split(DELIMITER);
          // Emit updates: completed phases get finalized, last part is live/streaming
          const phaseTexts = parts.map((p, i) => ({
            text: p.trim(),
            complete: i < parts.length - 1, // all but last are complete
          }));
          onUpdate(phaseTexts);
        }
      } catch (e) { /* skip */ }
    }
  }

  // Final emit
  const finalParts = fullText.split(DELIMITER);
  onUpdate(finalParts.map((p) => ({ text: p.trim(), complete: true })));
  return finalParts.length;
}

// ─── COMPONENTS ─────────────────────────────────────────────
function GradientOrb({ style }) {
  return <div style={{ position: "absolute", borderRadius: "50%", filter: "blur(80px)", opacity: 0.35, pointerEvents: "none", ...style }} />;
}

function TypingIndicator() {
  return (
    <div style={{ display: "flex", gap: 4, padding: "8px 0", alignItems: "center" }}>
      {[0, 1, 2].map((i) => (
        <div key={i} style={{ width: 8, height: 8, borderRadius: "50%", background: "#888", animation: `bounce 1.2s ease-in-out ${i * 0.2}s infinite` }} />
      ))}
    </div>
  );
}

function ChatBot({ onClose, palette }) {
  const [messages, setMessages] = useState([]);
  const [stage, setStage] = useState("open");
  const [typing, setTyping] = useState(false);
  const [options, setOptions] = useState(null);
  const bottomRef = useRef(null);
  const p = PALETTES[palette];

  const addMessages = (msgs, delay = 600) => {
    msgs.forEach((msg, i) => {
      setTimeout(() => {
        setTyping(true);
        setTimeout(() => {
          setMessages((prev) => [...prev, msg]);
          setTyping(false);
        }, 800 + Math.random() * 400);
      }, i * (1200 + Math.random() * 400) + delay);
    });
  };

  useEffect(() => {
    setTyping(true);
    setTimeout(() => {
      setMessages([CHAT_FLOWS.opener]);
      setTyping(false);
      setTimeout(() => {
        setOptions([
          { label: "I was just curious", value: "curious" },
          { label: "I'm bored", value: "boredom" },
          { label: "I actually need a website", value: "needing" },
        ]);
      }, 1200);
    }, 1000);
  }, []);

  useEffect(() => { bottomRef.current?.scrollIntoView({ behavior: "smooth" }); }, [messages, typing, options]);

  const handleOptionClick = (value) => {
    setOptions(null);
    const labels = { curious: "I was just curious", boredom: "I'm bored", needing: "I actually need a website" };
    setMessages((prev) => [...prev, { from: "user", text: labels[value] }]);
    const flow = CHAT_FLOWS.answers[value];
    addMessages(flow, 400);
    setTimeout(() => {
      setOptions([
        { label: "Yeah, I think it can.", value: "yes" },
        { label: "Honestly? No.", value: "no" },
      ]);
      setStage("deeper");
    }, flow.length * 1600 + 800);
  };

  const handleDeeperClick = (value) => {
    setOptions(null);
    setMessages((prev) => [...prev, { from: "user", text: value === "yes" ? "Yeah, I think it can." : "Honestly? No." }]);
    const flow = CHAT_FLOWS.deeper[value];
    addMessages(flow, 400);
    setTimeout(() => {
      setOptions([{ label: "Show me.", value: "reward" }]);
      setStage("reward");
    }, flow.length * 1600 + 600);
  };

  const handleRewardClick = () => {
    setOptions(null);
    setMessages((prev) => [...prev, { from: "user", text: "Show me." }]);
    addMessages(CHAT_FLOWS.reward, 400);
    setTimeout(() => setStage("done"), CHAT_FLOWS.reward.length * 1600 + 400);
  };

  const route = (value) => {
    if (stage === "open") handleOptionClick(value);
    else if (stage === "deeper") handleDeeperClick(value);
    else if (stage === "reward") handleRewardClick();
  };

  return (
    <div style={{ position: "fixed", inset: 0, background: "rgba(0,0,0,0.6)", display: "flex", alignItems: "flex-end", justifyContent: "center", zIndex: 100, animation: "fadeIn 0.3s ease" }}>
      <div style={{ width: "100%", maxWidth: 480, background: "#0a0a0a", borderRadius: "20px 20px 0 0", border: "1px solid #222", borderBottom: "none", display: "flex", flexDirection: "column", height: "75vh", maxHeight: 600 }}>
        <div style={{ padding: "16px 20px", borderBottom: "1px solid #1a1a1a", display: "flex", justifyContent: "space-between", alignItems: "center" }}>
          <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
            <div style={{ width: 32, height: 32, borderRadius: "50%", background: p.icon, display: "flex", alignItems: "center", justifyContent: "center", fontSize: 14 }}>✦</div>
            <div>
              <div style={{ color: "#fff", fontSize: 14, fontWeight: 600, fontFamily: FONT }}>Mosaic</div>
              <div style={{ color: "#555", fontSize: 11, fontFamily: FONT }}>Usually replies instantly</div>
            </div>
          </div>
          <button onClick={onClose} style={{ background: "none", border: "none", color: "#555", fontSize: 20, cursor: "pointer", padding: "4px 8px", borderRadius: 6 }}>×</button>
        </div>
        <div style={{ flex: 1, overflowY: "auto", padding: 20, display: "flex", flexDirection: "column", gap: 12 }}>
          {messages.map((msg, i) => (
            <div key={i} style={{ display: "flex", justifyContent: msg.from === "user" ? "flex-end" : "flex-start", animation: "slideUp 0.3s ease" }}>
              <div style={{
                maxWidth: "80%", padding: "10px 14px",
                borderRadius: msg.from === "user" ? "16px 16px 4px 16px" : "16px 16px 16px 4px",
                background: msg.from === "user" ? p.botGradient : "#1a1a1a",
                color: "#fff", fontSize: 14, lineHeight: 1.5, fontFamily: FONT,
              }}>
                {msg.text}
              </div>
            </div>
          ))}
          {typing && (
            <div style={{ display: "flex", justifyContent: "flex-start" }}>
              <div style={{ background: "#1a1a1a", borderRadius: "16px 16px 16px 4px", padding: "8px 14px" }}><TypingIndicator /></div>
            </div>
          )}
          {options && !typing && (
            <div style={{ display: "flex", flexDirection: "column", gap: 8, marginTop: 4, animation: "slideUp 0.3s ease" }}>
              {options.map((opt) => (
                <button key={opt.value} onClick={() => route(opt.value)} style={{
                  background: "transparent", border: "1px solid #333", color: "#ccc",
                  padding: "10px 16px", borderRadius: 12, cursor: "pointer", fontSize: 13,
                  textAlign: "left", fontFamily: FONT, transition: "all 0.2s ease",
                }}
                  onMouseEnter={(e) => { e.currentTarget.style.borderColor = p.primary; e.currentTarget.style.color = "#fff"; e.currentTarget.style.background = `${p.primary}15`; }}
                  onMouseLeave={(e) => { e.currentTarget.style.borderColor = "#333"; e.currentTarget.style.color = "#ccc"; e.currentTarget.style.background = "transparent"; }}
                >
                  {opt.label}
                </button>
              ))}
            </div>
          )}
          {stage === "done" && (
            <div style={{ marginTop: 16, padding: 16, background: p.botAccentBg, borderRadius: 12, border: `1px solid ${p.botAccentBorder}`, animation: "slideUp 0.4s ease" }}>
              <div style={{ color: p.botAccentText, fontSize: 13, fontFamily: FONT, marginBottom: 8 }}>This is where it gets real.</div>
              <div style={{ color: "#fff", fontSize: 13, fontFamily: FONT, lineHeight: 1.6 }}>In production, this is where the bot branches — adventure, services, or deeper into the mystery. This is just the proof of concept.</div>
            </div>
          )}
          <div ref={bottomRef} />
        </div>
      </div>
    </div>
  );
}

// ─── STYLED BUTTONS ────────────────────────────────────────
function getPrimaryButtonStyle(btnStyle, p, pressed) {
  const base = {
    padding: "14px 32px", borderRadius: 12, border: "none",
    color: "#fff", fontSize: 15, fontFamily: FONT, cursor: "pointer",
    fontWeight: 600, letterSpacing: "0.5px", userSelect: "none",
    WebkitUserSelect: "none",
  };
  switch (btnStyle) {
    case "Current":
      return { ...base, background: p.buttonGradient, boxShadow: pressed ? `0 2px 12px ${p.buttonShadow}` : `0 4px 24px ${p.buttonShadow}`, transform: pressed ? "translateY(1px)" : "translateY(0)", transition: "all 0.15s ease", animation: pressed ? "none" : "btnFloat 3s ease-in-out infinite" };
    case "Squishy":
      return { ...base, background: p.buttonGradient, borderRadius: 24, boxShadow: pressed ? `inset 0 4px 12px rgba(0,0,0,0.35), inset 0 2px 4px rgba(0,0,0,0.2), 0 1px 2px rgba(255,255,255,0.1)` : `0 6px 12px rgba(0,0,0,0.25), 0 2px 4px rgba(0,0,0,0.15), inset 0 1px 2px rgba(255,255,255,0.25), inset 0 -2px 4px rgba(0,0,0,0.15)`, transform: pressed ? "translateY(3px)" : "translateY(0)", transition: "all 0.12s cubic-bezier(0.23, 1, 0.32, 1)", filter: pressed ? "brightness(0.88)" : "brightness(1)" };
    case "Soft UI":
      return { ...base, color: "#333", fontWeight: 700, borderRadius: 14, boxShadow: pressed ? "inset 2px 2px 5px rgba(0,0,0,0.12), inset -1px -1px 3px rgba(255,255,255,0.8)" : "3px 3px 8px rgba(0,0,0,0.12), -2px -2px 6px rgba(255,255,255,0.9)", transform: pressed ? "translateY(1px)" : "translateY(0)", transition: "all 0.15s ease", background: pressed ? "#e4e4e8" : "#efeff2" };
    case "Raised":
      return { ...base, background: p.buttonGradient, borderRadius: 10, boxShadow: pressed ? `0 1px 0 ${p.solidDark}, 0 2px 8px ${p.buttonShadow}` : `0 4px 0 ${p.solidDark}, 0 6px 20px ${p.buttonShadow}`, transform: pressed ? "translateY(3px)" : "translateY(0)", transition: "all 0.1s cubic-bezier(0.4, 0, 0.2, 1)" };
    default: return base;
  }
}

function getSecondaryButtonStyle(btnStyle, pressed) {
  const base = { padding: "14px 32px", borderRadius: 12, background: "#fff", color: "#888", fontSize: 15, fontFamily: FONT, cursor: "pointer", letterSpacing: "0.5px", userSelect: "none", WebkitUserSelect: "none" };
  switch (btnStyle) {
    case "Current":
      return { ...base, border: "1px solid #ddd", boxShadow: "none", transform: "translateY(0)", transition: "all 0.25s ease" };
    case "Squishy":
      return { ...base, border: "none", borderRadius: 24, background: "#e8e8eb", color: "#666", boxShadow: pressed ? `inset 0 4px 10px rgba(0,0,0,0.2), inset 0 2px 3px rgba(0,0,0,0.12), 0 1px 1px rgba(255,255,255,0.5)` : `0 5px 10px rgba(0,0,0,0.15), 0 2px 3px rgba(0,0,0,0.1), inset 0 1px 2px rgba(255,255,255,0.7), inset 0 -1px 2px rgba(0,0,0,0.08)`, transform: pressed ? "translateY(3px)" : "translateY(0)", transition: "all 0.12s cubic-bezier(0.23, 1, 0.32, 1)", filter: pressed ? "brightness(0.92)" : "brightness(1)" };
    case "Soft UI":
      return { ...base, border: "none", borderRadius: 14, color: "#666", boxShadow: pressed ? "inset 2px 2px 5px rgba(0,0,0,0.1), inset -1px -1px 3px rgba(255,255,255,0.8)" : "3px 3px 8px rgba(0,0,0,0.1), -2px -2px 6px rgba(255,255,255,0.9)", background: pressed ? "#e6e6ea" : "#efeff2", transform: pressed ? "translateY(1px)" : "translateY(0)", transition: "all 0.15s ease" };
    case "Raised":
      return { ...base, border: "none", borderRadius: 10, color: "#666", boxShadow: pressed ? "0 1px 0 #c8c8c8, 0 2px 6px rgba(0,0,0,0.08)" : "0 3px 0 #c8c8c8, 0 5px 14px rgba(0,0,0,0.1)", transform: pressed ? "translateY(2px)" : "translateY(0)", transition: "all 0.1s cubic-bezier(0.4, 0, 0.2, 1)" };
    default: return base;
  }
}

// ─── MAIN APP ───────────────────────────────────────────────
export default function App() {
  const [phase, setPhase] = useState(0);
  const [seqState, setSeqState] = useState("showing");
  const [displayedPhase, setDisplayedPhase] = useState(0);
  const [showChoice, setShowChoice] = useState(false);
  const [showBot, setShowBot] = useState(false);
  const [clicked, setClicked] = useState(null);
  const [palette, setPalette] = useState("Royal Digital");
  const [weightPreset, setWeightPreset] = useState("Bold");
  const [btnStyle, setBtnStyle] = useState("Current");
  const [primaryPressed, setPrimaryPressed] = useState(false);
  const [secondaryPressed, setSecondaryPressed] = useState(false);

  // Hamburger nav
  const [navOpen, setNavOpen] = useState(false);
  const [hoveredNav, setHoveredNav] = useState(null);

  // Accordion state
  const [accordionAI, setAccordionAI] = useState(false);
  const [accordionBtn, setAccordionBtn] = useState(false);

  // Scavenger hunt countdown
  const [countdown, setCountdown] = useState({ hours: 0, mins: 0, secs: 0, active: false, window: false });
  const [showHuntButton, setShowHuntButton] = useState(false);
  const [showHuntModal, setShowHuntModal] = useState(false);
  const [huntInput, setHuntInput] = useState("");
  const [huntStatus, setHuntStatus] = useState("idle"); // idle | wrong | success
  const modalOpenRef = useRef(false); // track modal open state for countdown closure

  // Photos for nav panel — owned library, swap as needed
  const NAV_PHOTOS = [
    { url: "https://scontent-iad3-1.xx.fbcdn.net/v/t39.30808-6/569412307_10117774126594854_5213473671390533144_n.jpg?stp=cp6_dst-jpegr_tt6&_nc_cat=109&ccb=1-7&_nc_sid=cc71e4&_nc_ohc=nCMR1gKmvH8Q7kNvwFZse0M&_nc_oc=AdlzdKzc0CKXOkpmciMBY4R4-FY8jHWHdL0nnTfqMNKWfCMYuI6Bo1eLETyvkhLKg3w&_nc_zt=23&se=-1&_nc_ht=scontent-iad3-1.xx&_nc_gid=H5rnz8h4cD7KgdOkPnuOxA&oh=00_AfsqzmuMRrQjLSr7wp4op28FTplN83bW3-a7okGbyintvw&oe=6985C12A", caption: "Photo 01", credit: "MLC" },
    { url: "https://scontent-iad3-2.xx.fbcdn.net/v/t39.30808-6/530026145_10117173893731324_5757000082493040631_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=86c6b0&_nc_ohc=nGAAW0BN2aQQ7kNvwEiag5X&_nc_oc=AdlOwMXzuG6VzaSX5Kdt4pV90Uys5gESKFJRKOXVyTKwJ9td41_bqZcYYlJgRiEAU9c&_nc_zt=23&_nc_ht=scontent-iad3-2.xx&_nc_gid=doSNHFHdiyBc06EzH9_iXQ&oh=00_Afu6Kvj1HNxuPz4uAaKfeIX2Zk3Tj_BzQ18HmrBK9GXqzw&oe=6985BF8E", caption: "Photo 02", credit: "MLC" },
    { url: "https://scontent-iad3-1.xx.fbcdn.net/v/t39.30808-6/527448697_10117106745651614_2367060233084739950_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=127cfc&_nc_ohc=cRXvWprwuzsQ7kNvwFrGulj&_nc_oc=Adlx6BTY6nrML_Iw43TC3RvETyc9UTJdUqIG_c-ubpptbRzCQZKGZwovxVqb_ywQwuo&_nc_zt=23&_nc_ht=scontent-iad3-1.xx&_nc_gid=EHJ8LkZtGlN0P3kvb6m_L0&oh=00_AftwfUxd3XGI-3KndVSvuKVgbBUFUNZMBKaEKh_nxbQK6Q&oe=6985C96E", caption: "Photo 03", credit: "MLC" },
    { url: "https://scontent-iad3-1.xx.fbcdn.net/v/t39.30808-6/517410123_10116879846269794_4895946253043819767_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=Y4aSvfKwO_kQ7kNvwFlAp9j&_nc_oc=AdlybeOg0Bf_g5v0yjYeRIwJxKApjP0Ji-cUs_yOhkg86r0Jz0S16zyXQjLS5ioqSu0&_nc_zt=23&_nc_ht=scontent-iad3-1.xx&_nc_gid=7tF0wXu1cG4yetMzw2EjqQ&oh=00_AfvMep-0VFx8wHiKsj0eGv2afzAQHhdjqXz_v4W4R21R9Q&oe=698598E7", caption: "Photo 04", credit: "MLC" },
  ];

  // Nav pages
  const NAV_PAGES = [
    { label: "Home", icon: "⌂" },
    { label: "Website Design", icon: "✎" },
    { label: "Hosting", icon: "☁" },
    { label: "Maintenance", icon: "⚙" },
    { label: "Email Marketing", icon: "✉" },
    { label: "AI Chat Agents", icon: "✦" },
    { label: "About", icon: "◎" },
    { label: "Contact", icon: "→" },
  ];

  // Sync ref so countdown closure can check modal state
  useEffect(() => { modalOpenRef.current = showHuntModal; }, [showHuntModal]);

  // Countdown timer — targets 3:16:23 PM daily
  useEffect(() => {
    const tick = () => {
      const now = new Date();
      const target = new Date();
      target.setHours(15, 16, 23, 0);

      let diff = (target - now) / 1000;

      if (diff <= 0 && diff > -42) {
        // We're in the 42-second window
        setCountdown({ hours: 0, mins: 0, secs: 0, active: true, window: true });
        setShowHuntButton(true);
        return;
      }

      if (diff <= -42) {
        // Window expired — reset to next day
        target.setDate(target.getDate() + 1);
        diff = (target - now) / 1000;
        setShowHuntButton(false);
        // Only clear hunt state if modal isn't open (debug or otherwise)
        if (!modalOpenRef.current) {
          setHuntInput("");
          setHuntStatus("idle");
        }
      }

      const hours = Math.floor(diff / 3600);
      const mins = Math.floor((diff % 3600) / 60);
      const secs = Math.floor(diff % 60);
      setCountdown({ hours, mins, secs, active: true, window: false });
    };

    tick();
    const interval = setInterval(tick, 1000);
    return () => clearInterval(interval);
  }, []);

  // Hunt sequence validation
  const TARGET_SEQUENCE = "4815162342";
  const validateHunt = () => {
    if (huntInput === TARGET_SEQUENCE) {
      setHuntStatus("success");
    } else {
      setHuntStatus("wrong");
      setTimeout(() => setHuntStatus("idle"), 1200);
    }
  };

  // AI state
  const [aiOn, setAiOn] = useState(true);
  const [scenario, setScenario] = useState("Chamber Referral · New");
  const [phases, setPhases] = useState(STATIC_PHASES);
  const [aiStatus, setAiStatus] = useState("idle"); // idle | loading | streaming | done | error
  // Track which phase index is currently being streamed so we can show cursor
  const [activeStreamPhase, setActiveStreamPhase] = useState(-1);
  const abortRef = useRef(null);

  const p = PALETTES[palette];
  const w = WEIGHT_PRESETS[weightPreset];

  // Reset the sequence back to start
  const resetSequence = () => {
    setPhase(0);
    setDisplayedPhase(0);
    setSeqState("showing");
    setShowChoice(false);
    setShowBot(false);
    setClicked(null);
  };

  // Fire AI generation
  const runAI = useCallback(async () => {
    if (abortRef.current) abortRef.current.abort();
    abortRef.current = new AbortController();

    resetSequence();
    setAiStatus("loading");
    setActiveStreamPhase(0);
    // Start with empty phases so the state machine waits
    setPhases(PHASE_TEMPLATE.map((t) => ({ ...t, text: "" })));

    try {
      await generatePhases(scenario, (phaseUpdates) => {
        // phaseUpdates is array of { text, complete }
        // Build full phases array from template + streamed text
        const newPhases = PHASE_TEMPLATE.map((template, i) => ({
          ...template,
          text: phaseUpdates[i]?.text || "",
        }));
        setPhases(newPhases);

        // Figure out which phase is actively streaming (last incomplete one)
        const lastIncomplete = phaseUpdates.findIndex((p, i) => !p.complete && p.text.length > 0);
        const streamIdx = lastIncomplete >= 0 ? lastIncomplete : phaseUpdates.length - 1;
        setActiveStreamPhase(streamIdx);
        setAiStatus("streaming");
      });
      setAiStatus("done");
      setActiveStreamPhase(-1);
    } catch (e) {
      if (e.name === "AbortError") return;
      console.error("AI gen failed:", e);
      setAiStatus("error");
      setPhases(STATIC_PHASES);
      setActiveStreamPhase(-1);
    }
  }, [scenario]);

  // Switch between AI and static
  useEffect(() => {
    if (aiOn) {
      runAI();
    } else {
      if (abortRef.current) abortRef.current.abort();
      setPhases(STATIC_PHASES);
      setAiStatus("idle");
      setActiveStreamPhase(-1);
      resetSequence();
    }
  }, [aiOn, scenario]);

  // Sequence state machine — waits for text before advancing
  useEffect(() => {
    if (phase >= phases.length) return;
    const current = phases[phase];
    if (!current?.text) return; // wait for AI to fill this phase

    if (seqState === "showing") {
      if (current.duration === null) {
        // Last phase — show choice buttons after a beat
        const t = setTimeout(() => setShowChoice(true), 2200);
        return () => clearTimeout(t);
      }
      const t = setTimeout(() => setSeqState("hiding"), current.duration);
      return () => clearTimeout(t);
    }

    if (seqState === "hiding") {
      const t = setTimeout(() => {
        const next = phase + 1;
        if (next < phases.length) {
          setDisplayedPhase(next);
          setPhase(next);
          setTimeout(() => setSeqState("showing"), 60);
        }
      }, 550);
      return () => clearTimeout(t);
    }
  }, [phase, seqState, phases]);

  const skipIntro = () => {
    const last = phases.length - 1;
    setPhase(last);
    setDisplayedPhase(last);
    setSeqState("showing");
    setShowChoice(true);
  };

  const handleBold = () => { setClicked("bold"); setTimeout(() => setShowBot(true), 400); };
  const handleSafe = () => { setClicked("safe"); setTimeout(() => window.open("https://www.squarespace.com", "_blank"), 600); };

  const current = phases[displayedPhase] || PHASE_TEMPLATE[0];
  const isVisible = seqState === "showing" && !!current.text;
  // Show blinking cursor if this displayed phase is the one actively streaming
  const showCursor = aiOn && activeStreamPhase === displayedPhase && (aiStatus === "loading" || aiStatus === "streaming");

  const getFontWeight = () => {
    if (current.highlight) return w.highlight;
    if (current.isHeading) return w.heading;
    return w.body;
  };

  const BUTTON_STYLES = ["Current", "Squishy", "Soft UI", "Raised"];

  return (
    <div style={{ minHeight: "100vh", background: "#fafafa", position: "relative", overflow: "hidden", display: "flex", alignItems: "center", justifyContent: "center" }}>
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap');
        @keyframes float1 { 0% { transform:translate(0,0) scale(1); } 30% { transform:translate(80px,-60px) scale(1.08); } 60% { transform:translate(-40px,30px) scale(0.95); } 100% { transform:translate(0,0) scale(1); } }
        @keyframes float2 { 0% { transform:translate(0,0) scale(1); } 25% { transform:translate(-90px,70px) scale(0.92); } 55% { transform:translate(50px,-50px) scale(1.06); } 100% { transform:translate(0,0) scale(1); } }
        @keyframes float3 { 0% { transform:translate(0,0) scale(1); } 35% { transform:translate(70px,80px) scale(1.04); } 65% { transform:translate(-60px,-40px) scale(0.94); } 100% { transform:translate(0,0) scale(1); } }
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes bounce { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-6px); } }
        @keyframes btnFloat { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-3px); } }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0; } }
        @keyframes hamburgerSpin { to { transform: rotate(360deg); } }
        @keyframes hamburgerBreath { 0%,100% { opacity:0.7; } 50% { opacity:1; } }
        * { box-sizing:border-box; margin:0; padding:0; }
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#333; border-radius:2px; }
      `}</style>

      {/* Gradient Orbs */}
      <GradientOrb style={{ width: 500, height: 500, top: "-10%", left: "-15%", background: p.orbs[0], animation: "float1 8s ease-in-out infinite", transition: "background 0.8s ease" }} />
      <GradientOrb style={{ width: 400, height: 400, bottom: "-5%", right: "-10%", background: p.orbs[1], animation: "float2 10s ease-in-out infinite", transition: "background 0.8s ease" }} />
      <GradientOrb style={{ width: 300, height: 300, top: "50%", left: "60%", background: p.orbs[2], animation: "float3 12s ease-in-out infinite", transition: "background 0.8s ease" }} />
      <GradientOrb style={{ width: 200, height: 200, top: "20%", right: "20%", background: p.orbs[3], animation: "float1 9s ease-in-out infinite 2s", transition: "background 0.8s ease" }} />

      {/* Hamburger button — frosted glass pill, spinning conic border, magnetic + morph */}
      <div
        ref={el => {
          if (el && !el._bound) {
            el._bound = true;
            el.addEventListener("mousemove", (e) => {
              const rect = el.getBoundingClientRect();
              const cx = rect.left + rect.width / 2;
              const cy = rect.top + rect.height / 2;
              const dx = (e.clientX - cx) / rect.width * 6; // max ±6px
              const dy = (e.clientY - cy) / rect.height * 6;
              el.style.transform = `translate(${dx}px, ${dy}px) scale(1.08)`;
            });
            el.addEventListener("mouseleave", () => {
              el.style.transform = "translate(0,0) scale(1)";
            });
          }
        }}
        onClick={() => setNavOpen(true)}
        style={{
          position: "fixed", top: 22, right: 22, zIndex: 50,
          width: 44, height: 44, borderRadius: 14,
          padding: 1.5,
          background: "conic-gradient(from var(--spin-angle, 0deg), #7C3AED, #06B6D4, #EC4899, #7C3AED)",
          animation: "hamburgerSpin 4s linear infinite",
          cursor: "pointer",
          transition: "transform 0.15s cubic-bezier(0.25, 0.46, 0.45, 0.94)",
        }}
        onMouseEnter={e => {
          e.currentTarget.style.animation = "hamburgerSpin 1.2s linear infinite";
          const inner = e.currentTarget.querySelector(".hb-inner");
          if (inner) inner.style.background = "rgba(255,255,255,0.75)";
          // Morph lines toward X
          const lines = e.currentTarget.querySelectorAll(".hb-line");
          if (lines[0]) { lines[0].style.width = "18px"; lines[0].style.transform = "rotate(45deg) translateX(3px) translateY(3px)"; }
          if (lines[1]) { lines[1].style.opacity = "0"; lines[1].style.transform = "scaleX(0)"; }
          if (lines[2]) { lines[2].style.width = "18px"; lines[2].style.transform = "rotate(-45deg) translateX(3px) translateY(-3px)"; }
        }}
        onMouseLeave={e => {
          e.currentTarget.style.animation = "hamburgerSpin 4s linear infinite";
          e.currentTarget.style.transform = "translate(0,0) scale(1)";
          const inner = e.currentTarget.querySelector(".hb-inner");
          if (inner) inner.style.background = "rgba(255,255,255,0.58)";
          const lines = e.currentTarget.querySelectorAll(".hb-line");
          if (lines[0]) { lines[0].style.width = "20px"; lines[0].style.transform = "none"; }
          if (lines[1]) { lines[1].style.opacity = "0.6"; lines[1].style.transform = "none"; }
          if (lines[2]) { lines[2].style.width = "20px"; lines[2].style.transform = "none"; }
        }}
      >
        <div className="hb-inner" style={{
          width: "100%", height: "100%", borderRadius: 12.5,
          background: "rgba(255,255,255,0.58)",
          backdropFilter: "blur(14px)",
          display: "flex", flexDirection: "column",
          alignItems: "center", justifyContent: "center", gap: 5,
          transition: "background 0.25s ease",
          animation: "hamburgerBreath 3s ease-in-out infinite",
        }}>
          <div className="hb-line" style={{ width: 20, height: 1.5, borderRadius: 1, background: "linear-gradient(90deg, #7C3AED, #06B6D4)", transition: "all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94)", transformOrigin: "center" }} />
          <div className="hb-line" style={{ width: 14, height: 1.5, borderRadius: 1, background: "linear-gradient(90deg, #7C3AED, #06B6D4)", opacity: 0.6, transition: "all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94)", transformOrigin: "center" }} />
          <div className="hb-line" style={{ width: 20, height: 1.5, borderRadius: 1, background: "linear-gradient(90deg, #7C3AED, #06B6D4)", transition: "all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94)", transformOrigin: "center" }} />
        </div>
      </div>

      {/* Nav overlay */}
      {navOpen && (
        <div style={{
          position: "fixed", inset: 0, zIndex: 90,
          background: "rgba(14,14,18,0.97)", backdropFilter: "blur(20px)",
          display: "flex", flexDirection: "row", alignItems: "stretch",
          animation: "fadeIn 0.25s ease",
        }}>
          {/* Close */}
          <div
            onClick={() => setNavOpen(false)}
            style={{
              position: "absolute", top: 24, right: 28, zIndex: 10,
              color: "#555", fontSize: 26, cursor: "pointer", transition: "color 0.2s",
              width: 32, height: 32, display: "flex", alignItems: "center", justifyContent: "center",
            }}
            onMouseEnter={e => e.currentTarget.style.color = "#fff"}
            onMouseLeave={e => e.currentTarget.style.color = "#555"}
          >
            ×
          </div>

          {/* Left — nav items */}
          <div style={{
            flex: "0 0 55%", maxFlex: 520,
            display: "flex", flexDirection: "column", justifyContent: "center",
            padding: "60px 64px",
          }}>
            {/* Brand mark */}
            <div style={{ fontSize: 10, letterSpacing: 3, color: "#3a3a48", textTransform: "uppercase", fontFamily: FONT, marginBottom: 48 }}>
              Mosaic Life Creative
            </div>

            <nav style={{ display: "flex", flexDirection: "column", gap: 4 }}>
              {NAV_PAGES.map((page, i) => (
                <div
                  key={page.label}
                  onMouseEnter={() => setHoveredNav(i)}
                  onMouseLeave={() => setHoveredNav(null)}
                  style={{
                    display: "flex", alignItems: "baseline", gap: 16,
                    padding: "10px 0", cursor: "pointer",
                    opacity: 0, animation: `slideUp 0.35s ease ${i * 0.07}s forwards`,
                  }}
                >
                  <span style={{
                    fontSize: 11, fontFamily: FONT, color: hoveredNav === i ? "#7C3AED" : "#3a3a48",
                    width: 24, textAlign: "right", transition: "color 0.2s", flexShrink: 0,
                  }}>
                    {String(i + 1).padStart(2, "0")}
                  </span>
                  <span style={{
                    fontFamily: FONT,
                    fontSize: "clamp(32px, 5vw, 52px)",
                    fontWeight: hoveredNav === i ? 700 : 300,
                    color: hoveredNav === i ? "#fff" : "#9a9aa8",
                    letterSpacing: "-1px",
                    lineHeight: 1.1,
                    transition: "color 0.25s ease, font-weight 0.25s ease",
                  }}>
                    {page.label}
                  </span>
                </div>
              ))}
            </nav>
          </div>

          {/* Right — photo panel (desktop only, hidden on narrow) */}
          <div style={{
            flex: 1, position: "relative", overflow: "hidden",
            minWidth: 0,
          }}>
            {NAV_PHOTOS.map((photo, i) => (
              <div
                key={i}
                style={{
                  position: "absolute", inset: 0,
                  backgroundImage: `url(${photo.url})`,
                  backgroundSize: "cover",
                  backgroundPosition: "center",
                  opacity: hoveredNav !== null && (hoveredNav % NAV_PHOTOS.length) === i ? 1 : 0,
                  transition: "opacity 0.5s ease",
                  zIndex: hoveredNav !== null && (hoveredNav % NAV_PHOTOS.length) === i ? 1 : 0,
                }}
              />
            ))}
            {/* Default image when nothing hovered */}
            <div style={{
              position: "absolute", inset: 0,
              backgroundImage: `url(${NAV_PHOTOS[0].url})`,
              backgroundSize: "cover",
              backgroundPosition: "center",
              opacity: hoveredNav === null ? 0.4 : 0,
              transition: "opacity 0.4s ease",
              zIndex: 0,
            }} />
            {/* Gradient overlay left edge to blend into nav */}
            <div style={{
              position: "absolute", inset: 0, zIndex: 2,
              background: "linear-gradient(to right, rgba(14,14,18,0.97) 0%, rgba(14,14,18,0.3) 40%, transparent 70%)",
            }} />
            {/* Caption */}
            {hoveredNav !== null && (
              <div style={{
                position: "absolute", bottom: 40, left: 40, zIndex: 3,
                animation: "fadeIn 0.3s ease",
              }}>
                <div style={{ color: "#fff", fontSize: 13, fontFamily: FONT, fontWeight: 500, opacity: 0.9 }}>
                  {NAV_PHOTOS[hoveredNav % NAV_PHOTOS.length]?.caption}
                </div>
                <div style={{ color: "#555", fontSize: 11, fontFamily: FONT, marginTop: 4 }}>
                  {NAV_PHOTOS[hoveredNav % NAV_PHOTOS.length]?.credit}
                </div>
              </div>
            )}
          </div>
        </div>
      )}


      {/* Controls Panel — accordion */}
      <div style={{ position: "fixed", top: 20, left: 20, zIndex: 50, display: "flex", flexDirection: "column", gap: 8, maxHeight: "calc(100vh - 40px)", overflowY: "auto", paddingBottom: 20, width: 240 }}>

        {/* ── AI Controls (accordion) ── */}
        <div style={{ background: "rgba(255,255,255,0.92)", backdropFilter: "blur(12px)", borderRadius: 12, border: "1px solid #eee", boxShadow: "0 4px 20px rgba(0,0,0,0.08)", overflow: "hidden" }}>
          {/* Header */}
          <div
            onClick={() => setAccordionAI(prev => !prev)}
            style={{ display: "flex", alignItems: "center", justifyContent: "space-between", padding: "10px 14px", cursor: "pointer", userSelect: "none" }}
          >
            <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
              <span style={{ fontSize: 9, color: accordionAI ? "#7C3AED" : "#aaa", transition: "color 0.2s" }}>▶</span>
              <span style={{ fontSize: 10, textTransform: "uppercase", letterSpacing: "1.5px", color: "#999", fontFamily: FONT, fontWeight: 600 }}>AI Text</span>
            </div>
            <div style={{ display: "flex", alignItems: "center", gap: 6 }}>
              {aiStatus === "loading" && <span style={{ fontSize: 9, color: "#aaa", fontFamily: FONT, fontStyle: "italic" }}>connecting…</span>}
              {aiStatus === "streaming" && <span style={{ fontSize: 9, color: "#7C3AED", fontFamily: FONT, fontStyle: "italic" }}>streaming…</span>}
              {aiStatus === "error" && <span style={{ fontSize: 9, color: "#e55", fontFamily: FONT, fontWeight: 600 }}>error</span>}
              <div onClick={(e) => { e.stopPropagation(); setAiOn(!aiOn); }} style={{ width: 34, height: 19, borderRadius: 10, background: aiOn ? "#7C3AED" : "#ccc", position: "relative", cursor: "pointer", transition: "background 0.3s" }}>
                <div style={{ width: 15, height: 15, borderRadius: "50%", background: "#fff", position: "absolute", top: 2, left: aiOn ? 17 : 2, transition: "left 0.3s", boxShadow: "0 1px 3px rgba(0,0,0,0.2)" }} />
              </div>
            </div>
          </div>
          {/* Body */}
          {accordionAI && aiOn && (
            <div style={{ padding: "0 14px 12px", display: "flex", flexDirection: "column", gap: 4 }}>
              {Object.keys(VISITOR_SCENARIOS).map((name) => {
                const active = scenario === name;
                return (
                  <button key={name} onClick={() => setScenario(name)} style={{
                    padding: "5px 10px", borderRadius: 8, textAlign: "left",
                    border: active ? "2px solid #7C3AED" : "2px solid transparent",
                    background: active ? "#7C3AED12" : "#f5f5f5", cursor: "pointer", transition: "all 0.2s",
                  }}>
                    <span style={{ fontSize: 11, color: active ? "#222" : "#555", fontFamily: FONT, fontWeight: active ? 600 : 400 }}>{name}</span>
                  </button>
                );
              })}
              <button onClick={runAI} style={{
                marginTop: 4, width: "100%", padding: "6px 0", borderRadius: 8, border: "1px solid #7C3AED",
                background: "transparent", color: "#7C3AED", fontSize: 11, fontFamily: FONT, fontWeight: 600,
                cursor: "pointer", letterSpacing: "0.5px", transition: "background 0.2s",
              }}
                onMouseEnter={(e) => (e.currentTarget.style.background = "#7C3AED12")}
                onMouseLeave={(e) => (e.currentTarget.style.background = "transparent")}
              >
                ↻ Regenerate
              </button>
            </div>
          )}
        </div>

        {/* ── Button Style (accordion) ── */}
        <div style={{ background: "rgba(255,255,255,0.92)", backdropFilter: "blur(12px)", borderRadius: 12, border: "1px solid #eee", boxShadow: "0 4px 20px rgba(0,0,0,0.08)", overflow: "hidden" }}>
          <div
            onClick={() => setAccordionBtn(prev => !prev)}
            style={{ display: "flex", alignItems: "center", justifyContent: "space-between", padding: "10px 14px", cursor: "pointer", userSelect: "none" }}
          >
            <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
              <span style={{ fontSize: 9, color: accordionBtn ? "#7C3AED" : "#aaa", transition: "color 0.2s" }}>▶</span>
              <span style={{ fontSize: 10, textTransform: "uppercase", letterSpacing: "1.5px", color: "#999", fontFamily: FONT, fontWeight: 600 }}>Button Style</span>
            </div>
            <span style={{ fontSize: 10, color: "#aaa", fontFamily: FONT }}>{btnStyle}</span>
          </div>
          {accordionBtn && (
            <div style={{ padding: "0 14px 12px", display: "flex", gap: 6, flexWrap: "wrap" }}>
              {BUTTON_STYLES.map((name) => {
                const active = btnStyle === name;
                return (
                  <button key={name} onClick={() => setBtnStyle(name)} style={{ padding: "5px 10px", borderRadius: 8, border: active ? "2px solid #7C3AED" : "2px solid transparent", background: active ? "#7C3AED12" : "#f5f5f5", cursor: "pointer", transition: "all 0.2s" }}>
                    <span style={{ fontSize: 11, color: active ? "#222" : "#444", fontFamily: FONT, fontWeight: active ? 600 : 400 }}>{name}</span>
                  </button>
                );
              })}
            </div>
          )}
        </div>

        {/* ── Debug ── */}
        <div style={{ background: "rgba(255,255,255,0.92)", backdropFilter: "blur(12px)", borderRadius: 12, border: "1px solid #eee", boxShadow: "0 4px 20px rgba(0,0,0,0.08)", padding: "10px 14px", display: "flex", gap: 6, flexWrap: "wrap" }}>
          <button onClick={() => { setShowHuntModal(true); setShowHuntButton(true); }} style={{
            padding: "5px 10px", borderRadius: 8, border: "1px solid #EC4899",
            background: "transparent", color: "#EC4899", fontSize: 10, fontFamily: FONT,
            fontWeight: 600, cursor: "pointer", letterSpacing: "0.5px", transition: "background 0.2s",
          }}
            onMouseEnter={e => e.currentTarget.style.background = "#EC489912"}
            onMouseLeave={e => e.currentTarget.style.background = "transparent"}
          >
            ◇ Hunt Modal
          </button>
          {!showChoice && (
            <button onClick={skipIntro} style={{
              padding: "5px 10px", borderRadius: 8, border: "1px solid #ccc",
              background: "transparent", color: "#999", fontSize: 10, fontFamily: FONT,
              cursor: "pointer", letterSpacing: "0.5px", transition: "background 0.2s",
            }}
              onMouseEnter={e => e.currentTarget.style.background = "#00000008"}
              onMouseLeave={e => e.currentTarget.style.background = "transparent"}
            >
              skip intro →
            </button>
          )}
        </div>
      </div>

      {/* ── Main Content ── */}
      <div style={{ position: "relative", zIndex: 1, textAlign: "center", padding: 20 }}>
        <div style={{ height: 220, display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center" }}>
          <div style={{ opacity: isVisible ? 1 : 0, transform: isVisible ? "translateY(0)" : "translateY(-10px)", transition: "opacity 0.5s ease, transform 0.5s ease" }}>
            <h1 style={{
              color: "#111", fontSize: current.size, fontWeight: getFontWeight(),
              letterSpacing: `${current.trackingRaw}px`, lineHeight: 1.2, maxWidth: 750, fontFamily: FONT,
              background: current.highlight ? p.buttonGradient : "none",
              WebkitBackgroundClip: current.highlight ? "text" : "unset",
              WebkitTextFillColor: current.highlight ? "transparent" : "unset",
            }}>
              {current.text}
              {/* Blinking cursor while this phase is actively streaming from AI */}
              {showCursor && (
                <span style={{
                  display: "inline-block", width: 3,
                  height: current.isHeading ? "0.82em" : "0.78em",
                  background: current.highlight ? "#7C3AED" : "#111",
                  marginLeft: 6, verticalAlign: "middle",
                  animation: "blink 1s step-end infinite",
                }} />
              )}
            </h1>
          </div>
        </div>

        {/* Choice Buttons */}
        {showChoice && !clicked && (
          <div style={{ display: "flex", gap: 20, justifyContent: "center", marginTop: 32, animation: "slideUp 0.5s ease", flexWrap: "wrap", alignItems: "center" }}>
            {/* Secondary — "Like everyone else's" */}
            {btnStyle === "Squishy" ? (
              <div style={{ padding: "10px 12px", borderRadius: 16, background: "#d4d4d8", boxShadow: "inset 0 3px 8px rgba(0,0,0,0.22), inset 0 1px 2px rgba(0,0,0,0.15), 0 1px 1px rgba(255,255,255,0.4)" }}>
                <button onClick={handleSafe} style={getSecondaryButtonStyle(btnStyle, secondaryPressed)}
                  onMouseDown={() => setSecondaryPressed(true)} onMouseUp={() => setSecondaryPressed(false)} onMouseLeave={() => setSecondaryPressed(false)}>
                  Like everyone else's
                </button>
              </div>
            ) : (
              <button onClick={handleSafe} style={getSecondaryButtonStyle(btnStyle, secondaryPressed)}
                onMouseDown={() => setSecondaryPressed(true)} onMouseUp={() => setSecondaryPressed(false)} onMouseLeave={() => setSecondaryPressed(false)}>
                Like everyone else's
              </button>
            )}

            {/* Primary — "Like nothing else" */}
            {btnStyle === "Squishy" ? (
              <div style={{ padding: "10px 12px", borderRadius: 16, background: "#d4d4d8", boxShadow: "inset 0 3px 8px rgba(0,0,0,0.22), inset 0 1px 2px rgba(0,0,0,0.15), 0 1px 1px rgba(255,255,255,0.4)" }}>
                <button onClick={handleBold} style={getPrimaryButtonStyle(btnStyle, p, primaryPressed)}
                  onMouseDown={() => setPrimaryPressed(true)} onMouseUp={() => setPrimaryPressed(false)} onMouseLeave={() => setPrimaryPressed(false)}>
                  Like nothing else
                </button>
              </div>
            ) : (
              <button onClick={handleBold} style={getPrimaryButtonStyle(btnStyle, p, primaryPressed)}
                onMouseDown={() => setPrimaryPressed(true)} onMouseUp={() => setPrimaryPressed(false)} onMouseLeave={() => setPrimaryPressed(false)}>
                Like nothing else
              </button>
            )}
          </div>
        )}

        {clicked && !showBot && (
          <div style={{ marginTop: 32, color: "#aaa", fontSize: 14, fontFamily: FONT, animation: "fadeIn 0.3s ease" }}>
            {clicked === "bold" ? "Opening..." : "Redirecting..."}
          </div>
        )}

        {!showChoice && (
          <div style={{ marginTop: 40, color: "#bbb", fontSize: 12, fontFamily: FONT, letterSpacing: "2px", textTransform: "uppercase" }}>
            or just scroll
          </div>
        )}
      </div>

      {/* Footer — subtle countdown */}
      <div style={{
        position: "fixed", bottom: 0, left: 0, right: 0, zIndex: 10,
        padding: "12px 24px", display: "flex", alignItems: "center", justifyContent: "space-between",
        pointerEvents: "none",
      }}>
        {/* Left: brand mark */}
        <div style={{ color: "#bbb", fontSize: 10, fontFamily: FONT, letterSpacing: 2, textTransform: "uppercase", opacity: 0.5 }}>
          MLC
        </div>

        {/* Right: countdown */}
        <div style={{ display: "flex", alignItems: "center", gap: 12 }}>
          {showHuntButton && !showHuntModal && (
            <button
              onClick={() => { setShowHuntModal(true); }}
              style={{
                pointerEvents: "auto",
                background: "rgba(124,58,237,0.12)", border: "1px solid rgba(124,58,237,0.3)",
                borderRadius: 6, padding: "4px 12px", color: "#7C3AED",
                fontSize: 10, fontFamily: FONT, letterSpacing: 1, cursor: "pointer",
                animation: "fadeIn 0.4s ease", transition: "background 0.2s",
              }}
              onMouseEnter={e => e.currentTarget.style.background = "rgba(124,58,237,0.22)"}
              onMouseLeave={e => e.currentTarget.style.background = "rgba(124,58,237,0.12)"}
            >
              ● ENTER
            </button>
          )}
          <div style={{
            color: countdown.window ? "#EC4899" : "#999",
            fontSize: 11, fontFamily: "'SF Mono', 'Fira Code', monospace",
            letterSpacing: 1, opacity: countdown.window ? 1 : 0.45,
            transition: "color 0.3s, opacity 0.3s",
          }}>
            {String(countdown.hours).padStart(2, "0")}:{String(countdown.mins).padStart(2, "0")}:{String(countdown.secs).padStart(2, "0")}
          </div>
        </div>
      </div>

      {/* Scavenger Hunt Modal */}
      {showHuntModal && (
        <div style={{
          position: "fixed", inset: 0, zIndex: 95,
          background: "rgba(0,0,0,0.7)", backdropFilter: "blur(8px)",
          display: "flex", alignItems: "center", justifyContent: "center",
          animation: "fadeIn 0.2s ease",
        }}>
          <div style={{
            position: "relative",
            background: "#12121a", border: "1px solid #2a2a38", borderRadius: 16,
            padding: "36px 40px", width: 360, maxWidth: "90vw",
            boxShadow: "0 20px 60px rgba(0,0,0,0.5)",
            animation: huntStatus === "wrong" ? "shake 0.4s ease" : "slideUp 0.25s ease",
          }}>
            {/* Close — only if not in the middle of success */}
            {huntStatus !== "success" && (
              <div
                onClick={() => { setShowHuntModal(false); setHuntInput(""); setHuntStatus("idle"); }}
                style={{ position: "absolute", top: 16, right: 20, color: "#555", fontSize: 20, cursor: "pointer" }}
              >×</div>
            )}

            {huntStatus !== "success" ? (
              <>
                <div style={{ fontSize: 10, letterSpacing: 3, color: "#555", textTransform: "uppercase", fontFamily: FONT, marginBottom: 16 }}>
                  Sequence
                </div>
                <div style={{ fontSize: 14, color: "#aaa", fontFamily: FONT, marginBottom: 24, lineHeight: 1.6 }}>
                  Enter the numbers.
                </div>
                <input
                  autoFocus
                  type="text"
                  inputMode="numeric"
                  value={huntInput}
                  onChange={e => { setHuntInput(e.target.value.replace(/\D/g, "")); setHuntStatus("idle"); }}
                  onKeyDown={e => { if (e.key === "Enter") validateHunt(); }}
                  placeholder="__________"
                  style={{
                    width: "100%", background: "#1e1e28", border: `1px solid ${huntStatus === "wrong" ? "#EC4899" : "#333"}`,
                    borderRadius: 8, padding: "12px 16px", color: "#fff",
                    fontFamily: "'SF Mono', 'Fira Code', monospace", fontSize: 18, letterSpacing: 4,
                    outline: "none", transition: "border-color 0.2s",
                  }}
                />
                {huntStatus === "wrong" && (
                  <div style={{ marginTop: 10, color: "#EC4899", fontSize: 11, fontFamily: FONT, letterSpacing: 1 }}>
                    Incorrect.
                  </div>
                )}
                <button
                  onClick={validateHunt}
                  style={{
                    marginTop: 20, width: "100%", padding: "10px 0", borderRadius: 8,
                    background: "linear-gradient(135deg, #7C3AED, #6d28d9)", border: "none",
                    color: "#fff", fontSize: 12, fontFamily: FONT, fontWeight: 600,
                    letterSpacing: 1.5, cursor: "pointer", textTransform: "uppercase",
                    boxShadow: "0 4px 16px rgba(124,58,237,0.3)",
                  }}
                >
                  Submit
                </button>
              </>
            ) : (
              <div style={{ textAlign: "center" }}>
                <div style={{ fontSize: 28, marginBottom: 16 }}>✓</div>
                <div style={{ color: "#06B6D4", fontSize: 14, fontFamily: FONT, letterSpacing: 1, marginBottom: 8 }}>
                  ACCESS GRANTED
                </div>
                <div style={{ color: "#555", fontSize: 11, fontFamily: FONT }}>
                  Redirecting to 4815162342.quest...
                </div>
              </div>
            )}
          </div>

          {/* Shake keyframes for modal */}
          <style>{`
            @keyframes shake {
              0%, 100% { transform: translateX(0); }
              20% { transform: translateX(-8px); }
              40% { transform: translateX(8px); }
              60% { transform: translateX(-5px); }
              80% { transform: translateX(5px); }
            }
          `}</style>
        </div>
      )}


      {showBot && <ChatBot onClose={() => setShowBot(false)} palette={palette} />}
    </div>
  );
}
