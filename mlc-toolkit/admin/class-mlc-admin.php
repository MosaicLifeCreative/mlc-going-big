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
            'Proposals',
            'Proposals',
            'manage_options',
            'mlc-proposals',
            [$this, 'render_proposals_page']
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
            'mlc-toolkit_page_mlc-proposals',
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

        // Proposals page needs media uploader + proposals JS
        if ($hook === 'mlc-toolkit_page_mlc-proposals') {
            wp_enqueue_media();
            wp_enqueue_script(
                'mlc-admin-proposals',
                MLC_TOOLKIT_URL . 'admin/js/admin-proposals.js',
                ['jquery'],
                MLC_TOOLKIT_VERSION,
                true
            );
            wp_localize_script('mlc-admin-proposals', 'mlcProposalAdmin', [
                'serviceCatalog' => MLC_Proposal::get_service_catalog(),
            ]);
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

        // Proposal save
        if (isset($_POST['mlc_proposal_nonce'])) {
            $this->handle_proposal_save();
        }

        // Proposal delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete-proposal' && isset($_GET['id'])) {
            $this->handle_proposal_delete();
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
     * Handle proposal create/update (form POST)
     */
    private function handle_proposal_save() {
        if (!wp_verify_nonce($_POST['mlc_proposal_nonce'], 'mlc_save_proposal')) return;
        if (!current_user_can('manage_options')) return;

        $post_id = absint($_POST['proposal_id'] ?? 0);
        $title   = sanitize_text_field($_POST['proposal_title'] ?? '');

        if (empty($title)) {
            wp_redirect(admin_url('admin.php?page=mlc-proposals&action=new&error=title'));
            exit;
        }

        // Determine slug: custom > company name > title
        $custom_slug = sanitize_title($_POST['proposal_slug'] ?? '');
        $company     = sanitize_text_field($_POST['client_company'] ?? '');
        $slug        = $custom_slug ?: ($company ? sanitize_title($company) : sanitize_title($title));

        // Create or update
        if ($post_id) {
            wp_update_post([
                'ID'         => $post_id,
                'post_title' => $title,
                'post_name'  => $slug,
            ]);
        } else {
            $post_id = MLC_Proposal::create($title, $slug);
        }

        if (!$post_id || is_wp_error($post_id)) {
            wp_redirect(admin_url('admin.php?page=mlc-proposals&error=save'));
            exit;
        }

        MLC_Proposal::save_meta($post_id, $_POST);

        wp_redirect(admin_url('admin.php?page=mlc-proposals&action=edit&id=' . $post_id . '&saved=1'));
        exit;
    }

    /**
     * Handle proposal delete
     */
    private function handle_proposal_delete() {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mlc_delete_proposal')) return;
        if (!current_user_can('manage_options')) return;

        $post_id = absint($_GET['id']);
        if ($post_id) {
            wp_delete_post($post_id, true);
        }

        wp_redirect(admin_url('admin.php?page=mlc-proposals&deleted=1'));
        exit;
    }

    /**
     * Render the photos admin page
     */
    public function render_photos_page() {
        include MLC_TOOLKIT_PATH . 'admin/views/photos.php';
    }

    /**
     * Render the proposals admin page (list or edit)
     */
    public function render_proposals_page() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        if ($action === 'new' || $action === 'edit') {
            include MLC_TOOLKIT_PATH . 'admin/views/proposal-edit.php';
        } else {
            include MLC_TOOLKIT_PATH . 'admin/views/proposal-list.php';
        }
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
