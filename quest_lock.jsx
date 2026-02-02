import { useState, useRef, useCallback, useEffect } from "react";

// ─── CONFIG ───────────────────────────────────────
const TARGET = [2, 2, 3, 7];
const DIGITS = [0,1,2,3,4,5,6,7,8,9];
const WHEEL_HEIGHT = 56; // px per digit slot

// ─── ROYAL DIGITAL PALETTE ────────────────────────
const THEME = {
  bg: "#18181B",
  surface: "#1e1e24",
  surfaceHi: "#27272a",
  border: "#3f3f46",
  text: "#FAFAFA",
  textDim: "#71717a",
  accent: "#7C3AED",
  accentDim: "#6d28d9",
  accentSecondary: "#06B6D4",
  accentPink: "#EC4899",
  wheelBg: "#1e1e24",
  wheelBorder: "#3f3f46",
  digitActive: "#FAFAFA",
  digitInactive: "#52525b",
  success: "#06B6D4",
  error: "#EC4899",
};

// ─── SINGLE WHEEL ─────────────────────────────────
function Wheel({ value, onChange, index }) {
  const touchStart = useRef(null);
  const dragStart = useRef(null);
  const containerRef = useRef(null);
  const animating = useRef(false);

  const wrap = (n) => ((n % 10) + 10) % 10;

  // Smoothly animate to target value
  const snapTo = useCallback((target) => {
    animating.current = true;
    const start = performance.now();
    const duration = 180;
    const from = value;
    const diff = target - from;
    // Normalize diff to shortest path
    let d = diff;
    if (d > 5) d -= 10;
    if (d < -5) d += 10;

    const animate = (now) => {
      const t = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - t, 3); // ease-out cubic
      const current = wrap(Math.round(from + d * eased));
      onChange(current);
      if (t < 1) {
        requestAnimationFrame(animate);
      } else {
        onChange(wrap(target));
        animating.current = false;
      }
    };
    requestAnimationFrame(animate);
  }, [value, onChange]);

  const getDelta = (clientY) => {
    if (dragStart.current === null) return 0;
    return Math.round((dragStart.current - clientY) / (WHEEL_HEIGHT * 0.7));
  };

  // Mouse
  const onMouseDown = (e) => {
    e.preventDefault();
    dragStart.current = e.clientY;
    touchStart.current = value;
  };
  const onMouseMove = (e) => {
    if (dragStart.current === null) return;
    const delta = getDelta(e.clientY);
    if (delta !== 0) {
      onChange(wrap(touchStart.current + delta));
    }
  };
  const onMouseUp = () => {
    dragStart.current = null;
    touchStart.current = null;
  };

  // Touch
  const onTouchStart = (e) => {
    dragStart.current = e.touches[0].clientY;
    touchStart.current = value;
  };
  const onTouchMove = (e) => {
    e.preventDefault();
    if (dragStart.current === null) return;
    const delta = getDelta(e.touches[0].clientY);
    if (delta !== 0) {
      onChange(wrap(touchStart.current + delta));
    }
  };
  const onTouchEnd = () => {
    dragStart.current = null;
    touchStart.current = null;
  };

  // Scroll wheel
  const onWheel = (e) => {
    e.preventDefault();
    if (animating.current) return;
    const delta = e.deltaY > 0 ? 1 : -1;
    snapTo(wrap(value + delta));
  };

  // Arrow clicks
  const up = () => { if (!animating.current) snapTo(wrap(value - 1)); };
  const down = () => { if (!animating.current) snapTo(wrap(value + 1)); };

  // Build visible digits: show 5 (2 above, active, 2 below)
  const visibleDigits = [];
  for (let i = -2; i <= 2; i++) {
    visibleDigits.push({
      digit: wrap(value + i),
      offset: i, // -2 top, 0 center, 2 bottom
    });
  }

  return (
    <div style={{ display: "flex", flexDirection: "column", alignItems: "center", userSelect: "none" }}>
      {/* Up arrow */}
      <div
        onClick={up}
        style={{
          width: 52,
          height: 24,
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          cursor: "pointer",
          color: THEME.textDim,
          fontSize: 14,
          transition: "color 0.15s",
          marginBottom: 4,
        }}
        onMouseEnter={e => e.currentTarget.style.color = THEME.accent}
        onMouseLeave={e => e.currentTarget.style.color = THEME.textDim}
      >
        ▲
      </div>

      {/* Wheel barrel */}
      <div
        ref={containerRef}
        onMouseDown={onMouseDown}
        onMouseMove={onMouseMove}
        onMouseUp={onMouseUp}
        onMouseLeave={onMouseUp}
        onTouchStart={onTouchStart}
        onTouchMove={onTouchMove}
        onTouchEnd={onTouchEnd}
        onWheel={onWheel}
        style={{
          width: 52,
          height: WHEEL_HEIGHT * 3,
          borderRadius: 8,
          background: `linear-gradient(180deg, ${THEME.wheelBg} 0%, #222228 50%, ${THEME.wheelBg} 100%)`,
          border: `1px solid ${THEME.wheelBorder}`,
          boxShadow: `inset 0 2px 8px rgba(0,0,0,0.5), 0 2px 4px rgba(0,0,0,0.3)`,
          overflow: "hidden",
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          cursor: "grab",
          position: "relative",
        }}
      >
        {/* Selection highlight line — top */}
        <div style={{
          position: "absolute",
          top: WHEEL_HEIGHT - 1,
          left: 0,
          right: 0,
          height: 1,
          background: `linear-gradient(90deg, transparent, ${THEME.accent}44, ${THEME.accent}88, ${THEME.accent}44, transparent)`,
          zIndex: 2,
        }} />
        {/* Selection highlight line — bottom */}
        <div style={{
          position: "absolute",
          top: WHEEL_HEIGHT * 2 - 1,
          left: 0,
          right: 0,
          height: 1,
          background: `linear-gradient(90deg, transparent, ${THEME.accent}44, ${THEME.accent}88, ${THEME.accent}44, transparent)`,
          zIndex: 2,
        }} />
        {/* Center glow */}
        <div style={{
          position: "absolute",
          top: WHEEL_HEIGHT,
          left: 0,
          right: 0,
          height: WHEEL_HEIGHT,
          background: `linear-gradient(180deg, transparent, ${THEME.accent}08, transparent)`,
          zIndex: 1,
        }} />

        {/* Digits */}
        {visibleDigits.map((item, i) => {
          const isActive = item.offset === 0;
          const opacity = isActive ? 1 : Math.max(0.25, 1 - Math.abs(item.offset) * 0.35);
          const scale = isActive ? 1.15 : 0.85;
          return (
            <div
              key={i}
              style={{
                height: WHEEL_HEIGHT,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontSize: isActive ? 28 : 22,
                fontFamily: "'SF Mono', 'Fira Code', monospace",
                fontWeight: isActive ? 700 : 400,
                color: isActive ? THEME.digitActive : THEME.digitInactive,
                opacity,
                transform: `scale(${scale})`,
                transition: "all 0.12s ease-out",
                zIndex: isActive ? 3 : 0,
                position: "relative",
                textShadow: isActive ? `0 0 12px ${THEME.accent}33` : "none",
              }}
            >
              {item.digit}
            </div>
          );
        })}
      </div>

      {/* Down arrow */}
      <div
        onClick={down}
        style={{
          width: 52,
          height: 24,
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          cursor: "pointer",
          color: THEME.textDim,
          fontSize: 14,
          transition: "color 0.15s",
          marginTop: 4,
        }}
        onMouseEnter={e => e.currentTarget.style.color = THEME.accent}
        onMouseLeave={e => e.currentTarget.style.color = THEME.textDim}
      >
        ▼
      </div>
    </div>
  );
}

// ─── MAIN LOCK COMPONENT ──────────────────────────
export default function QuestLock() {
  const [wheels, setWheels] = useState([0, 0, 0, 0]);
  const [status, setStatus] = useState("idle"); // idle | wrong | success
  const [wrongShake, setWrongShake] = useState(false);
  const [attempts, setAttempts] = useState(0);

  const setWheel = (index, val) => {
    setWheels(prev => {
      const next = [...prev];
      next[index] = val;
      return next;
    });
  };

  // Check if correct
  useEffect(() => {
    if (wheels.every((v, i) => v === TARGET[i])) {
      // Small delay so user sees the final digit land
      const t = setTimeout(() => setStatus("success"), 400);
      return () => clearTimeout(t);
    }
  }, [wheels]);

  const handleSubmit = () => {
    if (wheels.every((v, i) => v === TARGET[i])) {
      setStatus("success");
    } else {
      setAttempts(a => a + 1);
      setStatus("wrong");
      setWrongShake(true);
      setTimeout(() => {
        setWrongShake(false);
        setStatus("idle");
      }, 600);
    }
  };

  const handleReset = () => {
    setWheels([0, 0, 0, 0]);
    setStatus("idle");
    setAttempts(0);
  };

  return (
    <div style={{
      minHeight: "100vh",
      background: THEME.bg,
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      justifyContent: "center",
      fontFamily: "'SF Mono', 'Fira Code', 'Consolas', monospace",
      color: THEME.text,
      padding: 24,
      position: "relative",
      overflow: "hidden",
    }}>
      {/* Subtle grid background */}
      <div style={{
        position: "absolute",
        inset: 0,
        backgroundImage: `linear-gradient(${THEME.border}18 1px, transparent 1px), linear-gradient(90deg, ${THEME.border}18 1px, transparent 1px)`,
        backgroundSize: "40px 40px",
        pointerEvents: "none",
      }} />

      {/* Vignette */}
      <div style={{
        position: "absolute",
        inset: 0,
        background: `radial-gradient(ellipse at center, transparent 40%, ${THEME.bg} 100%)`,
        pointerEvents: "none",
      }} />

      {/* Content */}
      <div style={{ position: "relative", zIndex: 1, textAlign: "center" }}>

        {/* Header */}
        <div style={{ marginBottom: 48 }}>
          <div style={{
            fontSize: 11,
            letterSpacing: 4,
            textTransform: "uppercase",
            color: THEME.textDim,
            marginBottom: 12,
          }}>
            4815162342.quest
          </div>
          <div style={{
            fontSize: 22,
            color: THEME.accent,
            fontWeight: 300,
            letterSpacing: 2,
          }}>
            ENTER THE CODE
          </div>
        </div>

        {/* Lock housing */}
        <div style={{
          display: "inline-block",
          background: `linear-gradient(145deg, ${THEME.surfaceHi}, ${THEME.surface})`,
          border: `1px solid ${THEME.border}`,
          borderRadius: 16,
          padding: "32px 28px",
          boxShadow: `0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 ${THEME.border}44`,
          animation: wrongShake ? "shake 0.5s ease" : "none",
        }}>
          {/* Wheels row */}
          <div style={{
            display: "flex",
            gap: 12,
            alignItems: "flex-start",
          }}>
            {wheels.map((val, i) => (
              <Wheel
                key={i}
                index={i}
                value={val}
                onChange={(v) => setWheel(i, v)}
              />
            ))}
          </div>

          {/* Status bar */}
          <div style={{
            marginTop: 24,
            height: 20,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            fontSize: 11,
            letterSpacing: 2,
            color: status === "wrong" ? THEME.error : status === "success" ? THEME.success : THEME.textDim,
            transition: "color 0.2s",
          }}>
            {status === "idle" && attempts > 0 && "INCORRECT · TRY AGAIN"}
            {status === "wrong" && "✕  INCORRECT"}
            {status === "success" && "✓  ACCESS GRANTED"}
            {status === "idle" && attempts === 0 && ""}
          </div>
        </div>

        {/* Submit button — only show if not auto-detecting */}
        <div style={{ marginTop: 32 }}>
          {status !== "success" && (
            <button
              onClick={handleSubmit}
              style={{
                background: `linear-gradient(135deg, ${THEME.accent}, ${THEME.accentDim})`,
                border: "none",
                borderRadius: 6,
                color: "#fff",
                fontSize: 12,
                fontFamily: "inherit",
                fontWeight: 600,
                letterSpacing: 2,
                padding: "10px 28px",
                cursor: "pointer",
                textTransform: "uppercase",
                boxShadow: `0 2px 12px ${THEME.accent}33`,
                transition: "transform 0.1s, box-shadow 0.1s",
              }}
              onMouseDown={e => { e.currentTarget.style.transform = "scale(0.96)"; }}
              onMouseUp={e => { e.currentTarget.style.transform = "scale(1)"; }}
              onMouseLeave={e => { e.currentTarget.style.transform = "scale(1)"; }}
            >
              Submit
            </button>
          )}
          {status === "success" && (
            <div style={{ color: THEME.success, fontSize: 13, letterSpacing: 1 }}>
              Proceeding...
            </div>
          )}
        </div>

        {/* Reset link */}
        <div style={{ marginTop: 24 }}>
          <span
            onClick={handleReset}
            style={{
              fontSize: 10,
              color: THEME.textDim,
              cursor: "pointer",
              letterSpacing: 1,
              textTransform: "uppercase",
            }}
            onMouseEnter={e => e.currentTarget.style.color = THEME.text}
            onMouseLeave={e => e.currentTarget.style.color = THEME.textDim}
          >
            Reset
          </span>
        </div>

        {/* Attempt counter (subtle) */}
        {attempts > 0 && status !== "success" && (
          <div style={{
            marginTop: 16,
            fontSize: 10,
            color: THEME.textDim,
            letterSpacing: 1,
          }}>
            Attempts: {attempts}
          </div>
        )}
      </div>

      {/* Shake keyframes */}
      <style>{`
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          20% { transform: translateX(-6px); }
          40% { transform: translateX(6px); }
          60% { transform: translateX(-4px); }
          80% { transform: translateX(4px); }
        }
      `}</style>
    </div>
  );
}
