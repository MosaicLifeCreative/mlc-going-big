<?php
/**
 * Template Name: Maintenance
 * Description: Website maintenance service page - sp- design system
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
        <h1 class="sp-hero__title reveal">Website<br>Maintenance</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Your site doesn't stop needing attention just because it launched.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ MANIFESTO (Light, Centered Statement) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-statement reveal">
            <p class="sp-statement__text">Most agencies build your site and disappear. Most business owners don't know what they don't know. Both end the same way. A site that slowly stops working.</p>
        </div>
    </section>

    <!-- ═══ THE PROBLEM (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>The Slow<br>Decline</h2>
                <p>WordPress releases security patches. Plugins push updates. PHP versions change. SSL certificates expire. And somewhere in all of that, your site quietly breaks. You don't find out until a customer tells you. Or until Google flags you as insecure and drops your ranking overnight.</p>
                <p>The agency that built your site? They moved on six months ago. And the "I'll just click Update All" approach? That's how you end up staring at a white screen on a Tuesday morning wondering what happened.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-image-placeholder">
                    IMAGE — Business owner staring at broken/crashed website screen
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ WHAT'S INCLUDED (Light, Feature Grid) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">What's Included</h2>
            <div class="sp-features">
                <div class="sp-feature reveal">
                    <div class="sp-feature__number">01</div>
                    <h3 class="sp-feature__title">WordPress Updates</h3>
                    <p class="sp-feature__desc">Core, plugins, and themes tested in staging before deploying to production. No more crossing your fingers after clicking "Update All." If something breaks, it breaks on the test site. Not yours.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.1s">
                    <div class="sp-feature__number">02</div>
                    <h3 class="sp-feature__title">Security Monitoring</h3>
                    <p class="sp-feature__desc">Firewall rules, malware scans, brute force protection. Continuous monitoring for vulnerabilities and suspicious activity. Problems get caught before they become emergencies.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.2s">
                    <div class="sp-feature__number">03</div>
                    <h3 class="sp-feature__title">Uptime Monitoring</h3>
                    <p class="sp-feature__desc">24/7 monitoring with instant alerts. If your site goes down at 3 AM, we know about it before you do. Fast response, minimal downtime, no waiting until Monday morning.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.3s">
                    <div class="sp-feature__number">04</div>
                    <h3 class="sp-feature__title">Performance Tuning</h3>
                    <p class="sp-feature__desc">Database optimization, caching configuration, speed audits. Sites get slower over time. We make sure yours doesn't. Every page load matters when someone's deciding between you and the next result.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.4s">
                    <div class="sp-feature__number">05</div>
                    <h3 class="sp-feature__title">Content Changes</h3>
                    <p class="sp-feature__desc">Text updates, image swaps, minor layout adjustments. Need to change your hours, update a staff photo, or tweak a headline? Send us a message and it's done. No ticket queue, no 48-hour SLA.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.5s">
                    <div class="sp-feature__number">06</div>
                    <h3 class="sp-feature__title">Monthly Reports</h3>
                    <p class="sp-feature__desc">Clear summary of everything that happened. What was updated, what was blocked, how your site is performing. Real numbers in plain language. No jargon, no mystery, no 40-page PDF nobody reads.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ INSURANCE (Dark, Text Left + Image Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>Insurance, Not<br>an Expense</h2>
                <p>You don't cancel car insurance because you haven't had an accident. Your website handles leads, revenue, and reputation around the clock. A hacked site costs thousands to clean up. A broken site costs you every customer who tried to visit and couldn't.</p>
                <p>Maintenance is a fraction of what it costs to rebuild after something goes wrong. And unlike the agency that built your site and moved on, we actually stick around.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-image-placeholder">
                    IMAGE — Calm business owner with site health dashboard showing green status
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ TERMINAL (Dark, Wheatley Treatment) ═══ -->
    <section class="sp-terminal" data-wheatley-page="maintenance" data-wheatley-context="Website Maintenance. Full-stack: updates, security, uptime, performance, content changes, monthly reports. Separate from hosting. You're supposed to sell this but you're bad at it. Shade both agencies that build and disappear AND business owners who break their own sites.">
        <div class="wheatley-void__grid"></div>
        <div class="sp-terminal__content">
            <div class="sp-terminal__label">MLC Personality Core v2.7.4</div>
            <div class="sp-terminal__panel">
                <div class="sp-terminal__header">
                    <div class="sp-terminal__dots">
                        <span></span><span></span><span></span>
                    </div>
                    <span class="sp-terminal__title">maintenance_core — bash</span>
                </div>
                <div class="sp-terminal__body">
                    <div class="sp-terminal__line sp-terminal__line--system" style="--delay: 0s">
                        <span class="sp-terminal__timestamp">[14:23:01]</span>
                        <span class="sp-terminal__status sp-terminal__status--ok">&#10003;</span>
                        WordPress 6.7.1 update verified
                    </div>
                    <div class="sp-terminal__line sp-terminal__line--system" style="--delay: 0.3s">
                        <span class="sp-terminal__timestamp">[14:23:14]</span>
                        <span class="sp-terminal__status sp-terminal__status--ok">&#10003;</span>
                        12 plugins updated (0 conflicts)
                    </div>
                    <div class="sp-terminal__line sp-terminal__line--system" style="--delay: 0.6s">
                        <span class="sp-terminal__timestamp">[14:23:38]</span>
                        <span class="sp-terminal__status sp-terminal__status--ok">&#10003;</span>
                        Security scan clean. 3 brute force attempts blocked
                    </div>
                    <div class="sp-terminal__line sp-terminal__line--system" style="--delay: 0.9s">
                        <span class="sp-terminal__timestamp">[14:24:02]</span>
                        <span class="sp-terminal__status sp-terminal__status--ok">&#10003;</span>
                        Backup completed. Stored offsite (7-day retention)
                    </div>
                    <div class="sp-terminal__line sp-terminal__line--warn" style="--delay: 1.2s">
                        <span class="sp-terminal__timestamp">[14:24:15]</span>
                        <span class="sp-terminal__status sp-terminal__status--warn">!</span>
                        SSL certificate renews in 23 days. Scheduled
                    </div>
                    <div class="sp-terminal__separator"></div>
                    <div class="sp-terminal__line sp-terminal__line--prompt" style="--delay: 1.5s">
                        <span class="sp-terminal__prompt">wheatley@mlc ~$</span>
                        <span id="wheatleyPageMessage"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ════════════════════════════════════════ -->
    <section class="sp-cta">
        <h2 class="sp-cta__title reveal">Keep It<br>Running Right</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">Ongoing maintenance so your website stays secure, fast, and doing its job.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Let's Talk</a>
    </section>

    <?php wp_footer(); ?>

</body>
</html>
