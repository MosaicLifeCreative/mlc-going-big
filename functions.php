<?php
/**
 * Divi Child Theme Functions
 * 
 * Enqueues parent and child theme styles and custom scripts.
 */

function divi_child_enqueue_styles() {
    
    // Enqueue parent Divi theme styles
    wp_enqueue_style( 'divi-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Enqueue child theme styles
    wp_enqueue_style( 
        'divi-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'divi-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
    
    // Enqueue custom scripts (jQuery already loaded by Divi)
    wp_enqueue_script( 
        'divi-child-scripts',
        get_stylesheet_directory_uri() . '/js/scripts.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'divi_child_enqueue_styles' );

// Add theme support for features as needed
function divi_child_setup() {
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'divi_child_setup' );

/**
 * Enqueue Landing Page Assets (CSS and JS)
 * Only loads on pages using the MLC Landing Page template
 */
function mlc_enqueue_landing_assets() {
    // Only load on the landing page template
    if (is_page_template('page-landing.php')) {
        // Enqueue CSS
        wp_enqueue_style(
            'mlc-landing-css',
            get_stylesheet_directory_uri() . '/assets/css/landing.css',
            array(),
            '1.2.0',
            'all'
        );
        
        // Enqueue JS
        wp_enqueue_script(
            'mlc-landing-js',
            get_stylesheet_directory_uri() . '/assets/js/landing.js',
            array(),
            '1.2.0',
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'mlc_enqueue_landing_assets');

/**
 * AJAX Handler for Hunt Sequence Validation
 * Keeps the target sequence server-side only
 */
function mlc_validate_hunt_sequence() {
    // Verify nonce for security
    check_ajax_referer('mlc_hunt_nonce', 'nonce');
    
    // Get the submitted guess
    $guess = isset($_POST['sequence']) ? sanitize_text_field($_POST['sequence']) : '';
    
    // Target sequence (server-side only - never exposed to client)
    $target = '4815162342';
    
    // Validate
    $is_correct = ($guess === $target);
    
    // Return JSON response
    wp_send_json_success(array(
        'correct' => $is_correct,
        'redirect' => $is_correct ? 'https://4815162342.quest' : ''
    ));
}
add_action('wp_ajax_mlc_validate_hunt', 'mlc_validate_hunt_sequence');
add_action('wp_ajax_nopriv_mlc_validate_hunt', 'mlc_validate_hunt_sequence');

/**
 * Add nonce for hunt validation
 */
function mlc_add_hunt_nonce() {
    if (is_page_template('page-landing.php')) {
        ?>
        <script type="text/javascript">
            var mlcHunt = {
                ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
                nonce: '<?php echo wp_create_nonce('mlc_hunt_nonce'); ?>'
            };
        </script>
        <?php
    }
}
add_action('wp_head', 'mlc_add_hunt_nonce');
?>