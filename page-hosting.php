<?php
/**
 * Template Name: Hosting
 * Description: Managed hosting service page
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
                <h1 class="service-hero__title">Hosting</h1>
                <p class="service-hero__subtitle">Your website needs to live somewhere that doesn't crash when it matters most.</p>
            </section>

            <section class="service-content">
                <div class="service-content__block">
                    <h2>Why It Matters</h2>
                    <p>Cheap hosting is cheap for a reason. Slow load times, random downtime, and zero support when something breaks at 2 AM on a Saturday.</p>
                    <p>Your website is your storefront. You wouldn't rent a building with a landlord who doesn't answer the phone.</p>
                </div>

                <div class="service-content__block service-content__block--featured">
                    <h2>What's Included</h2>
                    <div class="service-features">
                        <div class="service-feature">
                            <div class="service-feature__icon">01</div>
                            <div class="service-feature__text">
                                <h3>Managed WordPress</h3>
                                <p>Optimized server environment built specifically for WordPress performance and security.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">02</div>
                            <div class="service-feature__text">
                                <h3>Daily Backups</h3>
                                <p>Automatic daily backups with one-click restore. If something breaks, you're never more than 24 hours from a clean copy.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">03</div>
                            <div class="service-feature__text">
                                <h3>SSL Certificate</h3>
                                <p>Free SSL included. Your site stays secure, your visitors stay confident, and Google stays happy.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">04</div>
                            <div class="service-feature__text">
                                <h3>99.9% Uptime</h3>
                                <p>Your site stays online. Period. With monitoring and alerts so issues get caught before your customers notice.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-content__block">
                    <h2>Managed vs. DIY</h2>
                    <p>You could manage your own hosting. You could also change your own oil, fix your own plumbing, and do your own taxes. The question isn't whether you can — it's whether that's the best use of your time.</p>
                    <p>Managed hosting means someone else handles the server updates, security patches, and 2 AM emergencies. You focus on your business.</p>
                </div>

                <div class="service-content__block--dark">
                    <h2>Keep Your Site Fast and Online</h2>
                    <p>Reliable managed hosting so you never have to think about servers again.</p>
                    <a href="/contact" class="service-content__cta-button">Learn More</a>
                </div>
            </section>

        </div>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
