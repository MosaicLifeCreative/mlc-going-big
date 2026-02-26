<?php
if (!defined('ABSPATH')) exit;

$post_id  = isset($_GET['id']) ? absint($_GET['id']) : 0;
$is_new   = !$post_id;
$post     = $post_id ? get_post($post_id) : null;
$meta     = $post_id ? MLC_Proposal::get_meta($post_id) : [];
$catalog  = MLC_Proposal::get_service_catalog();
$statuses = MLC_Proposal::get_status_labels();

// Defaults for new proposals
if ($is_new) {
    $meta = [
        'client_name'    => '',
        'client_email'   => '',
        'client_company' => '',
        'client_logo'    => 0,
        'accent_color'   => '#7C3AED',
        'pin'            => MLC_Proposal::generate_pin(),
        'services'       => [],
        'custom_items'   => [],
        'status'         => 'draft',
        'viewed_at'      => '',
        'accepted_at'    => '',
        'notes'          => '',
        'valid_days'     => 30,
    ];
}

$title = $post ? $post->post_title : '';
?>
<div class="wrap mlc-admin">
    <h1>
        <a href="<?php echo esc_url(admin_url('admin.php?page=mlc-proposals')); ?>">&larr; Proposals</a>
        &nbsp;/&nbsp;
        <?php echo $is_new ? 'New Proposal' : 'Edit Proposal'; ?>
    </h1>

    <?php if (isset($_GET['saved'])): ?>
        <div class="notice notice-success is-dismissible"><p>Proposal saved.</p></div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'title'): ?>
        <div class="notice notice-error is-dismissible"><p>Proposal title is required.</p></div>
    <?php endif; ?>

    <?php if ($post_id && !$is_new): ?>
        <div class="mlc-proposal-url-bar">
            <strong>Proposal URL:</strong>
            <code id="mlc-proposal-url"><?php echo esc_html(get_permalink($post_id)); ?></code>
            <button type="button" class="button button-small" onclick="navigator.clipboard.writeText(document.getElementById('mlc-proposal-url').textContent).then(function(){this.textContent='Copied!';}.bind(this))">Copy</button>
            <span class="mlc-proposal-url-bar__pin">PIN: <strong><?php echo esc_html($meta['pin']); ?></strong></span>
            <?php if ($meta['viewed_at']): ?>
                <span class="mlc-proposal-url-bar__badge" style="background: <?php echo esc_attr(MLC_Proposal::get_status_color('viewed')); ?>">
                    Viewed <?php echo esc_html(date('M j, g:i A', strtotime($meta['viewed_at']))); ?>
                </span>
            <?php endif; ?>
            <?php if ($meta['accepted_at']): ?>
                <span class="mlc-proposal-url-bar__badge" style="background: <?php echo esc_attr(MLC_Proposal::get_status_color('accepted')); ?>">
                    Accepted <?php echo esc_html(date('M j, g:i A', strtotime($meta['accepted_at']))); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('mlc_save_proposal', 'mlc_proposal_nonce'); ?>
        <input type="hidden" name="proposal_id" value="<?php echo esc_attr($post_id); ?>" />

        <div class="mlc-proposal-grid">
            <!-- LEFT COLUMN: Main content -->
            <div class="mlc-proposal-main">

                <!-- Proposal Title -->
                <div class="mlc-proposal-card">
                    <h2>Proposal Title</h2>
                    <input type="text" name="proposal_title" value="<?php echo esc_attr($title); ?>" class="large-text" placeholder="e.g., Website Redesign for Blackburn's Chimney" required />
                    <p class="description">Internal title shown at the top of the proposal.</p>

                    <div style="margin-top: 12px;">
                        <label for="proposal_slug" style="font-weight: 600; font-size: 13px;">URL Slug</label>
                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                            <span class="description" style="margin: 0; white-space: nowrap;"><?php echo esc_html(home_url('/proposal/')); ?></span>
                            <input type="text" id="proposal_slug" name="proposal_slug" value="<?php echo esc_attr($post ? $post->post_name : ''); ?>" class="regular-text" placeholder="auto-generated from company name" />
                        </div>
                        <p class="description">Leave blank to auto-generate from the company name. Lowercase, hyphens only.</p>
                    </div>
                </div>

                <!-- Client Information -->
                <div class="mlc-proposal-card">
                    <h2>Client Information</h2>
                    <table class="form-table">
                        <tr>
                            <th><label for="client_name">Client Name</label></th>
                            <td><input type="text" id="client_name" name="client_name" value="<?php echo esc_attr($meta['client_name']); ?>" class="regular-text" placeholder="John Smith" /></td>
                        </tr>
                        <tr>
                            <th><label for="client_email">Email</label></th>
                            <td><input type="email" id="client_email" name="client_email" value="<?php echo esc_attr($meta['client_email']); ?>" class="regular-text" placeholder="john@company.com" /></td>
                        </tr>
                        <tr>
                            <th><label for="client_company">Company</label></th>
                            <td><input type="text" id="client_company" name="client_company" value="<?php echo esc_attr($meta['client_company']); ?>" class="regular-text" placeholder="Smith & Sons Plumbing" /></td>
                        </tr>
                        <tr>
                            <th><label>Client Logo</label></th>
                            <td>
                                <div class="mlc-proposal-logo-picker">
                                    <?php
                                    $logo_url = '';
                                    if ($meta['client_logo']) {
                                        $logo_url = wp_get_attachment_image_url($meta['client_logo'], 'medium');
                                    }
                                    ?>
                                    <div class="mlc-proposal-logo-preview" id="mlc-logo-preview" style="<?php echo $logo_url ? '' : 'display:none;'; ?>">
                                        <img src="<?php echo esc_url($logo_url); ?>" alt="" id="mlc-logo-img" />
                                    </div>
                                    <input type="hidden" name="client_logo" id="mlc-logo-id" value="<?php echo esc_attr($meta['client_logo']); ?>" />
                                    <button type="button" class="button" id="mlc-logo-upload">
                                        <?php echo $logo_url ? 'Change Logo' : 'Upload Logo'; ?>
                                    </button>
                                    <button type="button" class="button mlc-logo-remove" id="mlc-logo-remove" style="<?php echo $logo_url ? '' : 'display:none;'; ?>">Remove</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="accent_color">Accent Color</label></th>
                            <td>
                                <div class="mlc-proposal-color-picker">
                                    <input type="text" id="accent_color" name="accent_color" value="<?php echo esc_attr($meta['accent_color']); ?>" class="regular-text" placeholder="#7C3AED" maxlength="7" />
                                    <span class="mlc-proposal-color-swatch" id="mlc-color-swatch" style="background: <?php echo esc_attr($meta['accent_color']); ?>;"></span>
                                </div>
                                <p class="description">Hex color used as the primary accent on the client-facing proposal.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Services -->
                <div class="mlc-proposal-card">
                    <h2>Services</h2>
                    <p class="description">Toggle services to include. Customize descriptions and pricing for each.</p>

                    <div id="mlc-services-list">
                        <?php foreach ($catalog as $slug => $svc):
                            $included    = isset($meta['services'][$slug]);
                            $desc        = $included ? ($meta['services'][$slug]['description'] ?? $svc['description']) : $svc['description'];
                            $price       = $included ? ($meta['services'][$slug]['price'] ?? '') : '';
                            $ptype       = $included ? ($meta['services'][$slug]['price_type'] ?? 'monthly') : 'monthly';
                            $setup_price = $included ? ($meta['services'][$slug]['setup_price'] ?? '') : '';
                        ?>
                            <div class="mlc-service-row <?php echo $included ? 'mlc-service-row--active' : ''; ?>" data-slug="<?php echo esc_attr($slug); ?>">
                                <div class="mlc-service-row__header">
                                    <label class="mlc-service-row__toggle">
                                        <input type="checkbox" class="mlc-service-toggle" data-slug="<?php echo esc_attr($slug); ?>" <?php checked($included); ?> />
                                        <strong><?php echo esc_html($svc['name']); ?></strong>
                                    </label>
                                </div>
                                <div class="mlc-service-row__body" style="<?php echo $included ? '' : 'display:none;'; ?>">
                                    <textarea name="services[<?php echo esc_attr($slug); ?>][description]" rows="6" class="large-text mlc-service-desc"><?php echo esc_textarea($desc); ?></textarea>
                                    <input type="hidden" name="services[<?php echo esc_attr($slug); ?>][name]" value="<?php echo esc_attr($svc['name']); ?>" />
                                    <div class="mlc-service-row__pricing">
                                        <label>Price: $</label>
                                        <input type="text" name="services[<?php echo esc_attr($slug); ?>][price]" value="<?php echo esc_attr($price); ?>" class="small-text" placeholder="0" />
                                        <select name="services[<?php echo esc_attr($slug); ?>][price_type]">
                                            <option value="one-time" <?php selected($ptype, 'one-time'); ?>>One-time</option>
                                            <option value="monthly" <?php selected($ptype, 'monthly'); ?>>Monthly</option>
                                            <option value="annual" <?php selected($ptype, 'annual'); ?>>Annual</option>
                                        </select>
                                        <label style="margin-left: 12px;">Setup: $</label>
                                        <input type="text" name="services[<?php echo esc_attr($slug); ?>][setup_price]" value="<?php echo esc_attr($setup_price); ?>" class="small-text" placeholder="0" />
                                        <button type="button" class="button button-small mlc-service-reset" data-slug="<?php echo esc_attr($slug); ?>">Reset to Default</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Custom Line Items -->
                <div class="mlc-proposal-card">
                    <h2>Custom Line Items</h2>
                    <p class="description">Add project-specific deliverables, one-off costs, or anything not covered by the standard services above.</p>

                    <div id="mlc-custom-items">
                        <?php if (!empty($meta['custom_items'])):
                            foreach ($meta['custom_items'] as $i => $item): ?>
                                <div class="mlc-custom-item" data-index="<?php echo $i; ?>">
                                    <div class="mlc-custom-item__header">
                                        <input type="text" name="custom_items[<?php echo $i; ?>][name]" value="<?php echo esc_attr($item['name']); ?>" class="regular-text" placeholder="Item name" />
                                        <input type="text" name="custom_items[<?php echo $i; ?>][price]" value="<?php echo esc_attr($item['price']); ?>" class="small-text" placeholder="$0" />
                                        <select name="custom_items[<?php echo $i; ?>][price_type]">
                                            <option value="one-time" <?php selected($item['price_type'] ?? 'one-time', 'one-time'); ?>>One-time</option>
                                            <option value="monthly" <?php selected($item['price_type'] ?? 'one-time', 'monthly'); ?>>Monthly</option>
                                            <option value="annual" <?php selected($item['price_type'] ?? 'one-time', 'annual'); ?>>Annual</option>
                                        </select>
                                        <button type="button" class="button button-small mlc-custom-remove">&times;</button>
                                    </div>
                                    <textarea name="custom_items[<?php echo $i; ?>][description]" rows="3" class="large-text" placeholder="Description (optional)"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>

                    <p><button type="button" class="button" id="mlc-add-custom-item">+ Add Item</button></p>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="mlc-proposal-sidebar">

                <!-- Status & Controls -->
                <div class="mlc-proposal-card">
                    <h2>Status</h2>
                    <select name="status" class="mlc-proposal-status-select">
                        <?php foreach ($statuses as $key => $label): ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($meta['status'], $key); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="mlc-proposal-sidebar-field">
                        <label for="pin">PIN (4-6 digits)</label>
                        <div class="mlc-proposal-pin-row">
                            <input type="text" id="pin" name="pin" value="<?php echo esc_attr($meta['pin']); ?>" class="small-text" maxlength="6" pattern="[0-9]{4,6}" />
                            <button type="button" class="button button-small" id="mlc-regen-pin">Regenerate</button>
                        </div>
                    </div>

                    <div class="mlc-proposal-sidebar-field">
                        <label for="valid_days">Valid for (days)</label>
                        <input type="number" id="valid_days" name="valid_days" value="<?php echo esc_attr($meta['valid_days']); ?>" class="small-text" min="1" max="365" />
                    </div>

                    <div class="mlc-proposal-sidebar-actions">
                        <button type="submit" class="button button-primary button-large" style="width: 100%;">
                            <?php echo $is_new ? 'Create Proposal' : 'Save Proposal'; ?>
                        </button>
                    </div>
                </div>

                <!-- Internal Notes -->
                <div class="mlc-proposal-card">
                    <h2>Internal Notes</h2>
                    <textarea name="notes" rows="5" class="large-text" placeholder="Notes visible only to you..."><?php echo esc_textarea($meta['notes']); ?></textarea>
                </div>

                <!-- Timeline -->
                <?php if (!$is_new): ?>
                <div class="mlc-proposal-card">
                    <h2>Timeline</h2>
                    <ul class="mlc-proposal-timeline">
                        <li>
                            <span class="mlc-proposal-timeline__dot" style="background: #646970;"></span>
                            Created: <?php echo esc_html(date('M j, Y g:i A', strtotime($post->post_date))); ?>
                        </li>
                        <?php if ($meta['viewed_at']): ?>
                        <li>
                            <span class="mlc-proposal-timeline__dot" style="background: #dba617;"></span>
                            Viewed: <?php echo esc_html(date('M j, Y g:i A', strtotime($meta['viewed_at']))); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ($meta['accepted_at']): ?>
                        <li>
                            <span class="mlc-proposal-timeline__dot" style="background: #00a32a;"></span>
                            Accepted: <?php echo esc_html(date('M j, Y g:i A', strtotime($meta['accepted_at']))); ?>
                        </li>
                        <?php endif; ?>
                        <li>
                            <span class="mlc-proposal-timeline__dot" style="background: <?php echo MLC_Proposal::is_expired($post_id) ? '#d63638' : '#2271b1'; ?>;"></span>
                            Expires: <?php echo esc_html(MLC_Proposal::get_expiry_date($post_id)); ?>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </form>
</div>

<!-- Template for custom item (used by JS) -->
<script type="text/html" id="tmpl-mlc-custom-item">
    <div class="mlc-custom-item" data-index="{{INDEX}}">
        <div class="mlc-custom-item__header">
            <input type="text" name="custom_items[{{INDEX}}][name]" value="" class="regular-text" placeholder="Item name" />
            <input type="text" name="custom_items[{{INDEX}}][price]" value="" class="small-text" placeholder="$0" />
            <select name="custom_items[{{INDEX}}][price_type]">
                <option value="one-time">One-time</option>
                <option value="monthly">Monthly</option>
                <option value="annual">Annual</option>
            </select>
            <button type="button" class="button button-small mlc-custom-remove">&times;</button>
        </div>
        <textarea name="custom_items[{{INDEX}}][description]" rows="3" class="large-text" placeholder="Description (optional)"></textarea>
    </div>
</script>
