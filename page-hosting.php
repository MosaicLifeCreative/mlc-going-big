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
<body <?php body_class('mlc-page'); ?>>
<?php wp_body_open(); ?>

    <main>
    <!-- ═══ HERO ═══════════════════════════════════════ -->
    <section class="sp-hero">
        <?php mlc_render_gradient_blobs(); ?>
        <h1 class="sp-hero__title reveal">Managed<br>Hosting</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Cloud infrastructure that scales when you need it and disappears when you don't.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ MANIFESTO (Light, Centered Statement) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-statement reveal">
            <p class="sp-statement__text">Your website runs on the same cloud infrastructure as Google. Dedicated resources, not a shared apartment with a thousand strangers.</p>
        </div>
    </section>

    <!-- ═══ THE PROBLEM (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>Bargain Bin<br>Hosting</h2>
                <p>That $8/month hosting plan isn't saving you money. It's costing you customers. Slow load times, random outages, and a support team that responds sometime between now and never.</p>
                <p>Your site shares a server with thousands of other sites. One of them gets a traffic spike, and suddenly your homepage takes six seconds to load. That's not hosting. That's a liability.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/managed-hosting-server-room-chaos.png" alt="Frustrated business owner standing in front of a tangled messy shared hosting server rack" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ THE STACK (Light, Feature Grid) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">The Stack</h2>
            <div class="sp-features">
                <div class="sp-feature reveal">
                    <div class="sp-feature__number">01</div>
                    <h3 class="sp-feature__title">Cloud Infrastructure</h3>
                    <p class="sp-feature__desc">Dedicated CPU, RAM, and SSD storage on Google Cloud. Your resources are yours. Traffic spikes? Auto-scaling handles it without you picking up the phone.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.1s">
                    <div class="sp-feature__number">02</div>
                    <h3 class="sp-feature__title">Nightly Backups</h3>
                    <p class="sp-feature__desc">Automated every night, stored offsite for seven days. Something goes sideways? One click and you're back. No panic, no data loss, no starting from scratch.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.2s">
                    <div class="sp-feature__number">03</div>
                    <h3 class="sp-feature__title">3-Layer Caching</h3>
                    <p class="sp-feature__desc">Static caching, dynamic caching, and Memcached working together behind a global CDN. Your pages load fast everywhere, not just close to the server.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.3s">
                    <div class="sp-feature__number">04</div>
                    <h3 class="sp-feature__title">Security Stack</h3>
                    <p class="sp-feature__desc">Web application firewall with WordPress-specific rules. AI-powered threat blocking. Free SSL. Account isolation. The security most agencies charge extra for, included.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.4s">
                    <div class="sp-feature__number">05</div>
                    <h3 class="sp-feature__title">Staging Environments</h3>
                    <p class="sp-feature__desc">Clone your live site in one click. Test plugin updates, theme changes, new features. Break things safely. Push to production when you're confident, not when you're crossing your fingers.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.5s">
                    <div class="sp-feature__number">06</div>
                    <h3 class="sp-feature__title">Developer Access</h3>
                    <p class="sp-feature__desc">SSH, WP-CLI, Git integration, SFTP. Real tools for real development. No pointing and clicking through a control panel pretending to be a dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ MANAGED VS DIY (Dark, Text Left + Image Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>Managed Means<br>Handled</h2>
                <p>You could manage your own hosting. You could also change your own oil, rewire your own house, and represent yourself in court. The question isn't capability. It's priority.</p>
                <p>Server updates, security patches, PHP versions, SSL renewals, performance monitoring - we handle all of it. 99.99% uptime, backed by an SLA that actually means something. You focus on running your business.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/managed-hosting-relaxed-business-owner.png" alt="Relaxed business owner walking away from a clean server room with a confident wave goodbye" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ PORTAL (Light, Oval Viewport + Side Annotation) ═══ -->
    <section class="sp-section sp-section--light" data-wheatley-page="hosting" data-wheatley-context="Managed Hosting. Cloud infrastructure, nightly backups, 3-layer caching, security stack. White-labeled hosting on Google Cloud. You're supposed to sell this but you're bad at it.">
        <div class="sp-portal-group">
            <div class="sp-portal-wrap">
                <a href="/website-design" class="sp-portal sp-portal--orange" aria-label="Step through to Website Design">
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
        <h2 class="sp-cta__title reveal">Stop Thinking<br>About Servers</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">Managed cloud hosting for businesses that have better things to worry about.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Get Started</a>
    </section>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
