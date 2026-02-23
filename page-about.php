<?php
/**
 * Template Name: About
 * Description: About page with personal story and values
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
                <h1 class="service-hero__title">About</h1>
                <p class="service-hero__subtitle">Built by someone who actually builds things.</p>
            </section>

            <section class="service-content">
                <div class="about-split">
                    <div class="about-photo">
                        <img src="/wp-content/uploads/2026/02/trey-at-buffalo-park.jpg" alt="Trey Kauffman at Buffalo Park, Flagstaff" loading="lazy">
                    </div>
                    <div class="about-text">
                        <h2>Hey, I'm Trey.</h2>
                        <p>I run Mosaic Life Creative out of Columbus, Ohio — specifically the Grove City area, if you're local. I build websites and AI agents for service businesses that are tired of looking like everyone else.</p>
                        <p>I started in web design because I kept seeing the same problem: businesses that do incredible work, represented by websites that look like they were built in 2014. The disconnect between what these companies actually deliver and what their online presence says about them was massive.</p>
                        <p>Then AI changed everything. I watched service businesses struggle to answer the same questions over and over, miss leads because nobody was available at 9 PM on a Tuesday, and waste time on phone calls that could've been handled automatically. So I started building AI agents — trained on real business data, deployed on real websites, handling real conversations.</p>
                        <p>Now that's the core of what I do. Websites that stand out, AI agents that work around the clock, and a refusal to build anything that looks like everything else.</p>
                    </div>
                </div>

                <div class="service-content__block service-content__block--featured">
                    <h2>What We Believe</h2>
                    <div class="about-values">
                        <div class="about-value">
                            <h3>Ownership Over Rental</h3>
                            <p>Your website, your data, your AI agent. No platform lock-in. No renting someone else's tools. You own what we build.</p>
                        </div>
                        <div class="about-value">
                            <h3>Show, Don't Tell</h3>
                            <p>We don't have a portfolio page full of screenshots. We have this website — every interaction, every detail, every hidden easter egg is the proof.</p>
                        </div>
                        <div class="about-value">
                            <h3>Built Different</h3>
                            <p>Not different for the sake of it. Different because your business deserves better than a template and a prayer.</p>
                        </div>
                    </div>
                </div>

                <div class="service-content__block">
                    <h2>The Community</h2>
                    <p>I'm the marketing committee chair for the Grove City Area Chamber of Commerce and an active member of AmSpirit Business Connections, where I've claimed the AI category — because someone had to.</p>
                    <p>If you're in the Columbus area, there's a good chance we'll run into each other. And if you've noticed the countdown timer on the homepage... well, you're already playing.</p>
                </div>

                <div class="service-content__block--dark">
                    <h2>Let's Figure Out What You Need</h2>
                    <p>Whether it's a website, an AI agent, or both — the first step is a conversation.</p>
                    <a href="/contact" class="service-content__cta-button">Get in Touch</a>
                </div>
            </section>

        </div>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
