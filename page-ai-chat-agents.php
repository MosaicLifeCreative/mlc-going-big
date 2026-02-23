<?php
/**
 * Template Name: AI Chat Agents
 * Description: AI Chat Agents service page - flagship offering
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
        <h1 class="sp-hero__title reveal">AI Chat<br>Agents</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Your best employee works 24/7, never calls in sick, and actually reads the manual.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ WHAT IT IS (Light, Text Left + Image Right) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>Not a Chatbot.<br>An Agent.</h2>
                <p>Most "AI chatbots" are glorified FAQ pages with a chat bubble. They give generic answers because they weren't built with your business in mind.</p>
                <p>This is different. An AI agent trained on your services, your pricing, your FAQs, your voice. It lives on your website and does the work — answering questions, qualifying leads, booking appointments — while you focus on everything else.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-image-placeholder">
                    IMAGE — Agent conversation screenshot
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ WHY DIFFERENT (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>Trained on<br>Your Business</h2>
                <p>Your agent knows your services, your service area, your pricing tiers. It speaks the way you speak to customers — not like a robot reading a script.</p>
                <p>And you own it. No platform lock-in, no renting someone else's tool, no losing everything when you switch providers.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-image-placeholder">
                    IMAGE — Dashboard or training interface
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FEATURES (Light, Large Numbered) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">What You Get</h2>
            <div class="sp-features">
                <div class="sp-feature reveal">
                    <div class="sp-feature__number">01</div>
                    <h3 class="sp-feature__title">Custom Training</h3>
                    <p class="sp-feature__desc">Your agent is trained on your documents, your services, and your way of talking to customers. Not generic responses pulled from the internet.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.1s">
                    <div class="sp-feature__number">02</div>
                    <h3 class="sp-feature__title">Lead Qualification</h3>
                    <p class="sp-feature__desc">Your agent asks the right questions, collects contact info, and filters out tire-kickers before they ever reach your phone.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.2s">
                    <div class="sp-feature__number">03</div>
                    <h3 class="sp-feature__title">Analytics Dashboard</h3>
                    <p class="sp-feature__desc">See every conversation, track what people are asking, and understand what your customers actually want to know.</p>
                </div>
                <div class="sp-feature reveal" style="--delay: 0.3s">
                    <div class="sp-feature__number">04</div>
                    <h3 class="sp-feature__title">Human Escalation</h3>
                    <p class="sp-feature__desc">When someone needs a real person, the agent hands off gracefully. No dead ends, no frustration, no lost leads.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ WHEATLEY VOID ═══════════════════════════ -->
    <section class="wheatley-void" data-wheatley-page="ai-chat-agents" data-wheatley-context="AI Chat Agents — custom AI chatbots trained on business data, deployed on client websites. You're supposed to sell this service.">
        <div class="wheatley-void__grid"></div>
        <div class="wheatley-void__content">
            <div class="wheatley-void__label">MLC Personality Core v2.7.4</div>
            <div class="wheatley-void__message" id="wheatleyPageMessage"></div>
        </div>
    </section>

    <!-- ═══ LIVE EXAMPLES (Dark) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-section__inner">
            <div class="sp-split">
                <div class="sp-split__content reveal">
                    <h2>See It Live</h2>
                    <p>These aren't mockups or demos. These are real AI agents running on real businesses, handling real conversations right now. Visit any of them and start a chat.</p>
                </div>
                <div class="reveal" style="--delay: 0.15s">
                    <div class="sp-examples">
                        <a href="https://blackburnschimney.com" target="_blank" rel="noopener" class="sp-example">
                            <div class="sp-example__name">Blackburn's Chimney</div>
                            <div class="sp-example__desc">Chimney sweeping & repair</div>
                        </a>
                        <a href="https://ohiopropertybrothers.com" target="_blank" rel="noopener" class="sp-example">
                            <div class="sp-example__name">Ohio Property Brothers</div>
                            <div class="sp-example__desc">Property services</div>
                        </a>
                        <a href="https://noebull.com" target="_blank" rel="noopener" class="sp-example">
                            <div class="sp-example__name">Noebull</div>
                            <div class="sp-example__desc">Service business</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ HOW IT WORKS (Light, Image Right) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>How It Works</h2>
                <p>You send us your documents — service pages, pricing sheets, FAQs, whatever you've got. We train your agent on all of it.</p>
                <p>We deploy it to your website with your branding. You review the conversations, tell us what to tweak, and within a week you've got an AI employee that knows your business better than most of your actual employees.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-image-placeholder">
                    IMAGE — Setup process or deployment
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ════════════════════════════════════════ -->
    <section class="sp-cta">
        <h2 class="sp-cta__title reveal">Ready to Put<br>AI to Work?</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">AI agents for service businesses that want to capture every lead, answer every question, and never miss a call.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Get Your Agent</a>
    </section>

    <?php wp_footer(); ?>

</body>
</html>
