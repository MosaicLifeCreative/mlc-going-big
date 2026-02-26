<?php
/**
 * Plugin Name: MLC Toolkit
 * Plugin URI: https://mosaiclifecreative.com
 * Description: Mosaic Life Creative toolkit — slideshow photos, share analytics, URL shortener, client proposals, and dashboard widget.
 * Version: 1.2.2
 * Author: Trey Kauffman
 * Author URI: https://mosaiclifecreative.com
 * Text Domain: mlc-toolkit
 */

if (!defined('ABSPATH')) exit;

define('MLC_TOOLKIT_VERSION', '1.2.2');
define('MLC_TOOLKIT_PATH', plugin_dir_path(__FILE__));
define('MLC_TOOLKIT_URL', plugin_dir_url(__FILE__));

// Includes
require_once MLC_TOOLKIT_PATH . 'includes/class-mlc-photos.php';
require_once MLC_TOOLKIT_PATH . 'includes/class-mlc-share.php';
require_once MLC_TOOLKIT_PATH . 'includes/class-mlc-proposal.php';

MLC_Proposal::init();

if (is_admin()) {
    require_once MLC_TOOLKIT_PATH . 'admin/class-mlc-admin.php';
}

/**
 * Plugin activation — create database tables
 */
function mlc_toolkit_activate() {
    MLC_Share::create_tables();
    MLC_Photos::seed_defaults();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mlc_toolkit_activate');

/**
 * Database migration — runs once on version change
 */
function mlc_toolkit_maybe_upgrade() {
    $installed = get_option('mlc_toolkit_db_version', '1.0.0');
    if (version_compare($installed, '1.1.0', '<')) {
        MLC_Share::migrate_v2();
        update_option('mlc_toolkit_db_version', '1.1.0');
    }
}
add_action('admin_init', 'mlc_toolkit_maybe_upgrade');

/**
 * Plugin deactivation
 */
function mlc_toolkit_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'mlc_toolkit_deactivate');

/**
 * Handle /s/{code} share URLs.
 * Checks REQUEST_URI directly — no dependency on WP rewrite rules.
 * Fires early on 'init' (priority 1) before caching or template loading.
 *
 * Cache-busting headers prevent SiteGround's Dynamic Cache (Nginx layer)
 * from caching these responses and preventing PHP from executing on
 * subsequent requests.
 */
function mlc_toolkit_handle_share_url() {
    $raw_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Account for WordPress in a subdirectory (e.g. /fresh/s/code)
    $home_path = trim(parse_url(home_url(), PHP_URL_PATH) ?: '', '/');
    $path = $raw_path;
    if ($home_path && strpos($path, $home_path . '/') === 0) {
        $path = substr($path, strlen($home_path) + 1);
    }

    if (!preg_match('#^s/([a-zA-Z0-9]+)$#', $path, $matches)) return;

    // Prevent SiteGround/Nginx from caching this response
    nocache_headers();
    header('X-Cache-Enabled: False');
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }

    $code  = $matches[1];
    $share = MLC_Share::get_by_code($code);

    if (!$share) {
        error_log('[MLC Share] Code not found in DB: ' . $code);
        wp_redirect(home_url('/'), 302);
        exit;
    }

    // Social crawlers get a server-side redirect to the homepage so they
    // scrape the real OG tags from Slim SEO. No click recorded for bots.
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    if (preg_match('/facebookexternalhit|Facebot|Twitterbot|LinkedInBot|WhatsApp|Slackbot|Discordbot|TelegramBot/i', $ua)) {
        wp_redirect(home_url('/'), 302);
        exit;
    }

    // Record the click (real visitors only)
    MLC_Share::record_click($share->id, [
        'referrer'   => isset($_SERVER['HTTP_REFERER']) ? sanitize_url($_SERVER['HTTP_REFERER']) : '',
        'user_agent' => sanitize_text_field($ua),
    ]);

    error_log('[MLC Share] Click recorded for code: ' . $code . ' (link_id: ' . $share->id . ')');

    // Render a tiny bridge page that stores the personalization in
    // sessionStorage then redirects client-side.
    $encoded = esc_attr($share->url_encoded);
    $home    = esc_url(home_url('/'));
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>Redirecting...</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    </head>
    <body>
    <script>
    try { sessionStorage.setItem('mlc_share','<?php echo $encoded; ?>'); } catch(e) {}
    window.location.replace('<?php echo $home; ?>');
    </script>
    <noscript><meta http-equiv="refresh" content="0;url=<?php echo $home; ?>"></noscript>
    </body></html>
    <?php
    exit;
}
add_action('init', 'mlc_toolkit_handle_share_url', 1);

/**
 * REST endpoint for creating share links (called from frontend JS)
 */
add_action('rest_api_init', function () {
    register_rest_route('mlc/v1', '/share', [
        'methods'             => 'POST',
        'callback'            => 'mlc_toolkit_create_share',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('mlc/v1', '/proposal/validate-pin', [
        'methods'             => 'POST',
        'callback'            => 'mlc_toolkit_validate_proposal_pin',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('mlc/v1', '/proposal/accept', [
        'methods'             => 'POST',
        'callback'            => 'mlc_toolkit_accept_proposal',
        'permission_callback' => '__return_true',
    ]);
});

function mlc_toolkit_create_share($request) {
    $params = $request->get_json_params();

    $name    = isset($params['name']) ? sanitize_text_field($params['name']) : '';
    $context = isset($params['context']) ? sanitize_text_field($params['context']) : '';

    if (empty($name)) {
        return new WP_Error('missing_name', 'Name is required', ['status' => 400]);
    }

    $result = MLC_Share::create_link($name, $context);

    return [
        'success'   => true,
        'short_url' => $result['short_url'],
        'full_url'  => $result['full_url'],
        'code'      => $result['code'],
    ];
}

/**
 * REST: Validate proposal PIN
 */
function mlc_toolkit_validate_proposal_pin($request) {
    $params  = $request->get_json_params();
    $post_id = absint($params['proposal_id'] ?? 0);
    $pin     = sanitize_text_field($params['pin'] ?? '');

    if (!$post_id || !$pin) {
        return new WP_Error('invalid', 'Missing proposal ID or PIN', ['status' => 400]);
    }

    if (MLC_Proposal::is_expired($post_id)) {
        return ['success' => false, 'error' => 'expired'];
    }

    if (!MLC_Proposal::validate_pin($post_id, $pin)) {
        return ['success' => false, 'error' => 'invalid_pin'];
    }

    // Mark as viewed on first successful PIN entry
    MLC_Proposal::mark_viewed($post_id);

    return ['success' => true];
}

/**
 * REST: Accept proposal
 */
function mlc_toolkit_accept_proposal($request) {
    $params  = $request->get_json_params();
    $post_id = absint($params['proposal_id'] ?? 0);
    $pin     = sanitize_text_field($params['pin'] ?? '');

    if (!$post_id || !$pin) {
        return new WP_Error('invalid', 'Missing proposal ID or PIN', ['status' => 400]);
    }

    // Re-validate PIN for security
    if (!MLC_Proposal::validate_pin($post_id, $pin)) {
        return new WP_Error('unauthorized', 'Invalid PIN', ['status' => 403]);
    }

    if (MLC_Proposal::is_expired($post_id)) {
        return ['success' => false, 'error' => 'expired'];
    }

    MLC_Proposal::mark_accepted($post_id);

    return ['success' => true];
}

/**
 * Enqueue proposal frontend assets on proposal single pages
 */
function mlc_toolkit_proposal_assets() {
    if (!is_singular(MLC_Proposal::POST_TYPE)) return;

    wp_enqueue_style(
        'mlc-proposal-css',
        MLC_TOOLKIT_URL . 'public/css/proposal.css',
        [],
        MLC_TOOLKIT_VERSION
    );

    wp_enqueue_script(
        'mlc-proposal-js',
        MLC_TOOLKIT_URL . 'public/js/proposal.js',
        [],
        MLC_TOOLKIT_VERSION,
        true
    );

    $post_id = get_the_ID();
    $meta    = MLC_Proposal::get_meta($post_id);
    $totals  = MLC_Proposal::calculate_totals($post_id);

    wp_localize_script('mlc-proposal-js', 'mlcProposal', [
        'ajaxUrl'      => rest_url('mlc/v1/'),
        'proposalId'   => $post_id,
        'clientName'   => $meta['client_name'],
        'status'       => $meta['status'],
        'isExpired'    => MLC_Proposal::is_expired($post_id),
        'wheatleyUrl'  => rest_url('mlc/v1/wheatley-proposal'),
        'services'     => array_keys($meta['services']),
        'totalOnce'    => $totals['one_time'],
        'totalMonthly' => $totals['monthly'],
        'totalAnnual'  => $totals['annual'],
        'totalSetup'   => $totals['setup'],
    ]);
}
add_action('wp_enqueue_scripts', 'mlc_toolkit_proposal_assets');

/**
 * Legacy domain redirect for share URLs.
 * When migrating from fresh.mosaiclifecreative.com to mosaiclifecreative.com,
 * define MLC_OLD_DOMAINS in wp-config.php as a comma-separated string:
 *   define('MLC_OLD_DOMAINS', 'fresh.mosaiclifecreative.com');
 *
 * Any request to the old domain will be redirected to the new domain,
 * preserving the full path (so /s/{code} links keep working).
 */
function mlc_toolkit_legacy_domain_redirect() {
    if (!defined('MLC_OLD_DOMAINS')) return;

    $old_domains = array_map('trim', explode(',', MLC_OLD_DOMAINS));
    $current_host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';

    if (in_array($current_host, $old_domains, true)) {
        $new_url = home_url($_SERVER['REQUEST_URI']);
        nocache_headers();
        wp_redirect($new_url, 301);
        exit;
    }
}
add_action('init', 'mlc_toolkit_legacy_domain_redirect', 0);

/**
 * Localize photo data for global.js
 */
function mlc_toolkit_localize_photos() {
    $photos = MLC_Photos::get_frontend_data();

    wp_localize_script('mlc-global-js', 'mlcPhotos', [
        'photos' => $photos,
    ]);
}
add_action('wp_enqueue_scripts', 'mlc_toolkit_localize_photos', 20);

/**
 * Enqueue mobile slideshow assets on frontend
 */
function mlc_toolkit_frontend_assets() {
    wp_enqueue_style(
        'mlc-toolkit-public',
        MLC_TOOLKIT_URL . 'public/css/mobile-slideshow.css',
        [],
        MLC_TOOLKIT_VERSION
    );

    wp_enqueue_script(
        'mlc-toolkit-public',
        MLC_TOOLKIT_URL . 'public/js/mobile-slideshow.js',
        ['mlc-global-js'],
        MLC_TOOLKIT_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'mlc_toolkit_frontend_assets');

/**
 * Dashboard widget — Share Link metrics at a glance
 */
function mlc_toolkit_dashboard_widget() {
    wp_add_dashboard_widget(
        'mlc_share_metrics',
        'Share Link Metrics',
        'mlc_toolkit_dashboard_widget_render'
    );
}
add_action('wp_dashboard_setup', 'mlc_toolkit_dashboard_widget');

function mlc_toolkit_dashboard_widget_render() {
    $stats = MLC_Share::get_stats();
    $recent = MLC_Share::get_recent_activity(5);

    ?>
    <style>
        .mlc-dash-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
        .mlc-dash-stat { text-align: center; padding: 12px 8px; background: #f6f7f7; border-radius: 8px; }
        .mlc-dash-stat__value { font-size: 28px; font-weight: 700; color: #1d2327; line-height: 1.2; }
        .mlc-dash-stat__label { font-size: 11px; color: #646970; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .mlc-dash-divider { border: none; border-top: 1px solid #f0f0f1; margin: 12px 0; }
        .mlc-dash-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #f0f0f1; }
        .mlc-dash-row:last-child { border-bottom: none; }
        .mlc-dash-row__event { color: #1d2327; }
        .mlc-dash-row__time { color: #646970; }
        .mlc-dash-badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 11px; font-weight: 600; }
        .mlc-dash-badge--created { background: #e7f5ee; color: #00a32a; }
        .mlc-dash-badge--clicked { background: #e8f0fe; color: #2271b1; }
        .mlc-dash-footer { margin-top: 12px; text-align: right; }
    </style>
    <div class="mlc-dash-grid">
        <div class="mlc-dash-stat">
            <div class="mlc-dash-stat__value"><?php echo esc_html($stats['total_links']); ?></div>
            <div class="mlc-dash-stat__label">Links</div>
        </div>
        <div class="mlc-dash-stat">
            <div class="mlc-dash-stat__value"><?php echo esc_html($stats['total_clicks']); ?></div>
            <div class="mlc-dash-stat__label">Clicks</div>
        </div>
        <div class="mlc-dash-stat">
            <div class="mlc-dash-stat__value"><?php echo esc_html($stats['conversion_rate']); ?>%</div>
            <div class="mlc-dash-stat__label">Click Rate</div>
        </div>
    </div>
    <div class="mlc-dash-grid" style="grid-template-columns: repeat(2, 1fr);">
        <div class="mlc-dash-stat">
            <div class="mlc-dash-stat__value"><?php echo esc_html($stats['clicks_today']); ?></div>
            <div class="mlc-dash-stat__label">Clicks Today</div>
        </div>
        <div class="mlc-dash-stat">
            <div class="mlc-dash-stat__value"><?php echo esc_html($stats['clicks_week']); ?></div>
            <div class="mlc-dash-stat__label">Clicks This Week</div>
        </div>
    </div>
    <?php if (!empty($recent)): ?>
        <hr class="mlc-dash-divider">
        <strong style="font-size: 12px; text-transform: uppercase; color: #646970; letter-spacing: 0.5px;">Recent Activity</strong>
        <div style="margin-top: 8px;">
            <?php foreach ($recent as $event): ?>
                <div class="mlc-dash-row">
                    <span class="mlc-dash-row__event">
                        <span class="mlc-dash-badge mlc-dash-badge--<?php echo esc_attr($event->event_type); ?>">
                            <?php echo $event->event_type === 'created' ? 'New' : 'Click'; ?>
                        </span>
                        <?php echo esc_html($event->name_display); ?>
                        <?php if ($event->context): ?>
                            <span style="color: #646970;">— <?php echo esc_html($event->context); ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="mlc-dash-row__time"><?php echo esc_html(human_time_diff(strtotime($event->event_time), current_time('timestamp')) . ' ago'); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mlc-dash-footer">
        <a href="<?php echo admin_url('admin.php?page=mlc-share-analytics'); ?>">View Full Analytics &rarr;</a>
    </div>
    <?php
}
