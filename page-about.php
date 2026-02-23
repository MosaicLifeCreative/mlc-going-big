<?php
/**
 * Template Name: About
 * Description: About page - clean bio + journal records
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
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class('mlc-page'); ?>>
<?php wp_body_open(); ?>

    <!-- ═══════════════════════════════════════════════════════════
         LAYER 1 — CLEAN ABOUT
         ═══════════════════════════════════════════════════════════ -->

    <!-- ═══ HERO ═══════════════════════════════════════ -->
    <section class="sp-hero">
        <?php mlc_render_gradient_blobs(); ?>
        <h1 class="sp-hero__title reveal">About</h1>
        <p class="sp-hero__subtitle reveal" style="--delay: 0.15s">Built by someone who actually builds things.</p>
        <div class="sp-hero__accent reveal" style="--delay: 0.3s"></div>
    </section>

    <!-- ═══ MANIFESTO (Light) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-statement reveal">
            <p class="sp-statement__text">One person. One AI. No account managers, no project coordinators, no "circle back next quarter." You talk to the person who builds it.</p>
        </div>
    </section>

    <!-- ═══ BIO (Dark, Photo Left + Text Right) ═══ -->
    <section class="sp-section sp-section--dark">
        <div class="sp-about-split">
            <div class="sp-about-photo reveal">
                <img src="/wp-content/uploads/2026/02/trey-at-buffalo-park.jpg" alt="Trey at Buffalo Park, Flagstaff Arizona" loading="lazy">
            </div>
            <div class="sp-about-text reveal" style="--delay: 0.15s">
                <h2>Hey, I'm Trey.</h2>
                <p>I run Mosaic Life Creative out of Grove City, Ohio - just south of Columbus. I build websites and AI chat agents for service businesses. HVAC contractors, chimney sweeps, property maintenance companies. The people who actually fix things.</p>
                <p>Before this, I spent years in corporate IT watching good businesses get bad websites from agencies that treated them like ticket numbers. So I started building them myself. Custom code, not templates. Actual conversations, not questionnaires.</p>
                <p>The AI side happened because I kept watching businesses answer the same ten questions every day. Now their websites answer for them - 24 hours, no hold music, no "we'll get back to you."</p>
            </div>
        </div>
    </section>

    <!-- ═══ VALUES (Light) ═══ -->
    <section class="sp-section sp-section--light">
        <div class="sp-section__inner">
            <h2 class="sp-split__content reveal" style="font-size: clamp(36px, 5vw, 64px); font-weight: 800; letter-spacing: -2px; margin-bottom: clamp(40px, 6vw, 80px);">What We Believe</h2>
            <div class="sp-values">
                <div class="sp-value reveal">
                    <h3>Ownership Over Rental</h3>
                    <p>Your website, your code, your data. You're never locked into a platform that holds your business hostage. If you leave, everything goes with you.</p>
                </div>
                <div class="sp-value reveal" style="--delay: 0.1s">
                    <h3>Built, Not Assembled</h3>
                    <p>No page builders. No drag-and-drop templates dressed up as custom work. Every line of code is written for your business, your customers, your goals.</p>
                </div>
                <div class="sp-value reveal" style="--delay: 0.2s">
                    <h3>The Mystery Is The Portfolio</h3>
                    <p>We don't do case studies and stock photo grids. This site - the one you're on right now - is the proof. If you're still reading, it's working.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
         LAYER 2 — THE JOURNAL
         Myst journal aesthetic + Portal filing system + hunt clues
         ═══════════════════════════════════════════════════════════ -->

    <section class="sp-journal" data-subject="2237" data-clearance="standard" data-sequence="4-8-15-16-23-42">

        <!-- Classification Stamp -->
        <div class="sp-journal__stamp reveal">Company Records</div>

        <div class="sp-journal__grid">

            <!-- ─── Sidebar (Dark Info Card) ─── -->
            <aside class="sp-journal__sidebar reveal">
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Classification</div>
                    <div class="sp-journal__sidebar-value">Standard</div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Employee ID</div>
                    <div class="sp-journal__sidebar-value">2237</div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">AI Cores</div>
                    <div class="sp-journal__sidebar-value">1 <span style="opacity: 0.4">(unruly)</span></div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Ages Documented</div>
                    <div class="sp-journal__sidebar-value"><span class="sp-journal__redacted">████</span></div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Location</div>
                    <div class="sp-journal__sidebar-value">39.9° N, 82.8° W</div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Last Audit</div>
                    <div class="sp-journal__sidebar-value"><span class="sp-journal__redacted">██████████</span></div>
                </div>
                <div class="sp-journal__sidebar-row">
                    <div class="sp-journal__sidebar-label">Status</div>
                    <div class="sp-journal__sidebar-value" style="color: var(--secondary);">Active</div>
                </div>
            </aside>

            <!-- ─── Journal Entries ─── -->
            <div class="sp-journal__main">

                <!-- Age 1 -->
                <div class="sp-journal__entry reveal">
                    <h3 class="sp-journal__entry-title">Age 1: The Foundation</h3>
                    <div class="sp-journal__entry-text">
                        <p>Started building websites because the ones I saw businesses using were embarrassing. Not bad-taste embarrassing - actively-losing-money embarrassing. Slow, confusing, built by someone who'd never talked to the actual customers.</p>
                        <p>The first few were favors. Then favors turned into referrals. Then referrals turned into a business. Mosaic Life Creative didn't start with a business plan. It started with "I can build you something better than that<sup>[4]</sup>."</p>
                    </div>
                    <div class="sp-journal__margin-note sp-journal__margin-note--trey" style="top: 20px;">
                        42 seconds. That's all you get.
                    </div>
                </div>

                <!-- Age 2 -->
                <div class="sp-journal__entry reveal">
                    <h3 class="sp-journal__entry-title">Age 2: The Pivot</h3>
                    <div class="sp-journal__entry-text">
                        <p>Somewhere around the fifteenth website, I noticed the pattern. Every client had the same problem: their phone rang with the same questions, day after day. "Do you service my area?" "What are your hours?" "Can I get a quote?"</p>
                        <p>So I started building things that answer back. AI chat agents that know the business, know the services, know when to escalate to a human. Not chatbots - agents. The distinction matters<sup>[8]</sup>.</p>
                    </div>
                    <div class="sp-journal__wheatley-note" style="top: 40px;">
                        I've been told not to mention this part. Something about "trade secrets." Bit dramatic, honestly.
                    </div>
                </div>

                <!-- Age 3 -->
                <div class="sp-journal__entry reveal">
                    <h3 class="sp-journal__entry-title">Age 3: The Art</h3>
                    <div class="sp-journal__entry-text">
                        <p>The Art requires precision. One wrong symbol and the Age collapses. That's not metaphor - that's what happens when you push bad code to production on a Friday afternoon. Ask me how I know.</p>
                        <p>Writing creates reality. A few hundred lines of PHP, some carefully structured CSS, an API call that knows when to listen - and suddenly a business has a presence that works while they sleep<sup>[15]</sup>. The linking window works. Both directions.</p>
                        <p>This Age is still being written. Every client, every project, every line adds to it. The documentation continues.</p>
                    </div>
                    <div class="sp-journal__margin-note sp-journal__margin-note--alt sp-journal__margin-note--trey" style="top: 80px;">
                        3:16 PM - the window opens.
                    </div>
                </div>

                <!-- Age 4 -->
                <div class="sp-journal__entry reveal">
                    <h3 class="sp-journal__entry-title">Age 4: The Community</h3>
                    <div class="sp-journal__entry-text">
                        <p>Grove City Chamber of Commerce. Marketing committee chair. AmSpirit networking group - claimed the "AI" category before anyone else thought to<sup>[16]</sup>. Not because I wanted the title. Because someone was going to take it, and I'd rather it be someone who actually builds the things.</p>
                        <p>The goal isn't fifty clients. It's fifty clients who understand what they have. Ownership, not rental. Assets, not subscriptions<sup>[23]</sup>.</p>
                    </div>
                    <div class="sp-journal__wheatley-note" style="top: 10px;">
                        He genuinely believes he's going to be "THE AI person in Columbus." I find this endearing. Also slightly concerning.
                    </div>
                </div>

            </div><!-- /main -->

        </div><!-- /grid -->

        <!-- ─── Footnotes ─── -->
        <div class="sp-journal__footnotes reveal" data-wheatley-page="about" data-wheatley-context="About page journal/records section. You are disguised as footnote [42] in a list of cryptic footnotes. Do NOT sell anything. Be conspiratorial about the founder. You know things about him that you probably shouldn't say. Hint at secrets, patterns, things hidden in the site. Be cryptic, brief, and slightly unsettling. Like a co-conspirator whispering in the margins.">
            <div class="sp-journal__footnote" id="fn-4">
                <span class="sp-journal__footnote-num">[4]</span> Start over.
            </div>
            <div class="sp-journal__footnote" id="fn-8">
                <span class="sp-journal__footnote-num">[8]</span> A chatbot follows a script. An agent understands context. One replaces a FAQ page; the other replaces a phone call.
            </div>
            <div class="sp-journal__footnote" id="fn-15">
                <span class="sp-journal__footnote-num">[15]</span> See <a href="#fn-23">[23]</a>.
            </div>
            <div class="sp-journal__footnote" id="fn-16">
                <span class="sp-journal__footnote-num">[16]</span> First-mover advantage is real when nobody else is moving.
            </div>
            <div class="sp-journal__footnote" id="fn-23">
                <span class="sp-journal__footnote-num">[23]</span> See <a href="#fn-42">[42]</a>.
            </div>
            <div class="sp-journal__footnote" id="fn-42">
                <span class="sp-journal__footnote-num">[42]</span> <span id="wheatleyPageMessage">Have you tried the number?</span>
            </div>
        </div>

    </section>

    <!-- ═══ CTA ════════════════════════════════════════ -->
    <section class="sp-cta">
        <h2 class="sp-cta__title reveal">Let's Figure Out<br>What You Need</h2>
        <p class="sp-cta__subtitle reveal" style="--delay: 0.1s">No pitch decks, no discovery phases, no "synergy." Just a conversation about your business.</p>
        <a href="/contact" class="sp-cta__button reveal" style="--delay: 0.2s">Get in Touch</a>
    </section>

    <?php wp_footer(); ?>

</body>
</html>
