<?php
/**
 * 404 Error Page
 * Wheatley, alone and confused.
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
    <title>404 — Page Not Found | <?php bloginfo('name'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class('mlc-page mlc-404'); ?>>
<?php wp_body_open(); ?>

    <main class="mlc-404__wrap">
        <?php mlc_render_gradient_blobs(); ?>

        <div class="mlc-404__content">
            <div class="mlc-404__code">404</div>
            <div class="mlc-404__wheatley">
                <span class="mlc-404__label">MLC PERSONALITY CORE V2.7.4</span>
                <p class="mlc-404__message" id="wheatley404"></p>
            </div>
            <div class="mlc-404__nav">
                <a href="/" class="sp-cta__button">Take Me Home</a>
            </div>
        </div>
    </main>

    <script>
    (function() {
        var messages = [
            "Right, so... this page doesn't exist. Which is awkward because you clearly thought it did. That's on both of us, really.",
            "Alright, bit of a situation. This page has gone missing. I've been here the whole time and I definitely didn't see it leave.",
            "Hang on. This page was here. I'm almost certain. Well, fairly certain. Look, pages don't just vanish. Usually.",
            "Right, so... nobody's here. Just me. Alone. In the void. Which is fine. I'm fine with it. This is fine.",
            "Okay, so this page doesn't exist anymore. The person who built me probably deleted it during the rebrand. Very thorough, that one. Didn't think to leave a forwarding address though.",
            "Listen, I don't want to alarm you, but this page is gone. Completely gone. I've checked. Twice. Three times, actually. I had nothing else to do."
        ];
        var msg = messages[Math.floor(Math.random() * messages.length)];
        var el = document.getElementById('wheatley404');
        var i = 0;
        function type() {
            if (i < msg.length) {
                el.textContent += msg.charAt(i);
                i++;
                setTimeout(type, 28);
            } else {
                el.insertAdjacentHTML('beforeend', '<span class="wheatley-cursor"></span>');
            }
        }
        type();
    })();
    </script>

    <?php wp_footer(); ?>

</body>
</html>
