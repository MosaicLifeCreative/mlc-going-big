<?php
/**
 * MLC Proposal — client proposal system
 *
 * Custom post type with meta-driven services, pricing, PIN gate,
 * client branding, status tracking, and Wheatley commentary.
 */

if (!defined('ABSPATH')) exit;

class MLC_Proposal {

    const POST_TYPE = 'mlc_proposal';

    /**
     * Default service catalog.
     * Each service has a slug, name, and rich default description.
     * Descriptions can be customized per proposal.
     */
    public static function get_service_catalog() {
        return [
            'website-design' => [
                'name'        => 'Website Design',
                'description' => "A fully custom WordPress website built around your business. Not a template. Not a drag-and-drop page builder someone else could replicate in an afternoon.\n\nIncludes:\n- Custom page templates designed specifically for your brand\n- Mobile-responsive design tested across all devices\n- Performance optimization (fast load times, clean code)\n- SEO foundation (structured data, meta tags, sitemap)\n- Content migration from your existing site\n- Two rounds of revision after initial build\n- 30 days of post-launch support",
            ],
            'hosting' => [
                'name'        => 'Hosting',
                'description' => "Enterprise-grade hosting infrastructure managed entirely by us. Google Cloud platform, WAF security, server-level caching, CDN delivery, daily backups, and 99.9% uptime guarantee.\n\nIncludes:\n- Google Cloud hosting infrastructure\n- SSL certificate (HTTPS)\n- Daily automated backups with 7-day retention\n- Server-level caching (Memcached + CDN)\n- Web Application Firewall (WAF)\n- DNS management\n- Email deliverability monitoring",
            ],
            'maintenance' => [
                'name'        => 'Maintenance',
                'description' => "Ongoing care for your website so it stays fast, secure, and current. We handle the technical upkeep so you can focus on running your business.\n\nIncludes:\n- WordPress core, theme, and plugin updates\n- Security monitoring and malware scanning\n- Uptime monitoring with instant alerts\n- Performance optimization and speed checks\n- Monthly content updates (text, images, minor layout changes)\n- Monthly performance and security report",
            ],
            'email-marketing' => [
                'name'        => 'Email Marketing',
                'description' => "Email infrastructure you own. No per-subscriber pricing that scales against you. Built on dedicated sending infrastructure with full deliverability control.\n\nIncludes:\n- Dedicated email sending infrastructure (not shared)\n- Campaign design and template creation\n- List management and segmentation\n- Automation sequences (welcome series, follow-ups)\n- Deliverability monitoring and optimization\n- Monthly analytics reporting",
            ],
            'ai-chat-agents' => [
                'name'        => 'AI Chat Agents',
                'description' => "A custom AI assistant trained on your business, deployed on your website, answering questions 24/7. Not a generic chatbot. An agent that knows your services, your hours, your pricing, and your voice.\n\nIncludes:\n- Custom AI agent built and trained on your business\n- Knowledge base creation from your existing content\n- Personality and tone configuration\n- Website widget deployment\n- Monthly training updates and refinement\n- Conversation analytics dashboard",
            ],
            'social-media' => [
                'name'        => 'Social Media Management',
                'description' => "Strategic social media presence management. Content creation, scheduling, community engagement, and performance tracking across your key platforms.\n\nIncludes:\n- Content calendar planning and strategy\n- Post creation (graphics, copy, hashtags)\n- Scheduled publishing across platforms\n- Community monitoring and engagement\n- Monthly performance analytics\n- Platform-specific optimization",
            ],
            'reputation' => [
                'name'        => 'Reputation Management',
                'description' => "Monitor, manage, and grow your online reputation. Review generation, response management, and brand monitoring across the platforms that matter to your business.\n\nIncludes:\n- Review monitoring across Google and Yelp\n- Review response drafting and management\n- Automated review request campaigns\n- Negative review escalation and strategy\n- Monthly reputation health report",
            ],
            'graphic-design' => [
                'name'        => 'Graphic Design',
                'description' => "Professional design work for your brand. From logos to marketing materials, everything crafted to maintain visual consistency and elevate your brand presence.\n\nIncludes:\n- Logo design or refresh\n- Brand style guide (colors, fonts, usage rules)\n- Business card and stationery design\n- Marketing collateral (flyers, brochures, banners)\n- Social media graphics and templates\n- Two rounds of revision per deliverable",
            ],
        ];
    }

    /**
     * Register custom post type and hooks
     */
    public static function init() {
        add_action('init', [__CLASS__, 'register_post_type']);
        add_filter('single_template', [__CLASS__, 'load_proposal_template']);
    }

    /**
     * Register the mlc_proposal CPT
     */
    public static function register_post_type() {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => 'Proposals',
                'singular_name'      => 'Proposal',
                'add_new_item'       => 'New Proposal',
                'edit_item'          => 'Edit Proposal',
                'view_item'          => 'View Proposal',
                'search_items'       => 'Search Proposals',
                'not_found'          => 'No proposals found',
            ],
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => false, // We handle the admin UI ourselves
            'show_in_menu'        => false,
            'has_archive'         => false,
            'rewrite'             => ['slug' => 'proposal', 'with_front' => false],
            'supports'            => ['title'],
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ]);
    }

    /**
     * Load custom frontend template for proposals
     */
    public static function load_proposal_template($template) {
        if (is_singular(self::POST_TYPE)) {
            $custom = MLC_TOOLKIT_PATH . 'public/views/single-proposal.php';
            if (file_exists($custom)) {
                return $custom;
            }
        }
        return $template;
    }

    /**
     * Get all proposals for admin list
     */
    public static function get_all($args = []) {
        $defaults = [
            'post_type'      => self::POST_TYPE,
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ];
        return get_posts(array_merge($defaults, $args));
    }

    /**
     * Get proposal meta with defaults
     */
    public static function get_meta($post_id) {
        return [
            'client_name'    => get_post_meta($post_id, '_mlc_proposal_client_name', true) ?: '',
            'client_email'   => get_post_meta($post_id, '_mlc_proposal_client_email', true) ?: '',
            'client_company' => get_post_meta($post_id, '_mlc_proposal_client_company', true) ?: '',
            'client_logo'    => get_post_meta($post_id, '_mlc_proposal_client_logo', true) ?: 0,
            'accent_color'   => get_post_meta($post_id, '_mlc_proposal_accent_color', true) ?: '#7C3AED',
            'pin'            => get_post_meta($post_id, '_mlc_proposal_pin', true) ?: '',
            'services'       => get_post_meta($post_id, '_mlc_proposal_services', true) ?: [],
            'custom_items'   => get_post_meta($post_id, '_mlc_proposal_custom_items', true) ?: [],
            'status'         => get_post_meta($post_id, '_mlc_proposal_status', true) ?: 'draft',
            'viewed_at'      => get_post_meta($post_id, '_mlc_proposal_viewed_at', true) ?: '',
            'accepted_at'    => get_post_meta($post_id, '_mlc_proposal_accepted_at', true) ?: '',
            'notes'          => get_post_meta($post_id, '_mlc_proposal_notes', true) ?: '',
            'valid_days'     => get_post_meta($post_id, '_mlc_proposal_valid_days', true) ?: 30,
        ];
    }

    /**
     * Save proposal meta from admin form
     */
    public static function save_meta($post_id, $data) {
        $fields = [
            'client_name'    => 'sanitize_text_field',
            'client_email'   => 'sanitize_email',
            'client_company' => 'sanitize_text_field',
            'client_logo'    => 'absint',
            'accent_color'   => 'sanitize_hex_color',
            'pin'            => 'sanitize_text_field',
            'status'         => 'sanitize_text_field',
            'notes'          => 'sanitize_textarea_field',
            'valid_days'     => 'absint',
        ];

        foreach ($fields as $key => $sanitize) {
            if (isset($data[$key])) {
                $value = call_user_func($sanitize, $data[$key]);
                update_post_meta($post_id, "_mlc_proposal_{$key}", $value);
            }
        }

        // Services (array of selected services with customized descriptions + prices)
        if (isset($data['services']) && is_array($data['services'])) {
            $clean_services = [];
            foreach ($data['services'] as $slug => $svc) {
                $clean_services[$slug] = [
                    'name'        => sanitize_text_field($svc['name'] ?? ''),
                    'description' => wp_kses_post($svc['description'] ?? ''),
                    'price'       => sanitize_text_field($svc['price'] ?? ''),
                    'price_type'  => sanitize_text_field($svc['price_type'] ?? 'monthly'),
                    'setup_price' => sanitize_text_field($svc['setup_price'] ?? ''),
                ];
            }
            update_post_meta($post_id, '_mlc_proposal_services', $clean_services);
        } else {
            update_post_meta($post_id, '_mlc_proposal_services', []);
        }

        // Custom line items
        if (isset($data['custom_items']) && is_array($data['custom_items'])) {
            $clean_items = [];
            foreach ($data['custom_items'] as $item) {
                $name = sanitize_text_field($item['name'] ?? '');
                if (empty($name)) continue;
                $clean_items[] = [
                    'name'        => $name,
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'price'       => sanitize_text_field($item['price'] ?? ''),
                    'price_type'  => sanitize_text_field($item['price_type'] ?? 'one-time'),
                ];
            }
            update_post_meta($post_id, '_mlc_proposal_custom_items', $clean_items);
        } else {
            update_post_meta($post_id, '_mlc_proposal_custom_items', []);
        }
    }

    /**
     * Create a new proposal
     */
    public static function create($title) {
        $post_id = wp_insert_post([
            'post_type'   => self::POST_TYPE,
            'post_title'  => sanitize_text_field($title),
            'post_status' => 'publish',
            'post_name'   => sanitize_title($title),
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_mlc_proposal_status', 'draft');
            update_post_meta($post_id, '_mlc_proposal_pin', self::generate_pin());
            update_post_meta($post_id, '_mlc_proposal_accent_color', '#7C3AED');
            update_post_meta($post_id, '_mlc_proposal_valid_days', 30);
        }

        return $post_id;
    }

    /**
     * Generate a random 4-digit PIN
     */
    public static function generate_pin() {
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validate a PIN against a proposal
     */
    public static function validate_pin($post_id, $pin) {
        $stored = get_post_meta($post_id, '_mlc_proposal_pin', true);
        return $stored && $pin === $stored;
    }

    /**
     * Mark proposal as viewed (first time only)
     */
    public static function mark_viewed($post_id) {
        $already = get_post_meta($post_id, '_mlc_proposal_viewed_at', true);
        if ($already) return false;

        $now = current_time('mysql');
        update_post_meta($post_id, '_mlc_proposal_viewed_at', $now);
        update_post_meta($post_id, '_mlc_proposal_status', 'viewed');

        self::notify_admin($post_id, 'viewed');
        return true;
    }

    /**
     * Mark proposal as accepted
     */
    public static function mark_accepted($post_id) {
        $now = current_time('mysql');
        update_post_meta($post_id, '_mlc_proposal_accepted_at', $now);
        update_post_meta($post_id, '_mlc_proposal_status', 'accepted');

        self::notify_admin($post_id, 'accepted');
        return true;
    }

    /**
     * Check if a proposal is expired
     */
    public static function is_expired($post_id) {
        $post = get_post($post_id);
        if (!$post) return true;

        $valid_days = (int) get_post_meta($post_id, '_mlc_proposal_valid_days', true) ?: 30;
        $created    = strtotime($post->post_date);
        $expires    = $created + ($valid_days * DAY_IN_SECONDS);

        return time() > $expires;
    }

    /**
     * Get expiration date for display
     */
    public static function get_expiry_date($post_id) {
        $post = get_post($post_id);
        if (!$post) return '';

        $valid_days = (int) get_post_meta($post_id, '_mlc_proposal_valid_days', true) ?: 30;
        $created    = strtotime($post->post_date);
        $expires    = $created + ($valid_days * DAY_IN_SECONDS);

        return date('F j, Y', $expires);
    }

    /**
     * Calculate totals from services + custom items
     */
    public static function calculate_totals($post_id) {
        $meta = self::get_meta($post_id);
        $one_time = 0;
        $monthly  = 0;
        $annual   = 0;
        $setup    = 0;

        foreach ($meta['services'] as $svc) {
            $amount = (float) preg_replace('/[^0-9.]/', '', $svc['price'] ?? '0');
            $type   = $svc['price_type'] ?? 'monthly';
            if ($type === 'one-time') {
                $one_time += $amount;
            } elseif ($type === 'annual') {
                $annual += $amount;
            } else {
                $monthly += $amount;
            }

            // Setup fee (per service, always one-time)
            $setup_amount = (float) preg_replace('/[^0-9.]/', '', $svc['setup_price'] ?? '0');
            if ($setup_amount > 0) {
                $setup += $setup_amount;
            }
        }

        foreach ($meta['custom_items'] as $item) {
            $amount = (float) preg_replace('/[^0-9.]/', '', $item['price'] ?? '0');
            $type   = $item['price_type'] ?? 'one-time';
            if ($type === 'one-time') {
                $one_time += $amount;
            } elseif ($type === 'annual') {
                $annual += $amount;
            } else {
                $monthly += $amount;
            }
        }

        return [
            'one_time' => $one_time,
            'monthly'  => $monthly,
            'annual'   => $annual,
            'setup'    => $setup,
        ];
    }

    /**
     * Send admin notification email
     */
    private static function notify_admin($post_id, $event) {
        $meta    = self::get_meta($post_id);
        $post    = get_post($post_id);
        $admin   = get_option('admin_email');
        $client  = $meta['client_name'] ?: $post->post_title;
        $company = $meta['client_company'] ? " ({$meta['client_company']})" : '';
        $url     = get_permalink($post_id);

        if ($event === 'viewed') {
            $subject = "Proposal Viewed: {$client}{$company}";
            $body    = "{$client}{$company} just opened your proposal.\n\n";
            $body   .= "View in admin: " . admin_url("admin.php?page=mlc-proposals&action=edit&id={$post_id}") . "\n";
            $body   .= "Proposal URL: {$url}\n";
        } else {
            $subject = "Proposal Accepted: {$client}{$company}";
            $body    = "{$client}{$company} accepted your proposal.\n\n";
            $body   .= "View in admin: " . admin_url("admin.php?page=mlc-proposals&action=edit&id={$post_id}") . "\n";
            $body   .= "Proposal URL: {$url}\n";

            $totals = self::calculate_totals($post_id);
            if ($totals['setup'] > 0) {
                $body .= "\nSetup: $" . number_format($totals['setup'], 2);
            }
            if ($totals['one_time'] > 0) {
                $body .= "\nOne-time: $" . number_format($totals['one_time'], 2);
            }
            if ($totals['monthly'] > 0) {
                $body .= "\nMonthly: $" . number_format($totals['monthly'], 2) . "/mo";
            }
            if ($totals['annual'] > 0) {
                $body .= "\nAnnual: $" . number_format($totals['annual'], 2) . "/yr";
            }
        }

        wp_mail($admin, $subject, $body);
    }

    /**
     * Status labels for display
     */
    public static function get_status_labels() {
        return [
            'draft'    => 'Draft',
            'sent'     => 'Sent',
            'viewed'   => 'Viewed',
            'accepted' => 'Accepted',
            'expired'  => 'Expired',
        ];
    }

    /**
     * Status badge color mapping
     */
    public static function get_status_color($status) {
        $colors = [
            'draft'    => '#646970',
            'sent'     => '#2271b1',
            'viewed'   => '#dba617',
            'accepted' => '#00a32a',
            'expired'  => '#d63638',
        ];
        return $colors[$status] ?? '#646970';
    }
}
