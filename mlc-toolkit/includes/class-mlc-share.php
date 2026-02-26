<?php
/**
 * MLC Share — URL shortener + analytics tracking
 *
 * Two custom tables:
 *   mlc_share_links  — each generated share link
 *   mlc_share_clicks — each click on a share link
 */

if (!defined('ABSPATH')) exit;

class MLC_Share {

    /**
     * Create database tables on activation
     */
    public static function create_tables() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $links_table  = $wpdb->prefix . 'mlc_share_links';
        $clicks_table = $wpdb->prefix . 'mlc_share_clicks';

        $sql_links = "CREATE TABLE $links_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            code varchar(12) NOT NULL,
            name_hash varchar(64) NOT NULL,
            name_display varchar(100) NOT NULL DEFAULT '',
            context text NOT NULL,
            url_encoded text NOT NULL,
            source varchar(20) NOT NULL DEFAULT 'frontend',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY code (code),
            KEY created_at (created_at)
        ) $charset;";

        $sql_clicks = "CREATE TABLE $clicks_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            link_id bigint(20) unsigned NOT NULL,
            clicked_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            referrer varchar(512) NOT NULL DEFAULT '',
            user_agent varchar(512) NOT NULL DEFAULT '',
            PRIMARY KEY (id),
            KEY link_id (link_id),
            KEY clicked_at (clicked_at)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql_links);
        dbDelta($sql_clicks);
    }

    /**
     * Generate a unique short code
     */
    private static function generate_code($length = 7) {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $code  = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Create a new share link
     */
    public static function create_link($name, $context = '', $source = 'frontend') {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';

        // Build the personalization payload (same encoding as frontend)
        $payload     = $context ? "{$name}|{$context}" : $name;
        $url_encoded = base64_encode($payload);

        // Generate unique short code
        $code = self::generate_code();
        $attempts = 0;
        while (self::get_by_code($code) && $attempts < 10) {
            $code = self::generate_code();
            $attempts++;
        }

        $wpdb->insert($table, [
            'code'         => $code,
            'name_hash'    => hash('sha256', strtolower(trim($name))),
            'name_display' => sanitize_text_field($name),
            'context'      => sanitize_text_field($context),
            'url_encoded'  => $url_encoded,
            'source'       => sanitize_text_field($source),
            'created_at'   => current_time('mysql'),
        ]);

        $short_url = home_url("/s/{$code}");
        $full_url  = home_url("/?u={$url_encoded}");

        return [
            'id'        => $wpdb->insert_id,
            'code'      => $code,
            'short_url' => $short_url,
            'full_url'  => $full_url,
        ];
    }

    /**
     * Look up a share link by short code
     */
    public static function get_by_code($code) {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE code = %s", $code
        ));
    }

    /**
     * Record a click event
     */
    public static function record_click($link_id, $meta = []) {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_clicks';

        $wpdb->insert($table, [
            'link_id'    => absint($link_id),
            'clicked_at' => current_time('mysql'),
            'referrer'   => sanitize_text_field($meta['referrer'] ?? ''),
            'user_agent' => sanitize_text_field($meta['user_agent'] ?? ''),
        ]);
    }

    // ─── ANALYTICS QUERIES ───────────────────────────────────────

    /**
     * Summary stats
     */
    public static function get_stats() {
        global $wpdb;
        $links_table  = $wpdb->prefix . 'mlc_share_links';
        $clicks_table = $wpdb->prefix . 'mlc_share_clicks';

        $total_links  = (int) $wpdb->get_var("SELECT COUNT(*) FROM $links_table");
        $total_clicks = (int) $wpdb->get_var("SELECT COUNT(*) FROM $clicks_table");

        $links_today = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $links_table WHERE DATE(created_at) = %s",
            current_time('Y-m-d')
        ));

        $clicks_today = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $clicks_table WHERE DATE(clicked_at) = %s",
            current_time('Y-m-d')
        ));

        $links_week = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $links_table WHERE created_at >= %s",
            date('Y-m-d 00:00:00', strtotime('-7 days'))
        ));

        $clicks_week = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $clicks_table WHERE clicked_at >= %s",
            date('Y-m-d 00:00:00', strtotime('-7 days'))
        ));

        return [
            'total_links'    => $total_links,
            'total_clicks'   => $total_clicks,
            'conversion_rate' => $total_links > 0 ? round(($total_clicks / $total_links) * 100, 1) : 0,
            'links_today'    => $links_today,
            'clicks_today'   => $clicks_today,
            'links_week'     => $links_week,
            'clicks_week'    => $clicks_week,
        ];
    }

    /**
     * Get all links with click counts (paginated, optional source filter)
     */
    public static function get_links_with_clicks($page = 1, $per_page = 15, $source = '') {
        global $wpdb;
        $links_table  = $wpdb->prefix . 'mlc_share_links';
        $clicks_table = $wpdb->prefix . 'mlc_share_clicks';
        $offset       = ($page - 1) * $per_page;

        $where = '';
        if ($source) {
            $where = $wpdb->prepare(" WHERE l.source = %s", $source);
        }

        $total = (int) $wpdb->get_var(
            $source
                ? $wpdb->prepare("SELECT COUNT(*) FROM $links_table WHERE source = %s", $source)
                : "SELECT COUNT(*) FROM $links_table"
        );

        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT l.*, COUNT(c.id) AS click_count
             FROM $links_table l
             LEFT JOIN $clicks_table c ON c.link_id = l.id
             {$where}
             GROUP BY l.id
             ORDER BY l.created_at DESC
             LIMIT %d OFFSET %d",
            $per_page, $offset
        ));

        return [
            'links'    => $rows,
            'total'    => $total,
            'pages'    => ceil($total / $per_page),
            'page'     => $page,
            'per_page' => $per_page,
        ];
    }

    /**
     * Top contexts (most frequently used)
     */
    public static function get_top_contexts($limit = 10) {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT context, COUNT(*) AS count
             FROM $table
             WHERE context != ''
             GROUP BY context
             ORDER BY count DESC
             LIMIT %d",
            $limit
        ));
    }

    /**
     * Recent activity (links + clicks interleaved by time)
     */
    public static function get_recent_activity($limit = 10) {
        global $wpdb;
        $links_table  = $wpdb->prefix . 'mlc_share_links';
        $clicks_table = $wpdb->prefix . 'mlc_share_clicks';

        $links = $wpdb->get_results($wpdb->prepare(
            "SELECT 'created' AS event_type, l.name_display, l.context, l.code, l.created_at AS event_time
             FROM $links_table l
             ORDER BY l.created_at DESC
             LIMIT %d",
            $limit
        ));

        $clicks = $wpdb->get_results($wpdb->prepare(
            "SELECT 'clicked' AS event_type, l.name_display, l.context, l.code, c.clicked_at AS event_time
             FROM $clicks_table c
             JOIN $links_table l ON l.id = c.link_id
             ORDER BY c.clicked_at DESC
             LIMIT %d",
            $limit
        ));

        // Merge and sort by time
        $activity = array_merge($links, $clicks);
        usort($activity, function ($a, $b) {
            return strtotime($b->event_time) - strtotime($a->event_time);
        });

        return array_slice($activity, 0, $limit);
    }

    /**
     * Get a single link by ID
     */
    public static function get_link_by_id($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d", absint($id)
        ));
    }

    /**
     * Update an existing link's name and context (re-encodes url_encoded)
     */
    public static function update_link($id, $name, $context = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';

        $payload     = $context ? "{$name}|{$context}" : $name;
        $url_encoded = base64_encode($payload);

        return $wpdb->update($table, [
            'name_display' => sanitize_text_field($name),
            'name_hash'    => hash('sha256', strtolower(trim($name))),
            'context'      => sanitize_text_field($context),
            'url_encoded'  => $url_encoded,
        ], ['id' => absint($id)]);
    }

    /**
     * Migrate v1 data: unmask names, expand columns
     */
    public static function migrate_v2() {
        global $wpdb;
        $table = $wpdb->prefix . 'mlc_share_links';

        // Expand columns if needed (dbDelta in create_tables handles schema)
        self::create_tables();

        // Unmask existing name_display values by decoding url_encoded
        $rows = $wpdb->get_results("SELECT id, url_encoded, name_display FROM $table");
        foreach ($rows as $row) {
            $decoded = base64_decode($row->url_encoded);
            if ($decoded === false) continue;
            $parts = explode('|', $decoded, 2);
            $real_name = $parts[0];

            // Only update if currently masked (contains asterisks)
            if (strpos($row->name_display, '*') !== false) {
                $wpdb->update($table, [
                    'name_display' => sanitize_text_field($real_name),
                ], ['id' => $row->id]);
            }
        }
    }
}
