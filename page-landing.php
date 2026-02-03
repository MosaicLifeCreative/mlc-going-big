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
                <!-- Photo panels -->
                <div class="nav-photo nav-photo--default is-default" id="navPhotoDefault" style="background-image: url('/wp-content/uploads/2026/02/flagstaff-hike.jpg');"></div>
                <div class="nav-photo" data-photo="0" style="background-image: url('/wp-content/uploads/2026/02/flagstaff-hike.jpg');"></div>
                <div class="nav-photo" data-photo="1" style="background-image: url('/wp-content/uploads/2026/02/tuscon-at-sunset.jpg');"></div>
                <div class="nav-photo" data-photo="2" style="background-image: url('/wp-content/uploads/2026/02/buffalo-park-sunset.jpg');"></div>
                <div class="nav-photo" data-photo="3" style="background-image: url('/wp-content/uploads/2026/02/buffalo-park-scaled.jpg');"></div>
                <div class="nav-caption" id="navCaption">
                    <div class="nav-caption__title" id="navCaptionTitle">Photo 01</div>
                    <div class="nav-caption__credit">MLC</div>
                </div>
                <!-- Slideshow navigation buttons -->
                <button id="navPrev" class="nav-control nav-control--prev" aria-label="Previous photo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button id="navNext" class="nav-control nav-control--next" aria-label="Next photo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
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

            <div class="hunt-modal__title">The Hunt</div>
            <div class="hunt-modal__desc">
                Enter the sequence. If you know, you know.
            </div>

            <input
                type="text"
                id="huntInput"
                class="hunt-modal__input"
                placeholder="_ _ _ _ _ _ _ _ _ _"
                maxlength="10"
            />

            <button class="hunt-modal__submit" id="huntSubmit">Submit</button>

            <div class="hunt-modal__error" id="huntError">
                Not quite. Try again.
            </div>
        </div>
    </div>

    <!-- Chatbot -->
    <div class="chatbot" id="chatbot">
        <div class="chatbot__header">
            <div class="chatbot__avatar">🤖</div>
            <div>
                <div class="chatbot__name">Mosaic</div>
                <div class="chatbot__status">Online</div>
            </div>
            <button class="chatbot__close" id="chatbotClose">&times;</button>
        </div>

        <div class="chatbot__messages" id="chatMessages"></div>
    </div>

    <?php wp_footer(); ?>

</body>
</html>