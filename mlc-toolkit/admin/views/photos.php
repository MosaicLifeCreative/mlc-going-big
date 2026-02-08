<?php
if (!defined('ABSPATH')) exit;

$photos = MLC_Photos::get_all();
$saved  = isset($_GET['saved']);
?>
<div class="wrap mlc-admin">
    <h1>Slideshow Photos</h1>
    <p class="description">Manage the navigation slideshow photos. Drag to reorder. Photos are randomized on each page load for visitors.</p>

    <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible"><p>Photos saved.</p></div>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('mlc_save_photos', 'mlc_photos_nonce'); ?>

        <div id="mlc-photo-list" class="mlc-photo-list">
            <?php foreach ($photos as $i => $photo): ?>
                <div class="mlc-photo-item" data-index="<?php echo $i; ?>">
                    <div class="mlc-photo-item__drag">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                    <div class="mlc-photo-item__preview">
                        <?php
                        $preview_url = '';
                        if (!empty($photo['attachment_id'])) {
                            $preview_url = wp_get_attachment_image_url($photo['attachment_id'], 'thumbnail');
                        }
                        if (!$preview_url && !empty($photo['url'])) {
                            $preview_url = $photo['url'];
                        }
                        ?>
                        <img src="<?php echo esc_url($preview_url); ?>" alt="" class="mlc-photo-item__img" />
                    </div>
                    <div class="mlc-photo-item__fields">
                        <input type="hidden" name="photos[<?php echo $i; ?>][attachment_id]" value="<?php echo esc_attr($photo['attachment_id']); ?>" class="mlc-photo-attachment-id" />
                        <input type="hidden" name="photos[<?php echo $i; ?>][url]" value="<?php echo esc_url($photo['url']); ?>" class="mlc-photo-url" />
                        <div class="mlc-photo-item__row">
                            <label>Caption</label>
                            <input type="text" name="photos[<?php echo $i; ?>][caption]" value="<?php echo esc_attr($photo['caption']); ?>" class="regular-text mlc-photo-caption" />
                        </div>
                        <div class="mlc-photo-item__row">
                            <label>Credit</label>
                            <input type="text" name="photos[<?php echo $i; ?>][credit]" value="<?php echo esc_attr($photo['credit']); ?>" class="regular-text mlc-photo-credit" />
                        </div>
                    </div>
                    <div class="mlc-photo-item__actions">
                        <button type="button" class="button mlc-photo-change">Change</button>
                        <button type="button" class="button mlc-photo-remove" title="Remove photo">&times;</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p>
            <button type="button" id="mlc-add-photo" class="button button-secondary">+ Add Photo</button>
        </p>

        <hr />
        <p class="submit">
            <button type="submit" class="button button-primary">Save Photos</button>
        </p>
    </form>
</div>

<!-- Template for new photo row (used by JS) -->
<script type="text/html" id="tmpl-mlc-photo-item">
    <div class="mlc-photo-item" data-index="{{INDEX}}">
        <div class="mlc-photo-item__drag">
            <span class="dashicons dashicons-menu"></span>
        </div>
        <div class="mlc-photo-item__preview">
            <img src="" alt="" class="mlc-photo-item__img" />
        </div>
        <div class="mlc-photo-item__fields">
            <input type="hidden" name="photos[{{INDEX}}][attachment_id]" value="" class="mlc-photo-attachment-id" />
            <input type="hidden" name="photos[{{INDEX}}][url]" value="" class="mlc-photo-url" />
            <div class="mlc-photo-item__row">
                <label>Caption</label>
                <input type="text" name="photos[{{INDEX}}][caption]" value="" class="regular-text mlc-photo-caption" />
            </div>
            <div class="mlc-photo-item__row">
                <label>Credit</label>
                <input type="text" name="photos[{{INDEX}}][credit]" value="" class="regular-text mlc-photo-credit" />
            </div>
        </div>
        <div class="mlc-photo-item__actions">
            <button type="button" class="button mlc-photo-change">Change</button>
            <button type="button" class="button mlc-photo-remove" title="Remove photo">&times;</button>
        </div>
    </div>
</script>
