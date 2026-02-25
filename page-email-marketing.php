<?php
/**
 * Template Name: Email Marketing
 * Description: Email marketing service page - sp- design system
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
        <h1 class="sp-hero__title reveal">Email<br>Marketing</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Infrastructure you own. An audience you keep. Results you can measure.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ MANIFESTO (Light, Centered Statement) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-statement reveal">
            <p class="sp-statement__text">Mailchimp charges you monthly rent to talk to your own customers. Every subscriber you earn costs you more money just to keep. That model works great. For Mailchimp.</p>
        </div>
    </section>

    <!-- ═══ THE PROBLEM (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>The Subscription<br>Trap</h2>
                <p>Constant Contact, Mailchimp, and every other email platform charge you based on how many people want to hear from you. Grow your list, pay more. Hit a new tier, pay more. Exist, pay more.</p>
                <p>They gate automation behind premium plans. They lock segmentation behind upgrades. They built a business model that punishes you for doing the one thing email marketing is supposed to do: build an audience.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/email-marketing-subscription-pricing-trap.png" alt="Small business owner overwhelmed by escalating email marketing subscription costs and upgrade notices" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ WHAT WE BUILD (Light, Feature Grid) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">What We Build</h2>
            <div class="sp-features">
                <div class="sp-feature reveal">
                    <div class="sp-feature__number">01</div>
                    <h3 class="sp-feature__title">Campaign Design</h3>
                    <p class="sp-feature__desc">Emails that look right and actually get opened. On-brand templates built to render correctly across every inbox and every device. No broken layouts, no clipped images, no "view in browser" apologies.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.1s">
                    <div class="sp-feature__number">02</div>
                    <h3 class="sp-feature__title">Automation Sequences</h3>
                    <p class="sp-feature__desc">Welcome series, follow-ups, drip campaigns, and nurture flows that run on autopilot. Set the logic once, and let it turn subscribers into customers while you focus on everything else.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.2s">
                    <div class="sp-feature__number">03</div>
                    <h3 class="sp-feature__title">List Segmentation</h3>
                    <p class="sp-feature__desc">The right message to the right people. Custom fields, behavioral targeting, engagement scoring. No more blasting your entire list with the same email and hoping for the best.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.3s">
                    <div class="sp-feature__number">04</div>
                    <h3 class="sp-feature__title">Analytics & Reporting</h3>
                    <p class="sp-feature__desc">Open rates, click rates, bounce rates, revenue attribution. Know exactly what's working, what's not, and what to change next. Real numbers, not vanity metrics.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ INFRASTRUCTURE (Dark, Text Left + Image Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>Infrastructure<br>You Own</h2>
                <p>Your emails send through Amazon Web Services. The same infrastructure used by the largest companies in the world. Enterprise-grade deliverability without the enterprise price tag.</p>
                <p>You pay when you send, not for existing. No per-subscriber fees. No feature gates. No upgrade tiers. Automation, segmentation, drip campaigns. Everything from day one. Your list grows for free.</p>
                <p>We handle the technical side too. SPF, DKIM, DMARC. We configure your DNS so your emails actually land in inboxes, not spam folders. Maximum deliverability, properly authenticated, from day one.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/email-marketing-owned-infrastructure-calm.png" alt="Relaxed business owner with clean email dashboard showing successful sends and simple server infrastructure" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ═══ CORRUPTED INBOX (Dark, Wheatley Treatment) ═══ -->
    <section class="sp-corrupted-inbox" data-wheatley-page="email-marketing" data-wheatley-context="Email Marketing. Self-hosted sending infrastructure on AWS. Pay-per-send, not per-subscriber. You're supposed to sell this but you're bad at it. Call out Mailchimp and Constant Contact with casual pity.">
        <div class="wheatley-void__grid"></div>
        <div class="sp-corrupted-inbox__content">
            <div class="sp-corrupted-inbox__label">MLC Personality Core v2.7.4</div>
            <div class="sp-corrupted-inbox__panel">
                <div class="sp-corrupted-inbox__header">
                    <span>INBOX</span>
                    <span>3 unread</span>
                </div>
                <div class="sp-corrupted-inbox__row sp-corrupted-inbox__row--corrupted" style="--delay: 0s">
                    <span class="sp-corrupted-inbox__sender">Mailch&#x2591;mp</span>
                    <span class="sp-corrupted-inbox__subject">Y0ur campaig&#x2588; has be&#x2591;n...</span>
                    <span class="sp-corrupted-inbox__time">2m</span>
                </div>
                <div class="sp-corrupted-inbox__row sp-corrupted-inbox__row--corrupted" style="--delay: 0.4s">
                    <span class="sp-corrupted-inbox__sender">Constant C&#x2588;ntact</span>
                    <span class="sp-corrupted-inbox__subject">&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588; UPGRADE Y0UR PL&#x2591;N</span>
                    <span class="sp-corrupted-inbox__time">1h</span>
                </div>
                <div class="sp-corrupted-inbox__row sp-corrupted-inbox__row--corrupted" style="--delay: 0.8s">
                    <span class="sp-corrupted-inbox__sender">no-reply</span>
                    <span class="sp-corrupted-inbox__subject">&#x2591;&#x2591;&#x2591;&#x2591; UNDELIV&#x2591;&#x2591;&#x2591;&#x2591;&#x2591;</span>
                    <span class="sp-corrupted-inbox__time">11h</span>
                </div>
                <div class="sp-corrupted-inbox__row sp-corrupted-inbox__row--corrupted" style="--delay: 1.2s">
                    <span class="sp-corrupted-inbox__sender">billing@m...</span>
                    <span class="sp-corrupted-inbox__subject">Your sub&#x2588;&#x2588;ription is &#x2591;&#x2591;&#x2591;</span>
                    <span class="sp-corrupted-inbox__time">3d</span>
                </div>

                <div class="sp-corrupted-inbox__separator"></div>

                <div class="sp-corrupted-inbox__email">
                    <div class="sp-corrupted-inbox__field">
                        <span class="sp-corrupted-inbox__field-label">FROM:</span>
                        wheatley@mosaiclifecreative.com
                    </div>
                    <div class="sp-corrupted-inbox__field">
                        <span class="sp-corrupted-inbox__field-label">SUBJ:</span>
                        honestly? don't read this
                    </div>
                    <div class="sp-corrupted-inbox__body" id="wheatleyPageMessage"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ════════════════════════════════════════ -->
    <section class="sp-cta">
        <h2 class="sp-cta__title reveal">Start Owning<br>Your Audience</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">Email marketing that builds relationships and drives revenue. Not just fills inboxes.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Let's Talk</a>
    </section>
    </main>

    <?php wp_footer(); ?>

</body>
</html>
