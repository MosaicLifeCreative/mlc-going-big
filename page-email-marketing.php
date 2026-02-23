<?php
/**
 * Template Name: Email Marketing
 * Description: Email marketing service page
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
                <h1 class="service-hero__title">Email Marketing</h1>
                <p class="service-hero__subtitle">Social media rents your audience. Email lets you own it.</p>
            </section>

            <section class="service-content">
                <div class="service-content__block">
                    <h2>Why Email Still Wins</h2>
                    <p>Every dollar spent on email marketing returns an average of $36. No other channel comes close. Not social. Not paid ads. Not billboards.</p>
                    <p>The difference is ownership. Your email list is yours. No algorithm changes, no platform shutdowns, no paying to reach people who already follow you.</p>
                </div>

                <div class="service-content__block service-content__block--featured">
                    <h2>What We Build</h2>
                    <div class="service-features">
                        <div class="service-feature">
                            <div class="service-feature__icon">01</div>
                            <div class="service-feature__text">
                                <h3>Campaign Design</h3>
                                <p>Emails that look good and actually get opened. On-brand templates that work across every inbox and device.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">02</div>
                            <div class="service-feature__text">
                                <h3>Automation Sequences</h3>
                                <p>Welcome series, follow-ups, and nurture campaigns that run on autopilot and turn subscribers into customers.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">03</div>
                            <div class="service-feature__text">
                                <h3>List Management</h3>
                                <p>Clean, segmented lists that reach the right people with the right message. No blasting everyone with everything.</p>
                            </div>
                        </div>
                        <div class="service-feature">
                            <div class="service-feature__icon">04</div>
                            <div class="service-feature__text">
                                <h3>Analytics & Reporting</h3>
                                <p>Open rates, click rates, revenue attribution. Know exactly what's working and what needs to change.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-content__block">
                    <h2>Strategy First</h2>
                    <p>We don't just set up a tool and hand you the keys. Every email campaign starts with understanding your audience, your goals, and what's actually going to move the needle for your business.</p>
                    <p>Then we build it, test it, and optimize it. Because sending emails isn't the hard part — sending the right ones is.</p>
                </div>

                <div class="service-content__block--dark">
                    <h2>Start Owning Your Audience</h2>
                    <p>Email marketing that builds relationships and drives revenue — not just fills inboxes.</p>
                    <a href="/contact" class="service-content__cta-button">Let's Talk</a>
                </div>
            </section>

        </div>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
