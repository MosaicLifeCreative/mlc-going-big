<?php
/**
 * Template Name: Contact
 * Description: Contact page with form and info
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
<body <?php body_class('service-page'); ?>>
<?php wp_body_open(); ?>

    <?php mlc_render_gradient_blobs(); ?>

    <main class="service-page">
        <div class="service-page__container">

            <section class="service-hero">
                <h1 class="service-hero__title">Contact</h1>
                <p class="service-hero__subtitle">Let's figure out what you need.</p>
            </section>

            <section class="service-content">
                <div class="contact-grid">
                    <div class="contact-form">
                        <form id="mlcContactForm" method="post" action="">
                            <?php wp_nonce_field('mlc_contact_form', 'mlc_contact_nonce'); ?>
                            <div class="contact-form__group">
                                <label class="contact-form__label" for="contact-name">Name</label>
                                <input type="text" id="contact-name" name="contact_name" required autocomplete="name" placeholder="Your name">
                            </div>
                            <div class="contact-form__group">
                                <label class="contact-form__label" for="contact-email">Email</label>
                                <input type="email" id="contact-email" name="contact_email" required autocomplete="email" placeholder="your@email.com">
                            </div>
                            <div class="contact-form__group">
                                <label class="contact-form__label" for="contact-phone">Phone <span style="font-weight: 400; text-transform: none; letter-spacing: 0;">(optional)</span></label>
                                <input type="tel" id="contact-phone" name="contact_phone" autocomplete="tel" placeholder="(555) 555-5555">
                            </div>
                            <div class="contact-form__group">
                                <label class="contact-form__label" for="contact-message">What are you looking for?</label>
                                <textarea id="contact-message" name="contact_message" required placeholder="Tell us about your project, your business, or what you're trying to solve."></textarea>
                            </div>
                            <button type="submit" class="contact-form__submit">Send Message</button>
                        </form>
                    </div>

                    <div class="contact-info">
                        <div class="contact-info__item">
                            <div class="contact-info__label">Email</div>
                            <div class="contact-info__value">
                                <a href="mailto:trey@mosaiclifecreative.com">trey@mosaiclifecreative.com</a>
                            </div>
                        </div>
                        <div class="contact-info__item">
                            <div class="contact-info__label">Location</div>
                            <div class="contact-info__value">Columbus, Ohio</div>
                        </div>
                        <div class="contact-info__item">
                            <div class="contact-info__label">Response Time</div>
                            <div class="contact-info__value">Usually within a few hours</div>
                        </div>
                        <div class="contact-info__item">
                            <div class="contact-info__label">Prefer to talk?</div>
                            <div class="contact-info__value">Skip the form and book a call directly.</div>
                            <a href="#" class="contact-book-link">Book a Call</a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
