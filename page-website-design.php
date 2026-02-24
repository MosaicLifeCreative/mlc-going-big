<?php
/**
 * Template Name: Website Design
 * Description: Website Design service page
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

    <!-- ═══ HERO ═══════════════════════════════════════ -->
    <section class="sp-hero">
        <?php mlc_render_gradient_blobs(); ?>
        <h1 class="sp-hero__title reveal">Website<br>Design</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Your website isn't a brochure. It's the first conversation your business has with a stranger.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ MANIFESTO (Light, Centered Statement) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-statement reveal">
            <p class="sp-statement__text">Most businesses treat their website like a checkbox. We treat it like the most important employee you'll ever hire.</p>
        </div>
    </section>

    <!-- ═══ THE PROBLEM (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>Digital Wallpaper<br>Is Everywhere</h2>
                <p>Same layouts. Same stock photos. Same copy that sounds like it was written by a committee. Your competitors are building websites people scroll past and forget.</p>
                <p>You need something people actually remember. A site that feels like walking into your best showroom, not browsing a template gallery.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/website-design-generic-template-hallway.png" alt="Overwhelmed business owner walking through endless hallway of identical generic website templates" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ THE PROCESS (Light, Stepped) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">How We Build</h2>
            <div class="sp-process">
                <div class="sp-process__step reveal">
                    <div class="sp-process__num">01</div>
                    <h3 class="sp-process__title">Discovery</h3>
                    <p class="sp-process__desc">We learn how your business actually works. Your customers, your competitors, your goals. No questionnaire. A real conversation.</p>
                </div>
                <div class="sp-process__step reveal" style="--delay: 0.1s">
                    <div class="sp-process__num">02</div>
                    <h3 class="sp-process__title">Design</h3>
                    <p class="sp-process__desc">Custom layouts built around how your customers think and buy. You see it before we build it. You approve every detail.</p>
                </div>
                <div class="sp-process__step reveal" style="--delay: 0.2s">
                    <div class="sp-process__num">03</div>
                    <h3 class="sp-process__title">Build</h3>
                    <p class="sp-process__desc">Clean code, fast load times, mobile-first. Built on WordPress so you can actually update it yourself when you need to.</p>
                </div>
                <div class="sp-process__step reveal" style="--delay: 0.3s">
                    <div class="sp-process__num">04</div>
                    <h3 class="sp-process__title">Launch</h3>
                    <p class="sp-process__desc">We handle DNS, SSL, email, hosting setup. You get a site that's live, fast, and ready for traffic. Then we stick around.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ BUILT AROUND YOU (Dark, Text Left + Image Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>Built Around<br>Your Business</h2>
                <p>No templates. No page builders. Every layout, every interaction, every word is designed around how your customers actually think and buy.</p>
                <p>We build for the person on their phone at 10 PM deciding between you and the other guy. That decision takes about three seconds. Your website needs to win it.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/custom-website-design-business-owner-excited.png" alt="Excited business owner pointing at a custom website design on his monitor in a warm creative office" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ PORTAL (Light, Oval Viewport + Side Annotation) ═══ -->
    <section class="sp-section sp-section--light" data-wheatley-page="website-design" data-wheatley-context="Website Design. Custom websites for service businesses. No templates, no page builders. You're supposed to sell this service.">
        <div class="sp-portal-group">
            <div class="sp-portal-wrap">
                <a href="/hosting" class="sp-portal sp-portal--blue" aria-label="Step through to Hosting">
                    <div class="sp-portal__scene" style="background-image: url('/wp-content/uploads/2026/02/web-design-page-screenshot-scaled.png')"></div>
                    <div class="sp-portal__hover-label">
                        <span>Step through</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <div class="sp-portal__ring"></div>
            </div>
            <div class="sp-portal__annotation">
                <div class="sp-portal__connector"></div>
                <div class="sp-portal__annotation-inner">
                    <div class="sp-portal__label">MLC Personality Core v2.7.4</div>
                    <div class="sp-portal__message" id="wheatleyPageMessage"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ════════════════════════════════════════ -->
    <section class="sp-cta">
        <h2 class="sp-cta__title reveal">Let's Build Something<br>Different</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">Custom design and development for businesses that want to stand out, not blend in.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Get Started</a>
    </section>

    <?php wp_footer(); ?>

</body>
</html>
