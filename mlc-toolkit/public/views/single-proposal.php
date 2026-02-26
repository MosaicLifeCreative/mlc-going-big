<?php
/**
 * Frontend Proposal Template
 * Standalone page with PIN gate, proposal content, Wheatley commentary, accept button.
 * Uses client accent color via CSS variable --proposal-accent.
 * Site footer is injected via wp_footer hook (same as all pages).
 */
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$meta    = MLC_Proposal::get_meta($post_id);
$totals  = MLC_Proposal::calculate_totals($post_id);
$expired = MLC_Proposal::is_expired($post_id);
$accent  = $meta['accent_color'] ?: '#7C3AED';
$logo_url = $meta['client_logo'] ? wp_get_attachment_image_url($meta['client_logo'], 'medium') : '';
$expiry  = MLC_Proposal::get_expiry_date($post_id);

// Calculate button text color based on accent luminance
$hex = ltrim($accent, '#');
$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));
$luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
$accent_text = $luminance > 0.55 ? '#1d2327' : '#ffffff';
// For prices (accent as text on white bg), darken if too light to read
$accent_price = $accent;
if ($luminance > 0.55) {
    // Darken the accent by 40% for readable text on white
    $accent_price = sprintf('#%02x%02x%02x', max(0, $r * 0.6), max(0, $g * 0.6), max(0, $b * 0.6));
}

// Build service list for display
$services     = $meta['services'];
$custom_items = $meta['custom_items'];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html(get_the_title()); ?> | Mosaic Life Creative</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --proposal-accent: <?php echo esc_attr($accent); ?>;
            --proposal-accent-text: <?php echo esc_attr($accent_text); ?>;
            --proposal-accent-price: <?php echo esc_attr($accent_price); ?>;
            --primary: #7C3AED;
            --secondary: #06B6D4;
            --accent: #EC4899;
            --dark: #0A0A0A;
            --light: #F8F8F8;
        }
    </style>
    <?php wp_head(); ?>
</head>
<body class="mlc-proposal-page" data-proposal-id="<?php echo esc_attr($post_id); ?>" data-status="<?php echo esc_attr($meta['status']); ?>">

    <!-- PIN GATE OVERLAY -->
    <div id="proposal-gate" class="proposal-gate" <?php echo ($meta['status'] === 'accepted') ? 'style="display:none;"' : ''; ?>>
        <!-- Gradient blobs -->
        <div class="proposal-gate__blob proposal-gate__blob--1"></div>
        <div class="proposal-gate__blob proposal-gate__blob--2"></div>
        <div class="proposal-gate__blob proposal-gate__blob--3"></div>

        <div class="proposal-gate__card">
            <?php if ($logo_url): ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="" class="proposal-gate__logo" />
            <?php endif; ?>
            <h1 class="proposal-gate__title">Your Proposal is Ready</h1>
            <?php if ($meta['client_company']): ?>
                <p class="proposal-gate__subtitle">Prepared for <?php echo esc_html($meta['client_company']); ?></p>
            <?php endif; ?>

            <?php if ($expired): ?>
                <div class="proposal-gate__expired">
                    <p>This proposal expired on <?php echo esc_html($expiry); ?>.</p>
                    <p>Please contact us for an updated proposal.</p>
                </div>
            <?php else: ?>
                <div class="proposal-gate__form">
                    <label for="proposal-pin">Enter your PIN to view</label>
                    <input type="text" id="proposal-pin" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="off" placeholder="------" />
                    <button type="button" id="proposal-pin-submit" class="proposal-btn proposal-btn--accent proposal-btn--full">View Proposal</button>
                    <p class="proposal-gate__error" id="proposal-pin-error" style="display:none;"></p>
                </div>
            <?php endif; ?>

            <p class="proposal-gate__from">From <strong>Mosaic Life Creative</strong></p>
        </div>
    </div>

    <!-- PROPOSAL CONTENT (hidden until PIN validated) -->
    <div id="proposal-content" class="proposal-content proposal-content--hidden">

        <!-- Header -->
        <header class="proposal-header">
            <div class="proposal-header__inner">
                <div class="proposal-header__brand">
                    <?php if ($logo_url): ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="" class="proposal-header__client-logo" />
                    <?php endif; ?>
                    <div>
                        <h1 class="proposal-header__title"><?php echo esc_html(get_the_title()); ?></h1>
                        <?php if ($meta['client_company']): ?>
                            <p class="proposal-header__subtitle">Prepared for <?php echo esc_html($meta['client_company']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="proposal-header__meta">
                    <?php if ($meta['client_name']): ?>
                        <span><?php echo esc_html($meta['client_name']); ?></span>
                    <?php endif; ?>
                    <span>Valid through <?php echo esc_html($expiry); ?></span>
                </div>
            </div>
        </header>

        <!-- Wheatley intro (inline blockquote) -->
        <div class="proposal-wheatley" id="wheatley-intro">
            <div class="proposal-wheatley__inner">
                <span class="proposal-wheatley__label">Wheatley, MLC Personality Core</span>
                <p class="proposal-wheatley__text" id="wheatley-intro-text"></p>
            </div>
        </div>

        <!-- Services -->
        <?php if (!empty($services)): ?>
        <section class="proposal-section">
            <div class="proposal-section__inner">
                <h2 class="proposal-section__title">What We're Building</h2>

                <?php foreach ($services as $slug => $svc): ?>
                    <div class="proposal-service">
                        <div class="proposal-service__header">
                            <h3 class="proposal-service__name"><?php echo esc_html($svc['name']); ?></h3>
                            <div class="proposal-service__price">
                                <?php if ($svc['price']): ?>
                                    <strong>$<?php echo esc_html(number_format((float) preg_replace('/[^0-9.]/', '', $svc['price']))); ?></strong>
                                    <span><?php
                                        $pt = $svc['price_type'] ?? 'monthly';
                                        if ($pt === 'monthly') echo '/month';
                                        elseif ($pt === 'annual') echo '/year';
                                        else echo 'one-time';
                                    ?></span>
                                <?php endif; ?>
                                <?php
                                    $setup = (float) preg_replace('/[^0-9.]/', '', $svc['setup_price'] ?? '0');
                                    if ($setup > 0):
                                ?>
                                    <span class="proposal-service__setup">+ $<?php echo esc_html(number_format($setup)); ?> setup</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($svc['description']): ?>
                            <div class="proposal-service__description">
                                <?php echo wpautop(esc_html($svc['description'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Custom Items -->
        <?php if (!empty($custom_items)): ?>
        <section class="proposal-section proposal-section--alt">
            <div class="proposal-section__inner">
                <h2 class="proposal-section__title">Project Specifics</h2>

                <?php foreach ($custom_items as $item): ?>
                    <div class="proposal-service">
                        <div class="proposal-service__header">
                            <h3 class="proposal-service__name"><?php echo esc_html($item['name']); ?></h3>
                            <div class="proposal-service__price">
                                <?php if ($item['price']): ?>
                                    <strong>$<?php echo esc_html(number_format((float) preg_replace('/[^0-9.]/', '', $item['price']))); ?></strong>
                                    <span><?php
                                        $pt = $item['price_type'] ?? 'one-time';
                                        if ($pt === 'monthly') echo '/month';
                                        elseif ($pt === 'annual') echo '/year';
                                        else echo 'one-time';
                                    ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($item['description']): ?>
                            <div class="proposal-service__description">
                                <?php echo wpautop(esc_html($item['description'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Investment Summary -->
        <section class="proposal-summary" id="wheatley-total">
            <div class="proposal-summary__inner">
                <h2 class="proposal-summary__title">Investment Summary</h2>

                <!-- Wheatley pre-total (inside summary) -->
                <div class="proposal-wheatley proposal-wheatley--embedded">
                    <div class="proposal-wheatley__inner">
                        <span class="proposal-wheatley__label">Wheatley, MLC Personality Core</span>
                        <p class="proposal-wheatley__text" id="wheatley-total-text"></p>
                    </div>
                </div>

                <?php if ($totals['setup'] > 0): ?>
                <div class="proposal-summary__row">
                    <span>Setup</span>
                    <strong>$<?php echo esc_html(number_format($totals['setup'], 2)); ?></strong>
                </div>
                <?php endif; ?>

                <?php if ($totals['one_time'] > 0): ?>
                <div class="proposal-summary__row">
                    <span>One-time</span>
                    <strong>$<?php echo esc_html(number_format($totals['one_time'], 2)); ?></strong>
                </div>
                <?php endif; ?>

                <?php if ($totals['monthly'] > 0): ?>
                <div class="proposal-summary__row">
                    <span>Monthly</span>
                    <strong>$<?php echo esc_html(number_format($totals['monthly'], 2)); ?>/mo</strong>
                </div>
                <?php endif; ?>

                <?php if ($totals['annual'] > 0): ?>
                <div class="proposal-summary__row">
                    <span>Annual</span>
                    <strong>$<?php echo esc_html(number_format($totals['annual'], 2)); ?>/yr</strong>
                </div>
                <?php endif; ?>

                <div class="proposal-summary__valid">
                    This proposal is valid through <?php echo esc_html($expiry); ?>.
                </div>

                <!-- Accept (inside summary) -->
                <?php if ($meta['status'] !== 'accepted'): ?>
                <div class="proposal-summary__accept" id="proposal-accept-section">
                    <button type="button" id="proposal-accept-btn" class="proposal-btn proposal-btn--accent proposal-btn--large">
                        Accept Proposal
                    </button>
                    <p class="proposal-accept__note">By accepting, you agree to proceed with the services outlined above.</p>
                </div>
                <?php else: ?>
                <div class="proposal-summary__accepted">
                    <div class="proposal-accepted__badge">Accepted</div>
                    <p>You accepted this proposal on <?php echo esc_html(date('F j, Y', strtotime($meta['accepted_at']))); ?>.</p>
                </div>
                <?php endif; ?>

                <!-- Wheatley post-accept (shown after accept click) -->
                <div class="proposal-wheatley proposal-wheatley--embedded" id="wheatley-accept" style="display:none;">
                    <div class="proposal-wheatley__inner">
                        <p class="proposal-wheatley__text" id="wheatley-accept-text"></p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php wp_footer(); ?>
</body>
</html>
