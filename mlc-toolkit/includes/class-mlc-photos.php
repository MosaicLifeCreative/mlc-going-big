<?php
/**
 * MLC Photos — slideshow photo management
 *
 * Stores photo data as a WordPress option (serialized array).
 * Each entry references a WP media attachment ID plus caption/credit metadata.
 */

if (!defined('ABSPATH')) exit;

class MLC_Photos {

    const OPTION_KEY = 'mlc_slideshow_photos';

    /**
     * Get all photos in admin sort order
     */
    public static function get_all() {
        $photos = get_option(self::OPTION_KEY, []);
        if (!is_array($photos)) return [];

        // Ensure each entry has required fields
        return array_values(array_map(function ($p) {
            return wp_parse_args($p, [
                'attachment_id' => 0,
                'url'           => '',
                'caption'       => '',
                'credit'        => '',
                'sort_order'    => 0,
            ]);
        }, $photos));
    }

    /**
     * Get photos for frontend (randomized, with resolved URLs).
     * Result is cached per request so multiple calls return the same
     * shuffled order (fixes caption/photo mismatch between PHP HTML
     * rendering and JS localization).
     */
    private static $frontend_cache = null;

    public static function get_frontend_data() {
        if (self::$frontend_cache !== null) {
            return self::$frontend_cache;
        }

        $photos = self::get_all();

        // Resolve attachment URLs for any that use attachment IDs
        foreach ($photos as &$photo) {
            if (!empty($photo['attachment_id']) && empty($photo['url'])) {
                $url = wp_get_attachment_image_url($photo['attachment_id'], 'large');
                if ($url) {
                    $photo['url'] = $url;
                }
            }
        }
        unset($photo);

        // Remove photos with no valid URL
        $photos = array_filter($photos, function ($p) {
            return !empty($p['url']);
        });

        // Randomize order (once per request)
        $photos = array_values($photos);
        shuffle($photos);

        self::$frontend_cache = array_map(function ($p) {
            return [
                'url'     => $p['url'],
                'caption' => $p['caption'],
                'credit'  => $p['credit'],
            ];
        }, $photos);

        return self::$frontend_cache;
    }

    /**
     * Save photos array (from admin)
     */
    public static function save($photos) {
        $clean = [];

        foreach ($photos as $i => $photo) {
            $clean[] = [
                'attachment_id' => absint($photo['attachment_id'] ?? 0),
                'url'           => esc_url_raw($photo['url'] ?? ''),
                'caption'       => sanitize_text_field($photo['caption'] ?? ''),
                'credit'        => sanitize_text_field($photo['credit'] ?? ''),
                'sort_order'    => $i,
            ];
        }

        update_option(self::OPTION_KEY, $clean);
    }

    /**
     * Seed default photos on activation (only if option doesn't exist)
     */
    public static function seed_defaults() {
        if (get_option(self::OPTION_KEY) !== false) return;

        $defaults = [
            [
                'attachment_id' => 0,
                'url'           => '/wp-content/uploads/2026/02/flagstaff-hike.jpg',
                'caption'       => 'Trail at Buffalo Park, Flagstaff',
                'credit'        => 'TREY KAUFFMAN',
                'sort_order'    => 0,
            ],
            [
                'attachment_id' => 0,
                'url'           => '/wp-content/uploads/2026/02/tuscon-at-sunset.jpg',
                'caption'       => 'Rincon Mountains, Tucson',
                'credit'        => 'TREY KAUFFMAN',
                'sort_order'    => 1,
            ],
            [
                'attachment_id' => 0,
                'url'           => '/wp-content/uploads/2026/02/buffalo-park-sunset.jpg',
                'caption'       => 'San Francisco Peaks, Flagstaff',
                'credit'        => 'TREY KAUFFMAN',
                'sort_order'    => 2,
            ],
            [
                'attachment_id' => 0,
                'url'           => '/wp-content/uploads/2026/02/buffalo-park-scaled.jpg',
                'caption'       => 'Sunset at Buffalo Park, Flagstaff',
                'credit'        => 'TREY KAUFFMAN',
                'sort_order'    => 3,
            ],
            [
                'attachment_id' => 0,
                'url'           => '/wp-content/uploads/2026/02/dharma-initiative.jpg',
                'caption'       => 'Dharma Initiative, South Pacific',
                'credit'        => 'HUGO REYES',
                'sort_order'    => 4,
            ],
        ];

        update_option(self::OPTION_KEY, $defaults);
    }
}
