<?php
/**
 * Template Name: MLC Landing Page
 * Description: Mosaic Life Creative landing page with interactive elements
 *
 * @package MosaicLifeCreative
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- Plus Jakarta Sans from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>

    <style>
        /* ─── BASE RESET ─────────────────────────────────────────── */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            /* Royal Digital Palette - LOCKED */
            --primary: #7C3AED;
            --secondary: #06B6D4;
            --accent: #EC4899;
            --dark: #18181B;
            --light: #FAFAFA;

            /* Derived colors */
            --primary-dark: #5b21b6;
            --button-shadow: rgba(124, 58, 237, 0.3);
            --bot-accent-bg: rgba(124, 58, 237, 0.15);
            --bot-accent-border: rgba(124, 58, 237, 0.3);
            --bot-accent-text: #c4b5fd;

            /* Typography */
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'SF Mono', 'Fira Code', 'Monaco', 'Consolas', monospace;

            /* Weight preset: Bold */
            --weight-heading: 700;
            --weight-body: 400;
            --weight-highlight: 700;
        }

        html, body {
            font-family: var(--font-main);
            background: var(--light);
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }

        /* ─── ANIMATIONS ─────────────────────────────────────────── */
        @keyframes float1 {
            0% { transform: translate(0, 0) scale(1); }
            30% { transform: translate(80px, -60px) scale(1.08); }
            60% { transform: translate(-40px, 30px) scale(0.95); }
            100% { transform: translate(0, 0) scale(1); }
        }

        @keyframes float2 {
            0% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-90px, 70px) scale(0.92); }
            55% { transform: translate(50px, -50px) scale(1.06); }
            100% { transform: translate(0, 0) scale(1); }
        }

        @keyframes float3 {
            0% { transform: translate(0, 0) scale(1); }
            35% { transform: translate(70px, 80px) scale(1.04); }
            65% { transform: translate(-60px, -40px) scale(0.94); }
            100% { transform: translate(0, 0) scale(1); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }

        @keyframes btnFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        @keyframes hamburgerSpin {
            to { transform: rotate(360deg); }
        }

        @keyframes hamburgerBreath {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        /* ─── LAYOUT ─────────────────────────────────────────────── */
        .mlc-landing {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ─── GRADIENT ORBS ──────────────────────────────────────── */
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            pointer-events: none;
            transition: background 0.8s ease;
        }

        .gradient-orb--1 {
            width: 500px;
            height: 500px;
            top: -10%;
            left: -15%;
            background: var(--primary);
            animation: float1 8s ease-in-out infinite;
        }

        .gradient-orb--2 {
            width: 400px;
            height: 400px;
            bottom: -5%;
            right: -10%;
            background: var(--secondary);
            animation: float2 10s ease-in-out infinite;
        }

        .gradient-orb--3 {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 60%;
            background: var(--accent);
            animation: float3 12s ease-in-out infinite;
        }

        .gradient-orb--4 {
            width: 200px;
            height: 200px;
            top: 20%;
            right: 20%;
            background: var(--primary);
            animation: float1 9s ease-in-out infinite 2s;
        }

        /* ─── HAMBURGER BUTTON ───────────────────────────────────── */
        .hamburger {
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 50;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            padding: 1.5px;
            background: conic-gradient(from 0deg, var(--primary), var(--secondary), var(--accent), var(--primary));
            animation: hamburgerSpin 4s linear infinite;
            cursor: pointer;
            transition: transform 0.15s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: none;
        }

        .hamburger:hover {
            animation-duration: 1.2s;
        }

        .hamburger__inner {
            width: 100%;
            height: 100%;
            border-radius: 12.5px;
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background 0.25s ease;
            animation: hamburgerBreath 3s ease-in-out infinite;
        }

        .hamburger:hover .hamburger__inner {
            background: rgba(255, 255, 255, 0.75);
        }

        .hamburger__line {
            height: 1.5px;
            border-radius: 1px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            transform-origin: center;
        }

        .hamburger__line--1,
        .hamburger__line--3 {
            width: 20px;
        }

        .hamburger__line--2 {
            width: 14px;
            opacity: 0.6;
        }

        /* Morph to X on hover */
        .hamburger:hover .hamburger__line--1 {
            width: 18px;
            transform: rotate(45deg) translateX(3px) translateY(3px);
        }

        .hamburger:hover .hamburger__line--2 {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger:hover .hamburger__line--3 {
            width: 18px;
            transform: rotate(-45deg) translateX(3px) translateY(-3px);
        }

        /* ─── NAV OVERLAY ────────────────────────────────────────── */
        .nav-overlay {
            position: fixed;
            inset: 0;
            z-index: 90;
            background: rgba(14, 14, 18, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: none;
            flex-direction: row;
            align-items: stretch;
            animation: fadeIn 0.25s ease;
        }

        .nav-overlay.is-open {
            display: flex;
        }

        .nav-overlay__close {
            position: absolute;
            top: 24px;
            right: 28px;
            z-index: 10;
            color: #555;
            font-size: 26px;
            cursor: pointer;
            transition: color 0.2s;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
        }

        .nav-overlay__close:hover {
            color: #fff;
        }

        .nav-overlay__left {
            flex: 0 0 55%;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 64px;
        }

        .nav-overlay__brand {
            font-size: 10px;
            letter-spacing: 3px;
            color: #3a3a48;
            text-transform: uppercase;
            margin-bottom: 48px;
        }

        .nav-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            list-style: none;
        }

        .nav-item {
            display: flex;
            align-items: baseline;
            gap: 16px;
            padding: 10px 0;
            cursor: pointer;
            opacity: 0;
            animation: slideUp 0.35s ease forwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0s; }
        .nav-item:nth-child(2) { animation-delay: 0.07s; }
        .nav-item:nth-child(3) { animation-delay: 0.14s; }
        .nav-item:nth-child(4) { animation-delay: 0.21s; }
        .nav-item:nth-child(5) { animation-delay: 0.28s; }
        .nav-item:nth-child(6) { animation-delay: 0.35s; }
        .nav-item:nth-child(7) { animation-delay: 0.42s; }
        .nav-item:nth-child(8) { animation-delay: 0.49s; }

        .nav-item__number {
            font-size: 11px;
            color: #3a3a48;
            width: 24px;
            text-align: right;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .nav-item:hover .nav-item__number {
            color: var(--primary);
        }

        .nav-item__label {
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 300;
            color: #9a9aa8;
            letter-spacing: -1px;
            line-height: 1.1;
            transition: color 0.25s ease, font-weight 0.25s ease;
            text-decoration: none;
        }

        .nav-item:hover .nav-item__label {
            font-weight: 700;
            color: #fff;
        }

        .nav-overlay__right {
            flex: 1;
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .nav-photo {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .nav-photo.is-active {
            opacity: 1;
            z-index: 1;
        }

        .nav-photo.is-default {
            opacity: 0.4;
        }

        .nav-overlay__gradient {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: linear-gradient(to right, rgba(14, 14, 18, 0.97) 0%, rgba(14, 14, 18, 0.3) 40%, transparent 70%);
            pointer-events: none;
        }

        .nav-caption {
            position: absolute;
            bottom: 40px;
            left: 40px;
            z-index: 3;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .nav-caption.is-visible {
            display: block;
        }

        .nav-caption__title {
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            opacity: 0.9;
        }

        .nav-caption__credit {
            color: #555;
            font-size: 11px;
            margin-top: 4px;
        }

        /* ─── MAIN CONTENT ───────────────────────────────────────── */
        .main-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
        }

        .phase-container {
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .phase-text {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .phase-text.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .phase-text h1 {
            color: #111;
            line-height: 1.2;
            max-width: 750px;
            margin: 0 auto;
        }

        .phase-text h1.is-highlight {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cursor-blink {
            display: inline-block;
            width: 3px;
            background: #111;
            margin-left: 6px;
            vertical-align: middle;
            animation: blink 1s step-end infinite;
        }

        .cursor-blink.is-highlight {
            background: var(--primary);
        }

        /* ─── CHOICE BUTTONS ─────────────────────────────────────── */
        .choice-buttons {
            display: none;
            gap: 20px;
            justify-content: center;
            margin-top: 32px;
            animation: slideUp 0.5s ease;
            flex-wrap: wrap;
            align-items: center;
        }

        .choice-buttons.is-visible {
            display: flex;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 12px;
            border: none;
            font-size: 15px;
            font-family: var(--font-main);
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            user-select: none;
            -webkit-user-select: none;
            transition: all 0.15s ease;
        }

        .btn--primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 24px var(--button-shadow);
            animation: btnFloat 3s ease-in-out infinite;
        }

        .btn--primary:active {
            box-shadow: 0 2px 12px var(--button-shadow);
            transform: translateY(1px);
            animation: none;
        }

        .btn--secondary {
            background: #fff;
            color: #888;
            border: 1px solid #ddd;
        }

        .btn--secondary:hover {
            border-color: #bbb;
            color: #666;
        }

        .scroll-hint {
            margin-top: 40px;
            color: #bbb;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .loading-text {
            margin-top: 32px;
            color: #aaa;
            font-size: 14px;
            animation: fadeIn 0.3s ease;
        }

        /* ─── FOOTER / COUNTDOWN ─────────────────────────────────── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .footer__center {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hunt-enter-btn {
            pointer-events: auto;
            display: none;
            background: rgba(124, 58, 237, 0.12);
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 6px;
            padding: 4px 12px;
            color: var(--primary);
            font-size: 10px;
            letter-spacing: 1px;
            cursor: pointer;
            animation: fadeIn 0.4s ease;
            transition: background 0.2s;
        }

        .hunt-enter-btn.is-visible {
            display: block;
        }

        .hunt-enter-btn:hover {
            background: rgba(124, 58, 237, 0.22);
        }

        .countdown {
            color: #999;
            font-size: 11px;
            font-family: var(--font-mono);
            letter-spacing: 1px;
            opacity: 0.45;
            transition: color 0.3s, opacity 0.3s;
        }

        .countdown.is-active {
            color: var(--accent);
            opacity: 1;
        }

        /* ─── HUNT MODAL ─────────────────────────────────────────── */
        .hunt-modal {
            position: fixed;
            inset: 0;
            z-index: 95;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease;
        }

        .hunt-modal.is-open {
            display: flex;
        }

        .hunt-modal__card {
            position: relative;
            background: #12121a;
            border: 1px solid #2a2a38;
            border-radius: 16px;
            padding: 36px 40px;
            width: 360px;
            max-width: 90vw;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.25s ease;
        }

        .hunt-modal__card.is-shaking {
            animation: shake 0.4s ease;
        }

        .hunt-modal__close {
            position: absolute;
            top: 16px;
            right: 20px;
            color: #555;
            font-size: 20px;
            cursor: pointer;
            background: none;
            border: none;
        }

        .hunt-modal__label {
            font-size: 10px;
            letter-spacing: 3px;
            color: #555;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .hunt-modal__desc {
            font-size: 14px;
            color: #aaa;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .hunt-modal__input {
            width: 100%;
            background: #1e1e28;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 12px 16px;
            color: #fff;
            font-family: var(--font-mono);
            font-size: 18px;
            letter-spacing: 4px;
            outline: none;
            transition: border-color 0.2s;
        }

        .hunt-modal__input::placeholder {
            color: #444;
        }

        .hunt-modal__input.is-error {
            border-color: var(--accent);
        }

        .hunt-modal__error {
            margin-top: 10px;
            color: var(--accent);
            font-size: 11px;
            letter-spacing: 1px;
            display: none;
        }

        .hunt-modal__error.is-visible {
            display: block;
        }

        .hunt-modal__submit {
            margin-top: 20px;
            width: 100%;
            padding: 10px 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            cursor: pointer;
            text-transform: uppercase;
            box-shadow: 0 4px 16px var(--button-shadow);
        }

        .hunt-modal__success {
            text-align: center;
            display: none;
        }

        .hunt-modal__success.is-visible {
            display: block;
        }

        .hunt-modal__success-icon {
            font-size: 28px;
            margin-bottom: 16px;
        }

        .hunt-modal__success-title {
            color: var(--secondary);
            font-size: 14px;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .hunt-modal__success-desc {
            color: #555;
            font-size: 11px;
        }

        /* ─── CHATBOT ────────────────────────────────────────────── */
        .chatbot-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: flex-end;
            justify-content: center;
            z-index: 100;
            animation: fadeIn 0.3s ease;
        }

        .chatbot-overlay.is-open {
            display: flex;
        }

        .chatbot {
            width: 100%;
            max-width: 480px;
            background: #0a0a0a;
            border-radius: 20px 20px 0 0;
            border: 1px solid #222;
            border-bottom: none;
            display: flex;
            flex-direction: column;
            height: 75vh;
            max-height: 600px;
        }

        .chatbot__header {
            padding: 16px 20px;
            border-bottom: 1px solid #1a1a1a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot__header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot__avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .chatbot__name {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .chatbot__status {
            color: #555;
            font-size: 11px;
        }

        .chatbot__close {
            background: none;
            border: none;
            color: #555;
            font-size: 20px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .chatbot__messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chat-message {
            display: flex;
            animation: slideUp 0.3s ease;
        }

        .chat-message--user {
            justify-content: flex-end;
        }

        .chat-message--bot {
            justify-content: flex-start;
        }

        .chat-message__bubble {
            max-width: 80%;
            padding: 10px 14px;
            color: #fff;
            font-size: 14px;
            line-height: 1.5;
        }

        .chat-message--user .chat-message__bubble {
            border-radius: 16px 16px 4px 16px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }

        .chat-message--bot .chat-message__bubble {
            border-radius: 16px 16px 16px 4px;
            background: #1a1a1a;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 8px 0;
            align-items: center;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #888;
        }

        .typing-dot:nth-child(1) { animation: bounce 1.2s ease-in-out 0s infinite; }
        .typing-dot:nth-child(2) { animation: bounce 1.2s ease-in-out 0.2s infinite; }
        .typing-dot:nth-child(3) { animation: bounce 1.2s ease-in-out 0.4s infinite; }

        .chat-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 4px;
            animation: slideUp 0.3s ease;
        }

        .chat-option {
            background: transparent;
            border: 1px solid #333;
            color: #ccc;
            padding: 10px 16px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            text-align: left;
            transition: all 0.2s ease;
        }

        .chat-option:hover {
            border-color: var(--primary);
            color: #fff;
            background: rgba(124, 58, 237, 0.08);
        }

        .chat-done-card {
            margin-top: 16px;
            padding: 16px;
            background: var(--bot-accent-bg);
            border-radius: 12px;
            border: 1px solid var(--bot-accent-border);
            animation: slideUp 0.4s ease;
        }

        .chat-done-card__title {
            color: var(--bot-accent-text);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .chat-done-card__desc {
            color: #fff;
            font-size: 13px;
            line-height: 1.6;
        }

        /* ─── MOBILE ADJUSTMENTS ─────────────────────────────────── */
        @media (max-width: 768px) {
            .nav-overlay__left {
                flex: 1;
                padding: 60px 32px;
            }

            .nav-overlay__right {
                display: none;
            }

            .gradient-orb--1 { width: 300px; height: 300px; }
            .gradient-orb--2 { width: 250px; height: 250px; }
            .gradient-orb--3 { width: 200px; height: 200px; }
            .gradient-orb--4 { width: 150px; height: 150px; }
        }
    </style>
</head>
<body <?php body_class('mlc-landing-page'); ?>>

    <div class="mlc-landing">
        <!-- Gradient Orbs -->
        <div class="gradient-orb gradient-orb--1"></div>
        <div class="gradient-orb gradient-orb--2"></div>
        <div class="gradient-orb gradient-orb--3"></div>
        <div class="gradient-orb gradient-orb--4"></div>

        <!-- Hamburger Navigation Button -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Open navigation">
            <div class="hamburger__inner">
                <div class="hamburger__line hamburger__line--1"></div>
                <div class="hamburger__line hamburger__line--2"></div>
                <div class="hamburger__line hamburger__line--3"></div>
            </div>
        </button>

        <!-- Navigation Overlay -->
        <div class="nav-overlay" id="navOverlay">
            <button class="nav-overlay__close" id="navClose" aria-label="Close navigation">&times;</button>

            <div class="nav-overlay__left">
                <div class="nav-overlay__brand">Mosaic Life Creative</div>
                <nav>
                    <ul class="nav-list" id="navList">
                        <li class="nav-item" data-index="0">
                            <span class="nav-item__number">01</span>
                            <a href="#" class="nav-item__label">Home</a>
                        </li>
                        <li class="nav-item" data-index="1">
                            <span class="nav-item__number">02</span>
                            <a href="#" class="nav-item__label">Website Design</a>
                        </li>
                        <li class="nav-item" data-index="2">
                            <span class="nav-item__number">03</span>
                            <a href="#" class="nav-item__label">Hosting</a>
                        </li>
                        <li class="nav-item" data-index="3">
                            <span class="nav-item__number">04</span>
                            <a href="#" class="nav-item__label">Maintenance</a>
                        </li>
                        <li class="nav-item" data-index="4">
                            <span class="nav-item__number">05</span>
                            <a href="#" class="nav-item__label">Email Marketing</a>
                        </li>
                        <li class="nav-item" data-index="5">
                            <span class="nav-item__number">06</span>
                            <a href="#" class="nav-item__label">AI Chat Agents</a>
                        </li>
                        <li class="nav-item" data-index="6">
                            <span class="nav-item__number">07</span>
                            <a href="#" class="nav-item__label">About</a>
                        </li>
                        <li class="nav-item" data-index="7">
                            <span class="nav-item__number">08</span>
                            <a href="#" class="nav-item__label">Contact</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="nav-overlay__right">
                <!-- Photo panels - placeholder URLs for now -->
                <div class="nav-photo nav-photo--default is-default" id="navPhotoDefault" style="background-image: url('https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-photo" data-photo="0" style="background-image: url('https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-photo" data-photo="1" style="background-image: url('https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-photo" data-photo="2" style="background-image: url('https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-photo" data-photo="3" style="background-image: url('https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-overlay__gradient"></div>
                <div class="nav-caption" id="navCaption">
                    <div class="nav-caption__title" id="navCaptionTitle">Photo 01</div>
                    <div class="nav-caption__credit">MLC</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="phase-container">
                <div class="phase-text" id="phaseText">
                    <h1 id="phaseHeadline">Hello.</h1>
                </div>
            </div>

            <!-- Choice Buttons -->
            <div class="choice-buttons" id="choiceButtons">
                <button class="btn btn--secondary" id="btnSecondary">Like everyone else's</button>
                <button class="btn btn--primary" id="btnPrimary">Like nothing else</button>
            </div>

            <div class="loading-text" id="loadingText" style="display: none;">Opening...</div>

            <div class="scroll-hint" id="scrollHint">or just scroll</div>
        </div>

        <!-- Footer with Countdown -->
        <div class="footer">
            <div class="footer__center">
                <button class="hunt-enter-btn" id="huntEnterBtn">&#x25CF; ENTER</button>
                <div class="countdown" id="countdown">00:00:00</div>
            </div>
        </div>
    </div>

    <!-- Hunt Modal -->
    <div class="hunt-modal" id="huntModal">
        <div class="hunt-modal__card" id="huntCard">
            <button class="hunt-modal__close" id="huntClose">&times;</button>

            <div id="huntForm">
                <div class="hunt-modal__label">Sequence</div>
                <div class="hunt-modal__desc">Enter the numbers.</div>
                <input type="text"
                       class="hunt-modal__input"
                       id="huntInput"
                       inputmode="numeric"
                       placeholder="__________"
                       autocomplete="off">
                <div class="hunt-modal__error" id="huntError">Incorrect.</div>
                <button class="hunt-modal__submit" id="huntSubmit">Submit</button>
            </div>

            <div class="hunt-modal__success" id="huntSuccess">
                <div class="hunt-modal__success-icon">&#x2713;</div>
                <div class="hunt-modal__success-title">ACCESS GRANTED</div>
                <div class="hunt-modal__success-desc">Redirecting to 4815162342.quest...</div>
            </div>
        </div>
    </div>

    <!-- Chatbot Overlay -->
    <div class="chatbot-overlay" id="chatbotOverlay">
        <div class="chatbot">
            <div class="chatbot__header">
                <div class="chatbot__header-left">
                    <div class="chatbot__avatar">&#x2726;</div>
                    <div>
                        <div class="chatbot__name">Mosaic</div>
                        <div class="chatbot__status">Usually replies instantly</div>
                    </div>
                </div>
                <button class="chatbot__close" id="chatbotClose">&times;</button>
            </div>
            <div class="chatbot__messages" id="chatMessages">
                <!-- Messages injected by JS -->
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>

    <script>
    (function() {
        'use strict';

        // ─── CONFIGURATION ──────────────────────────────────────────
        const CONFIG = {
            // Static fallback phases (when AI is off or fails)
            staticPhases: [
                { text: "Hello.", duration: 2400, size: "clamp(48px, 10vw, 96px)", tracking: -2, highlight: false, isHeading: true },
                { text: "We build websites that make people stop scrolling.", duration: 3400, size: "clamp(26px, 4.5vw, 48px)", tracking: -0.5, highlight: false, isHeading: false },
                { text: "Built Different.", duration: 3000, size: "clamp(40px, 8vw, 80px)", tracking: -1.5, highlight: true, isHeading: true },
                { text: "So how do you want yours to feel?", duration: null, size: "clamp(24px, 4vw, 42px)", tracking: -0.5, highlight: false, isHeading: false }
            ],

            // Countdown target: 3:16:23 PM (Lost numbers: 3, 16, 23)
            targetHour: 15,
            targetMin: 16,
            targetSec: 23,
            windowDuration: 42, // seconds

            // Hunt sequence
            targetSequence: "4815162342",
            questDomain: "4815162342.quest",

            // Nav photos - placeholder URLs (replace with WordPress media URLs)
            navPhotos: [
                { url: "https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg", caption: "Photo 01", credit: "MLC" },
                { url: "https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg", caption: "Photo 02", credit: "MLC" },
                { url: "https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg", caption: "Photo 03", credit: "MLC" },
                { url: "https://fresh.mosaiclifecreative.com/wp-content/uploads/2026/02/buffalo-park-scaled.jpg", caption: "Photo 04", credit: "MLC" }
            ],

            // Chatbot flows
            chatFlows: {
                opener: { from: "bot", text: "Interesting choice. Tell me something — what made you click that?" },
                answers: {
                    curious: [
                        { from: "bot", text: "Hmm. That's honest. I like honest." },
                        { from: "bot", text: "Most people just click things. You actually stopped and thought about it." },
                        { from: "bot", text: "Let me ask you this — do you believe a website can actually change how people perceive a business?" }
                    ],
                    boredom: [
                        { from: "bot", text: "Fair enough. Nothing wrong with that." },
                        { from: "bot", text: "But you did click 'Interesting.' So something pulled you." },
                        { from: "bot", text: "What if I told you there's more to this page than meets the eye?" }
                    ],
                    needing: [
                        { from: "bot", text: "Good. Then you're in the right place." },
                        { from: "bot", text: "But before we talk business — I want to know something." },
                        { from: "bot", text: "What does your current website make you feel when you look at it?" }
                    ]
                },
                deeper: {
                    yes: [
                        { from: "bot", text: "Then you already understand more than most." },
                        { from: "bot", text: "Most people think a website is just a page with information on it." },
                        { from: "bot", text: "It's not. It's the first conversation your business has with a stranger." },
                        { from: "bot", text: "And right now, yours is boring." },
                        { from: "bot", text: "Want to see what it could be instead?" }
                    ],
                    no: [
                        { from: "bot", text: "Most people don't think so either." },
                        { from: "bot", text: "They're wrong." },
                        { from: "bot", text: "A website is the first conversation your business ever has with a stranger." },
                        { from: "bot", text: "And that conversation either pulls them in... or loses them in 3 seconds." },
                        { from: "bot", text: "Want to see the difference?" }
                    ]
                },
                reward: [
                    { from: "bot", text: "Smart." },
                    { from: "bot", text: "Alright. You've earned it." },
                    { from: "bot", text: "🎯 Welcome to the interesting side." }
                ]
            }
        };

        // ─── STATE ──────────────────────────────────────────────────
        const state = {
            phase: 0,
            seqState: 'showing',
            displayedPhase: 0,
            phases: CONFIG.staticPhases,
            showChoice: false,
            clicked: null,
            huntModalOpen: false,
            huntStatus: 'idle',
            navOpen: false,
            hoveredNav: null,
            chatStage: 'open',
            chatTyping: false
        };

        // ─── DOM ELEMENTS ───────────────────────────────────────────
        const $ = (sel) => document.querySelector(sel);
        const $$ = (sel) => document.querySelectorAll(sel);

        const els = {
            hamburger: $('#hamburgerBtn'),
            navOverlay: $('#navOverlay'),
            navClose: $('#navClose'),
            navList: $('#navList'),
            navItems: $$('.nav-item'),
            navPhotos: $$('.nav-photo[data-photo]'),
            navPhotoDefault: $('#navPhotoDefault'),
            navCaption: $('#navCaption'),
            navCaptionTitle: $('#navCaptionTitle'),
            phaseText: $('#phaseText'),
            phaseHeadline: $('#phaseHeadline'),
            choiceButtons: $('#choiceButtons'),
            btnPrimary: $('#btnPrimary'),
            btnSecondary: $('#btnSecondary'),
            loadingText: $('#loadingText'),
            scrollHint: $('#scrollHint'),
            countdown: $('#countdown'),
            huntEnterBtn: $('#huntEnterBtn'),
            huntModal: $('#huntModal'),
            huntCard: $('#huntCard'),
            huntClose: $('#huntClose'),
            huntInput: $('#huntInput'),
            huntError: $('#huntError'),
            huntSubmit: $('#huntSubmit'),
            huntForm: $('#huntForm'),
            huntSuccess: $('#huntSuccess'),
            chatbotOverlay: $('#chatbotOverlay'),
            chatbotClose: $('#chatbotClose'),
            chatMessages: $('#chatMessages')
        };

        // ─── UTILITY FUNCTIONS ──────────────────────────────────────
        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // ─── HAMBURGER NAV ──────────────────────────────────────────
        function openNav() {
            state.navOpen = true;
            els.navOverlay.classList.add('is-open');
        }

        function closeNav() {
            state.navOpen = false;
            state.hoveredNav = null;
            els.navOverlay.classList.remove('is-open');
            updateNavPhotos();
        }

        function updateNavPhotos() {
            const idx = state.hoveredNav;

            // Hide all photos
            els.navPhotos.forEach(photo => {
                photo.classList.remove('is-active');
            });

            if (idx !== null) {
                // Show hovered photo
                const photoIdx = idx % CONFIG.navPhotos.length;
                const activePhoto = document.querySelector(`.nav-photo[data-photo="${photoIdx}"]`);
                if (activePhoto) {
                    activePhoto.classList.add('is-active');
                }
                els.navPhotoDefault.classList.remove('is-default');

                // Update caption
                els.navCaption.classList.add('is-visible');
                els.navCaptionTitle.textContent = CONFIG.navPhotos[photoIdx].caption;
            } else {
                // Show default
                els.navPhotoDefault.classList.add('is-default');
                els.navCaption.classList.remove('is-visible');
            }
        }

        // Hamburger magnetic effect
        els.hamburger.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = (e.clientX - cx) / rect.width * 6;
            const dy = (e.clientY - cy) / rect.height * 6;
            this.style.transform = `translate(${dx}px, ${dy}px) scale(1.08)`;
        });

        els.hamburger.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0, 0) scale(1)';
        });

        els.hamburger.addEventListener('click', openNav);
        els.navClose.addEventListener('click', closeNav);

        // Nav item hover
        els.navItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                state.hoveredNav = parseInt(this.dataset.index, 10);
                updateNavPhotos();
            });
            item.addEventListener('mouseleave', function() {
                state.hoveredNav = null;
                updateNavPhotos();
            });
        });

        // ─── TEXT SEQUENCE ──────────────────────────────────────────
        function updatePhaseDisplay() {
            const current = state.phases[state.displayedPhase];
            if (!current || !current.text) return;

            els.phaseHeadline.textContent = current.text;
            els.phaseHeadline.style.fontSize = current.size;
            els.phaseHeadline.style.letterSpacing = current.tracking + 'px';
            els.phaseHeadline.style.fontWeight = current.isHeading ? 'var(--weight-heading)' : 'var(--weight-body)';

            if (current.highlight) {
                els.phaseHeadline.classList.add('is-highlight');
            } else {
                els.phaseHeadline.classList.remove('is-highlight');
            }
        }

        function showPhase() {
            els.phaseText.classList.add('is-visible');
        }

        function hidePhase() {
            els.phaseText.classList.remove('is-visible');
        }

        async function runSequence() {
            for (let i = 0; i < state.phases.length; i++) {
                state.phase = i;
                state.displayedPhase = i;

                updatePhaseDisplay();
                showPhase();

                const current = state.phases[i];

                if (current.duration === null) {
                    // Last phase - show buttons after delay
                    await sleep(2200);
                    state.showChoice = true;
                    els.choiceButtons.classList.add('is-visible');
                    els.scrollHint.style.display = 'none';
                    break;
                }

                await sleep(current.duration);
                hidePhase();
                await sleep(550);
            }
        }

        // ─── CHOICE BUTTONS ─────────────────────────────────────────
        els.btnSecondary.addEventListener('click', function() {
            state.clicked = 'safe';
            els.choiceButtons.style.display = 'none';
            els.loadingText.textContent = 'Redirecting...';
            els.loadingText.style.display = 'block';
            setTimeout(() => {
                window.open('https://www.squarespace.com', '_blank');
            }, 600);
        });

        els.btnPrimary.addEventListener('click', function() {
            state.clicked = 'bold';
            els.choiceButtons.style.display = 'none';
            els.loadingText.textContent = 'Opening...';
            els.loadingText.style.display = 'block';
            setTimeout(() => {
                openChatbot();
            }, 400);
        });

        // ─── COUNTDOWN TIMER ────────────────────────────────────────
        function updateCountdown() {
            const now = new Date();
            const target = new Date();
            target.setHours(CONFIG.targetHour, CONFIG.targetMin, CONFIG.targetSec, 0);

            let diff = (target - now) / 1000;

            // Check if we're in the 42-second window
            if (diff <= 0 && diff > -CONFIG.windowDuration) {
                els.countdown.textContent = '00:00:00';
                els.countdown.classList.add('is-active');
                els.huntEnterBtn.classList.add('is-visible');
                return;
            }

            // Window expired - reset to next day
            if (diff <= -CONFIG.windowDuration) {
                target.setDate(target.getDate() + 1);
                diff = (target - now) / 1000;
                els.huntEnterBtn.classList.remove('is-visible');

                // Only clear hunt state if modal isn't open
                if (!state.huntModalOpen) {
                    els.huntInput.value = '';
                    state.huntStatus = 'idle';
                    els.huntError.classList.remove('is-visible');
                    els.huntInput.classList.remove('is-error');
                }
            }

            const hours = Math.floor(diff / 3600);
            const mins = Math.floor((diff % 3600) / 60);
            const secs = Math.floor(diff % 60);

            els.countdown.textContent = `${pad(hours)}:${pad(mins)}:${pad(secs)}`;
            els.countdown.classList.remove('is-active');
        }

        // ─── HUNT MODAL ─────────────────────────────────────────────
        function openHuntModal() {
            state.huntModalOpen = true;
            els.huntModal.classList.add('is-open');
            els.huntInput.focus();
        }

        function closeHuntModal() {
            state.huntModalOpen = false;
            els.huntModal.classList.remove('is-open');
            els.huntInput.value = '';
            state.huntStatus = 'idle';
            els.huntError.classList.remove('is-visible');
            els.huntInput.classList.remove('is-error');
            els.huntForm.style.display = 'block';
            els.huntSuccess.classList.remove('is-visible');
            els.huntClose.style.display = 'block';
        }

        function validateHunt() {
            const input = els.huntInput.value;

            if (input === CONFIG.targetSequence) {
                state.huntStatus = 'success';
                els.huntForm.style.display = 'none';
                els.huntSuccess.classList.add('is-visible');
                els.huntClose.style.display = 'none';

                // Redirect after showing success
                setTimeout(() => {
                    window.location.href = 'https://' + CONFIG.questDomain;
                }, 2000);
            } else {
                state.huntStatus = 'wrong';
                els.huntError.classList.add('is-visible');
                els.huntInput.classList.add('is-error');
                els.huntCard.classList.add('is-shaking');

                setTimeout(() => {
                    els.huntCard.classList.remove('is-shaking');
                }, 400);

                setTimeout(() => {
                    state.huntStatus = 'idle';
                    els.huntError.classList.remove('is-visible');
                    els.huntInput.classList.remove('is-error');
                }, 1200);
            }
        }

        els.huntEnterBtn.addEventListener('click', openHuntModal);
        els.huntClose.addEventListener('click', closeHuntModal);
        els.huntSubmit.addEventListener('click', validateHunt);

        els.huntInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            state.huntStatus = 'idle';
            els.huntError.classList.remove('is-visible');
            els.huntInput.classList.remove('is-error');
        });

        els.huntInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') validateHunt();
            if (e.key === 'Escape') closeHuntModal();
        });

        // ─── CHATBOT ────────────────────────────────────────────────
        function openChatbot() {
            els.chatbotOverlay.classList.add('is-open');
            state.chatStage = 'open';
            initChatbot();
        }

        function closeChatbot() {
            els.chatbotOverlay.classList.remove('is-open');
            els.chatMessages.innerHTML = '';
        }

        function addMessage(from, text) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `chat-message chat-message--${from}`;

            const bubble = document.createElement('div');
            bubble.className = 'chat-message__bubble';
            bubble.textContent = text;

            msgDiv.appendChild(bubble);
            els.chatMessages.appendChild(msgDiv);
            els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
        }

        function showTyping() {
            state.chatTyping = true;
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-message chat-message--bot';
            typingDiv.id = 'typingIndicator';

            const bubble = document.createElement('div');
            bubble.className = 'chat-message__bubble';
            bubble.innerHTML = '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>';

            typingDiv.appendChild(bubble);
            els.chatMessages.appendChild(typingDiv);
            els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
        }

        function hideTyping() {
            state.chatTyping = false;
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }

        function showOptions(options) {
            const optionsDiv = document.createElement('div');
            optionsDiv.className = 'chat-options';
            optionsDiv.id = 'chatOptions';

            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'chat-option';
                btn.textContent = opt.label;
                btn.addEventListener('click', () => handleOption(opt.value));
                optionsDiv.appendChild(btn);
            });

            els.chatMessages.appendChild(optionsDiv);
            els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
        }

        function hideOptions() {
            const opts = document.getElementById('chatOptions');
            if (opts) opts.remove();
        }

        async function addMessagesSequence(messages, delay = 600) {
            for (let i = 0; i < messages.length; i++) {
                await sleep(delay + Math.random() * 400);
                showTyping();
                await sleep(800 + Math.random() * 400);
                hideTyping();
                addMessage(messages[i].from, messages[i].text);
            }
        }

        async function initChatbot() {
            showTyping();
            await sleep(1000);
            hideTyping();
            addMessage('bot', CONFIG.chatFlows.opener.text);

            await sleep(1200);
            showOptions([
                { label: "I was just curious", value: "curious" },
                { label: "I'm bored", value: "boredom" },
                { label: "I actually need a website", value: "needing" }
            ]);
        }

        async function handleOption(value) {
            hideOptions();

            if (state.chatStage === 'open') {
                const labels = {
                    curious: "I was just curious",
                    boredom: "I'm bored",
                    needing: "I actually need a website"
                };
                addMessage('user', labels[value]);

                const flow = CONFIG.chatFlows.answers[value];
                await addMessagesSequence(flow, 400);

                await sleep(800);
                showOptions([
                    { label: "Yeah, I think it can.", value: "yes" },
                    { label: "Honestly? No.", value: "no" }
                ]);
                state.chatStage = 'deeper';

            } else if (state.chatStage === 'deeper') {
                addMessage('user', value === 'yes' ? "Yeah, I think it can." : "Honestly? No.");

                const flow = CONFIG.chatFlows.deeper[value];
                await addMessagesSequence(flow, 400);

                await sleep(600);
                showOptions([
                    { label: "Show me.", value: "reward" }
                ]);
                state.chatStage = 'reward';

            } else if (state.chatStage === 'reward') {
                addMessage('user', "Show me.");

                await addMessagesSequence(CONFIG.chatFlows.reward, 400);

                await sleep(400);
                showDoneCard();
                state.chatStage = 'done';
            }
        }

        function showDoneCard() {
            const card = document.createElement('div');
            card.className = 'chat-done-card';
            card.innerHTML = `
                <div class="chat-done-card__title">This is where it gets real.</div>
                <div class="chat-done-card__desc">In production, this is where the bot branches — adventure, services, or deeper into the mystery. This is just the proof of concept.</div>
            `;
            els.chatMessages.appendChild(card);
            els.chatMessages.scrollTop = els.chatMessages.scrollHeight;
        }

        els.chatbotClose.addEventListener('click', closeChatbot);

        // ─── INITIALIZE ─────────────────────────────────────────────
        function init() {
            // Start countdown timer
            updateCountdown();
            setInterval(updateCountdown, 1000);

            // Start text sequence
            setTimeout(() => {
                runSequence();
            }, 500);
        }

        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
</body>
</html>
