<?php
/**
 * Plugin Name: MLC Toolkit
 * Plugin URI: https://mosaiclifecreative.com
 * Description: Mosaic Life Creative toolkit — slideshow photo management, share link analytics, and URL shortener.
 * Version: 1.0.0
 * Author: Trey Kauffman
 * Author URI: https://mosaiclifecreative.com
 * Text Domain: mlc-toolkit
 */

if (!defined('ABSPATH')) exit;

define('MLC_TOOLKIT_VERSION', '1.0.0');
define('MLC_TOOLKIT_PATH', plugin_dir_path(__FILE__));
define('MLC_TOOLKIT_URL', plugin_dir_url(__FILE__));

// Includes
require_once MLC_TOOLKIT_PATH . 'includes/class-mlc-photos.php';
require_once MLC_TOOLKIT_PATH . 'includes/class-mlc-share.php';

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
 */
function mlc_toolkit_handle_share_url() {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    if (!preg_match('#^s/([a-zA-Z0-9]+)$#', $path, $matches)) return;

    $code  = $matches[1];
    $share = MLC_Share::get_by_code($code);

    if (!$share) {
        wp_redirect(home_url('/'), 302);
        exit;
    }

    // Record the click
    MLC_Share::record_click($share->id, [
        'referrer'   => isset($_SERVER['HTTP_REFERER']) ? sanitize_url($_SERVER['HTTP_REFERER']) : '',
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
    ]);

    // Render a tiny bridge page that stores the personalization in
    // sessionStorage then redirects client-side.
    $encoded = esc_attr($share->url_encoded);
    $home    = esc_url(home_url('/'));
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>Redirecting...</title></head>
    <body>
    <script>
    sessionStorage.setItem('mlc_share','<?php echo $encoded; ?>');
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
