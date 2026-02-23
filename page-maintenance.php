<?php
/**
 * Template Name: Maintenance
 * Description: Website maintenance service page
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
                <h1 class="service-hero__title">Maintenance</h1>
                <p class="service-hero__subtitle">Websites aren't set-it-and-forget-it. They're living, breathing things that need attention.</p>
            </section>

            <section class="service-content">
                <div class="service-content__block">
                    <h2>What Happens When You Don't</h2>
                    <p>WordPress releases security patches. Plugins push updates. PHP versions change. SSL certificates expire. And somewhere in all of that, your site quietly breaks — and you don't find out until a customer tells you.</p>
                    <p>Or worse, you find out when Google drops your ranking because your site's been flagged as insecure.</p>
                </div>

                <div class="service-content__block service-content__block--featured">
                    <h2>What's Included</h2>
                    <div class="service-features">
                        <div class="service-feature">
                            <div class="service-feature__icon">01</div>
                            <div class="service-feature__text">
                                <h3>WordPress Updates</h3>
                                <p>Core updates applied and tested so your site stays current without breaking anything in the process.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">02</div>
                            <div class="service-feature__text">
                                <h3>Plugin Management</h3>
                                <p>Updates tested in staging first, rolled out carefully. No more crossing your fingers after clicking "Update All."</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">03</div>
                            <div class="service-feature__text">
                                <h3>Security Monitoring</h3>
                                <p>Continuous monitoring for malware, vulnerabilities, and suspicious activity. Problems get caught early.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">04</div>
                            <div class="service-feature__text">
                                <h3>Monthly Reports</h3>
                                <p>Clear summary of what was updated, what was fixed, and how your site is performing. No jargon, no mystery.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-content__block">
                    <h2>The Real Cost of Neglect</h2>
                    <p>A hacked site costs thousands to clean up. A broken site costs you every customer who tried to visit and couldn't. An outdated site costs you the trust of everyone who notices.</p>
                    <p>Maintenance isn't an expense. It's insurance for the investment you already made.</p>
                </div>

                <div class="service-content__block--dark">
                    <h2>Keep It Running Right</h2>
                    <p>Ongoing maintenance so your website stays secure, fast, and doing its job.</p>
                    <a href="/contact" class="service-content__cta-button">Get Started</a>
                </div>
            </section>

        </div>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
