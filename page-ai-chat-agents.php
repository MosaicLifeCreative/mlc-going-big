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
                <p>This is different. An AI agent trained on your services, your pricing, your FAQs, your voice. It lives on your website and does the work. Answering questions, qualifying leads, booking appointments. All while you focus on everything else.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <div class="sp-inline-chat">
                    <script>window.chtlConfig = { chatbotId: "2733792244", display: "page_inline" }</script>
                    <div id="chtl-inline-bot" style="width: 100%; height: 500px; border-radius: 16px; overflow: hidden;"></div>
                    <script async data-id="2733792244" data-display="page_inline" id="chtl-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ WHY DIFFERENT (Dark, Image Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-split sp-split--reverse">
            <div class="sp-split__content reveal">
                <h2>Trained on<br>Your Business</h2>
                <p>Your agent knows your services, your service area, your pricing tiers. It speaks the way you speak to customers. Not like a robot reading a script.</p>
                <p>And you own it. No platform lock-in, no renting someone else's tool, no losing everything when you switch providers.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/ai-chat-agent-training-holographic-assistant.png" alt="Business owner handing colorful documents to a friendly glowing holographic AI assistant floating above his desk in a warm modern office" loading="lazy">
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
    <section class="wheatley-void" data-wheatley-page="ai-chat-agents" data-wheatley-context="AI Chat Agents. Custom AI chatbots trained on business data, deployed on client websites. You're supposed to sell this service.">
        <div class="wheatley-void__grid"></div>
        <div class="wheatley-void__content">
            <div class="wheatley-void__label">MLC Personality Core v2.7.4</div>
            <div class="wheatley-void__message" id="wheatleyPageMessage"></div>
        </div>
    </section>

    <!-- ═══ LIVE EXAMPLES (Dark) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-section__inner">
            <h2 class="sp-examples__heading reveal">See It Live</h2>
            <p class="sp-examples__desc reveal" style="--delay: 0.1s">These aren't mockups. These are real AI agents on real businesses, handling real conversations right now. Click any of them and start a chat.</p>
            <div class="sp-examples reveal" style="--delay: 0.2s">

                <a href="https://blackburnschimney.com" target="_blank" rel="noopener" class="sp-example">
                    <div class="sp-example__titlebar" style="background: #7e1c27">
                        <svg class="sp-example__titlebar-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" fill="rgba(255,255,255,0.9)"/></svg>
                        <span class="sp-example__titlebar-name">Blackburn's Chimney</span>
                    </div>
                    <div class="sp-example__chat-body">
                        <div class="sp-example__msg sp-example__msg--visitor">
                            <div class="sp-example__msg-bubble">I can smell gas even when my fireplace isn't lit. Is that dangerous?</div>
                        </div>
                        <div class="sp-example__msg sp-example__msg--agent">
                            <div class="sp-example__msg-bubble">That's definitely something to take seriously. A gas smell when the unit is off could mean a valve issue or a venting problem. I'd recommend not using it until we can get someone out to inspect it. Want me to help you schedule a service call?</div>
                        </div>
                    </div>
                    <div class="sp-example__input"><span>Type here...</span></div>
                </a>

                <a href="https://ohiopropertybrothers.com" target="_blank" rel="noopener" class="sp-example">
                    <div class="sp-example__titlebar" style="background: #bb0000">
                        <svg class="sp-example__titlebar-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" fill="rgba(255,255,255,0.9)"/></svg>
                        <span class="sp-example__titlebar-name">Ohio Property Brothers</span>
                    </div>
                    <div class="sp-example__chat-body">
                        <div class="sp-example__msg sp-example__msg--visitor">
                            <div class="sp-example__msg-bubble">I'm looking to redesign my kitchen.</div>
                        </div>
                        <div class="sp-example__msg sp-example__msg--agent">
                            <div class="sp-example__msg-bubble">Kitchen remodels are one of our most popular projects. To give you a ballpark, it helps to know a few things. Are you thinking a full layout change, or more of a cosmetic refresh with new cabinets and countertops?</div>
                        </div>
                    </div>
                    <div class="sp-example__input"><span>Type here...</span></div>
                </a>

                <a href="https://noebull.com" target="_blank" rel="noopener" class="sp-example">
                    <div class="sp-example__titlebar" style="background: #ebb929">
                        <svg class="sp-example__titlebar-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" fill="rgba(255,255,255,0.9)"/></svg>
                        <span class="sp-example__titlebar-name">Noebull Automotive</span>
                    </div>
                    <div class="sp-example__chat-body">
                        <div class="sp-example__msg sp-example__msg--visitor">
                            <div class="sp-example__msg-bubble">My car is making funny noises.</div>
                        </div>
                        <div class="sp-example__msg sp-example__msg--agent">
                            <div class="sp-example__msg-bubble">That could be a few things depending on when you hear it. Does it happen when you're braking, accelerating, turning, or just idling? That'll help us narrow it down before you bring it in.</div>
                        </div>
                        <div class="sp-example__msg sp-example__msg--agent">
                            <div class="sp-example__msg-bubble">Would you like me to help you schedule an appointment?</div>
                        </div>
                    </div>
                    <div class="sp-example__input"><span>Type here...</span></div>
                </a>

            </div>
        </div>
    </section>

    <!-- ═══ HOW IT WORKS (Light, Image Right) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-split">
            <div class="sp-split__content reveal">
                <h2>How It Works</h2>
                <p>You send us your documents. Service pages, pricing sheets, FAQs, whatever you've got. We train your agent on all of it.</p>
                <p>We deploy it to your website with your branding. You review the conversations, tell us what to tweak, and within a week you've got an AI employee that knows your business better than most of your actual employees.</p>
            </div>
            <div class="sp-split__media reveal" style="--delay: 0.2s">
                <img src="/wp-content/uploads/2026/02/ai-chat-agent-deployed-laptop-website.png" alt="Satisfied business owner leaning back in his chair with arms crossed while a friendly glowing AI assistant waves from a chat bubble on his laptop screen" loading="lazy">
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
