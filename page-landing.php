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

    <main class="mlc-landing">
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
        
        <!-- Share Button (Bottom Left) -->
        <button class="share-btn" id="shareBtn">Personalize & Share //</button>
    </main>

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
    
    <!-- Share Modal -->
    <div class="share-modal" id="shareModal">
        <div class="share-modal__card">
            <button class="share-modal__close" id="shareClose">&times;</button>

            <div class="share-modal__title">Personalize & Share</div>
            <div class="share-modal__subtitle">
                Create a custom link that greets someone by name. Wheatley will adjust his first message based on the context you provide.
            </div>

            <form class="share-modal__form" onsubmit="return false;">
                <!-- Name Field -->
                <div class="share-modal__field">
                    <label class="share-modal__label" for="shareName">
                        Their Name <span class="share-modal__label-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="shareName" 
                        class="share-modal__input"
                        placeholder="Jordan"
                        required
                        autocomplete="off"
                    />
                </div>

                <!-- Context Field -->
                <div class="share-modal__field">
                    <label class="share-modal__label" for="shareContext">
                        Context <span style="color: #666; font-weight: 400; text-transform: none;">(Optional)</span>
                    </label>
                    <input 
                        type="text" 
                        id="shareContext" 
                        class="share-modal__input"
                        placeholder="Birthday, Convincing my boss, Designing your logo..."
                        maxlength="50"
                        autocomplete="off"
                    />
                    <div class="share-modal__char-count" id="shareCharCount">0 / 50</div>
                    <div class="share-modal__hint">
                        A hint about why you're sending them here. Wheatley will adjust his greeting accordingly.
                    </div>
                </div>

                <!-- Preview -->
                <div class="share-modal__field">
                    <div class="share-modal__preview">
                        <div class="share-modal__preview-label">Preview</div>
                        <div class="share-modal__preview-text" id="sharePreviewText">
                            Enter a name to see the preview...
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="share-modal__actions">
                    <button 
                        type="button" 
                        class="share-modal__button share-modal__button--primary" 
                        id="shareGenerate"
                        disabled
                    >
                        Generate & Copy Link
                    </button>
                </div>

                <!-- Success Message -->
                <div class="share-modal__success" id="shareSuccess">
                    <span class="share-modal__success-icon">✓</span>
                    <span id="shareSuccessText">Link copied to clipboard!</span>
                </div>
            </form>
        </div>
    </div>

    <?php wp_footer(); ?>

</body>
</html>