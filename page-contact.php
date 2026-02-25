<?php
/**
 * Template Name: Contact
 * Description: Contact page with CF7 form + Wheatley textarea draft
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class('mlc-page'); ?>>
<?php wp_body_open(); ?>

    <main>
    <!-- ═══ HERO ═══════════════════════════════════════ -->
    <section class="sp-hero">
        <?php mlc_render_gradient_blobs(); ?>
        <h1 class="sp-hero__title reveal">Let's<br>Talk</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Real conversations start here.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ FORM + CONTACT INFO ═══════════════════════ -->
    <section class="sp-section sp-section--light" data-wheatley-page="contact" data-wheatley-context="SPECIAL MODE: This is NOT a parallax window. Your output goes directly into the contact form textarea as a pre-filled draft message. You are 'helping' the visitor by writing their inquiry for them. DO NOT write Wheatley commentary or a monologue. Write AS IF you are the visitor composing a message to MLC asking about getting a website built. Stay in bad salesman character. The draft should sound like Wheatley ghostwriting a message on someone's behalf. Rambling, accidentally underselling, saying the quiet part out loud. No openers like 'Right so' or 'Alright'. No addressing the visitor. Just the draft text that would go in a contact form message field.">
        <div class="sp-section__inner">
            <div class="sp-statement reveal" style="margin-top: clamp(-40px, -4vw, -20px); margin-bottom: clamp(40px, 6vw, 80px);">
                <p class="sp-statement__text">We don't do ticket queues. We don't do 48-hour SLAs. You send a message, a real person reads it, and you hear back the same day.</p>
            </div>
            <div class="sp-contact-grid">

                <div class="sp-contact-form reveal">
                    <div class="sp-contact-form__wheatley-label">MLC Personality Core v2.7.4</div>
                    <?php echo do_shortcode('[contact-form-7 id="99c0016" title="MLC Contact Form"]'); ?>
                </div>

                <div class="sp-contact-info sp-contact-info--dark reveal" style="--delay: 0.15s">
                    <div class="sp-contact-info__item">
                        <div class="sp-contact-info__label">Email</div>
                        <div class="sp-contact-info__value">
                            <a href="mailto:trey@mosaiclifecreative.com?subject=Wheatley%20Sent%20Me">trey@mosaiclifecreative.com</a>
                        </div>
                    </div>
                    <div class="sp-contact-info__item">
                        <div class="sp-contact-info__label">Phone</div>
                        <div class="sp-contact-info__value">
                            <a href="tel:+13802013300">(380) 201-3300</a>
                        </div>
                        <div class="sp-contact-info__hint">Text first. Call after we're friends.</div>
                    </div>
                    <div class="sp-contact-info__item">
                        <div class="sp-contact-info__label">Location</div>
                        <div class="sp-contact-info__value">Columbus, Ohio</div>
                    </div>
                    <div class="sp-contact-info__item">
                        <div class="sp-contact-info__label">Response Time</div>
                        <div class="sp-contact-info__value">Usually within a few hours</div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
