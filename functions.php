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
            '1.1.9',
            'all'
        );
        
        // Enqueue JS
        wp_enqueue_script(
            'mlc-landing-js',
            get_stylesheet_directory_uri() . '/assets/js/landing.js',
            array(),
            '1.1.3',
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'mlc_enqueue_landing_assets');
?>