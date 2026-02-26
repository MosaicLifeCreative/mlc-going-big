<?php
/**
 * MLC Admin — registers admin menus, handles saves, enqueues admin assets
 */

if (!defined('ABSPATH')) exit;

class MLC_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_menus']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_init', [$this, 'handle_save']);
    }

    /**
     * Register admin menu pages
     */
    public function register_menus() {
        add_menu_page(
            'MLC Toolkit',
            'MLC Toolkit',
            'manage_options',
            'mlc-toolkit',
            [$this, 'render_photos_page'],
            'dashicons-layout',
            80
        );

        add_submenu_page(
            'mlc-toolkit',
            'Slideshow Photos',
            'Slideshow Photos',
            'manage_options',
            'mlc-toolkit',
            [$this, 'render_photos_page']
        );

        add_submenu_page(
            'mlc-toolkit',
            'Prompt Links',
            'Prompt Links',
            'manage_options',
            'mlc-prompt-links',
            [$this, 'render_prompt_links_page']
        );

        add_submenu_page(
            'mlc-toolkit',
            'Share Analytics',
            'Share Analytics',
            'manage_options',
            'mlc-share-analytics',
            [$this, 'render_share_page']
        );
    }

    /**
     * Enqueue admin assets on our pages only
     */
    public function enqueue_assets($hook) {
        $our_pages = [
            'toplevel_page_mlc-toolkit',
            'mlc-toolkit_page_mlc-share-analytics',
            'mlc-toolkit_page_mlc-prompt-links',
        ];
        if (!in_array($hook, $our_pages)) return;

        wp_enqueue_style(
            'mlc-admin-css',
            MLC_TOOLKIT_URL . 'admin/css/admin.css',
            [],
            MLC_TOOLKIT_VERSION
        );

        // Photos page needs media uploader + sortable
        if ($hook === 'toplevel_page_mlc-toolkit') {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script(
                'mlc-admin-photos',
                MLC_TOOLKIT_URL . 'admin/js/admin-photos.js',
                ['jquery', 'jquery-ui-sortable'],
                MLC_TOOLKIT_VERSION,
                true
            );
        }
    }

    /**
     * Handle form saves
     */
    public function handle_save() {
        // Photo save
        if (isset($_POST['mlc_photos_nonce'])) {
            $this->handle_photos_save();
        }

        // Prompt link create
        if (isset($_POST['mlc_prompt_nonce'])) {
            $this->handle_prompt_create();
        }
    }

    /**
     * Handle photo save (form POST)
     */
    private function handle_photos_save() {
        if (!wp_verify_nonce($_POST['mlc_photos_nonce'], 'mlc_save_photos')) return;
        if (!current_user_can('manage_options')) return;

        $photos = [];

        if (isset($_POST['photos']) && is_array($_POST['photos'])) {
            foreach ($_POST['photos'] as $photo) {
                $photos[] = [
                    'attachment_id' => absint($photo['attachment_id'] ?? 0),
                    'url'           => esc_url_raw($photo['url'] ?? ''),
                    'caption'       => sanitize_text_field($photo['caption'] ?? ''),
                    'credit'        => sanitize_text_field($photo['credit'] ?? ''),
                ];
            }
        }

        MLC_Photos::save($photos);

        wp_redirect(admin_url('admin.php?page=mlc-toolkit&saved=1'));
        exit;
    }

    /**
     * Handle prompt link creation (form POST)
     */
    private function handle_prompt_create() {
        if (!wp_verify_nonce($_POST['mlc_prompt_nonce'], 'mlc_prompt_create')) return;
        if (!current_user_can('manage_options')) return;

        $name    = sanitize_text_field($_POST['prompt_name'] ?? '');
        $context = sanitize_textarea_field($_POST['prompt_context'] ?? '');

        if (empty($name)) {
            wp_redirect(admin_url('admin.php?page=mlc-prompt-links&error=name'));
            exit;
        }

        $result = MLC_Share::create_link($name, $context, 'admin');

        wp_redirect(admin_url('admin.php?page=mlc-prompt-links&created=' . $result['code']));
        exit;
    }

    /**
     * Render the photos admin page
     */
    public function render_photos_page() {
        include MLC_TOOLKIT_PATH . 'admin/views/photos.php';
    }

    /**
     * Render the prompt links admin page
     */
    public function render_prompt_links_page() {
        include MLC_TOOLKIT_PATH . 'admin/views/prompt-links.php';
    }

    /**
     * Render the share analytics page
     */
    public function render_share_page() {
        include MLC_TOOLKIT_PATH . 'admin/views/share-analytics.php';
    }
}

new MLC_Admin();
