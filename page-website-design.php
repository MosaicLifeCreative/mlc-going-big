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

    <!-- Plus Jakarta Sans from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class('service-page'); ?>>
<?php wp_body_open(); // This triggers nav injection ?>

    <!-- Render shared gradient background -->
    <?php mlc_render_gradient_blobs(); ?>

    <!-- Nav is injected globally via functions.php -->

    <main class="service-page">
        <section class="service-hero">
            <h1>Website Design</h1>
            <p class="service-hero__subtitle">Your website isn't a brochure. It's the first conversation your business has with a stranger.</p>
        </section>

        <section class="service-content">
            <div class="service-content__card">
                <h2>The Problem with Most Websites</h2>
                <p>They look like everything else in their industry. Same layouts. Same stock photos. Same tone-deaf copy that sounds like it was written by a committee of robots.</p>
                <p>Your competitors are building digital wallpaper. You need a digital storefront that people actually remember.</p>
            </div>

            <div class="service-content__card">
                <h2>What We Actually Do</h2>
                <p>We build websites that stop people mid-scroll. Sites that feel less like corporate brochures and more like actual conversations.</p>
                <p>Every project starts with understanding what makes your business different—then we build something that shows it, not just says it.</p>
            </div>

            <div class="service-content__card">
                <h2>Who This Is For</h2>
                <p>Service businesses tired of bland templates. Contractors who want to look as professional as they actually are. Anyone who knows their website should be doing more than just "existing."</p>
            </div>

            <div class="service-content__cta">
                <h2>Let's Build Something Different</h2>
                <p>Starting at $8,000 for complete custom design and development. Or $2,000 for a refresh of your existing site.</p>
                <a href="#" class="btn btn--primary">Get Started</a>
            </div>
        </section>
    </main>

    <?php wp_footer(); ?>

</body>
</html>