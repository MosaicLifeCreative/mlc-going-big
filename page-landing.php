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
<?php wp_body_open(); // This triggers nav injection from functions.php ?>

    <div class="mlc-landing">
        <!-- Render shared gradient background -->
        <?php mlc_render_gradient_blobs(); ?>

        <!-- Nav is now injected globally via functions.php -->

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

    <?php wp_footer(); ?>

</body>
</html>