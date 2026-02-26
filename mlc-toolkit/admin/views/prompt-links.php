<?php
if (!defined('ABSPATH')) exit;

$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
$per_page = 15;
$data = MLC_Share::get_links_with_clicks($paged, $per_page, 'admin');
$admin_links = $data['links'];
$total_pages = $data['pages'];
$total_links = $data['total'];

// Check if we're editing
$editing = null;
if (isset($_GET['edit'])) {
    $editing = MLC_Share::get_link_by_id(absint($_GET['edit']));
}
?>
<div class="wrap mlc-admin">
    <h1>Prompt Links</h1>
    <p class="description">Create personalized share links with custom Wheatley context. Extended context controls how Wheatley greets them.</p>

    <?php if (isset($_GET['created'])): ?>
        <div class="mlc-prompt-success">
            <strong>Link created.</strong> Copy the URL below and share it however you like.
            <div class="mlc-prompt-success__url">
                <code id="mlc-new-url"><?php echo esc_html(home_url('/s/' . sanitize_text_field($_GET['created']))); ?></code>
                <button type="button" class="button" onclick="navigator.clipboard.writeText(document.getElementById('mlc-new-url').textContent).then(function(){this.textContent='Copied!';}.bind(this))">Copy</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="mlc-prompt-success">
            <strong>Link updated.</strong> The short URL is unchanged. Wheatley will use the new name and context on next visit.
        </div>
    <?php endif; ?>

    <!-- Create / Edit Form -->
    <div class="mlc-prompt-card">
        <h2><?php echo $editing ? 'Edit Link' : 'Create Link'; ?></h2>
        <form method="post" action="">
            <?php if ($editing): ?>
                <?php wp_nonce_field('mlc_prompt_edit', 'mlc_prompt_nonce'); ?>
                <input type="hidden" name="mlc_prompt_edit_id" value="<?php echo esc_attr($editing->id); ?>" />
            <?php else: ?>
                <?php wp_nonce_field('mlc_prompt_create', 'mlc_prompt_nonce'); ?>
            <?php endif; ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="prompt-name">Name</label></th>
                    <td>
                        <input type="text" id="prompt-name" name="prompt_name" class="regular-text" placeholder="Blair" required value="<?php echo esc_attr($editing ? $editing->name_display : ''); ?>" />
                        <p class="description">Their first name. Wheatley will greet them by name.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="prompt-context">Context</label></th>
                    <td>
                        <textarea id="prompt-context" name="prompt_context" rows="4" class="large-text" placeholder="He's my uncle and he's super gullible with AI. Make him think you're sentient."><?php echo esc_textarea($editing ? $editing->context : ''); ?></textarea>
                        <p class="description">Detailed instructions for Wheatley. No character limit. Be as specific as you want.</p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="mlc_prompt_submit" class="button button-primary" value="<?php echo $editing ? 'Update Link' : 'Create Link'; ?>" />
                <?php if ($editing): ?>
                    <a href="<?php echo admin_url('admin.php?page=mlc-prompt-links'); ?>" class="button">Cancel</a>
                <?php endif; ?>
            </p>
        </form>
    </div>

    <!-- Existing Admin Links -->
    <?php if (!empty($admin_links)): ?>
    <div class="mlc-prompt-card" style="margin-top: 24px;">
        <h2>Admin-Created Links <span class="count">(<?php echo esc_html($total_links); ?>)</span></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 120px;">Name</th>
                    <th>Context</th>
                    <th style="width: 60px;">Clicks</th>
                    <th style="width: 140px;">Created</th>
                    <th style="width: 240px;">Short URL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin_links as $link): ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($link->name_display); ?></strong>
                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo esc_url(admin_url('admin.php?page=mlc-prompt-links&edit=' . $link->id)); ?>">Edit</a></span>
                            </div>
                        </td>
                        <td><?php
                            $ctx_text = $link->context ?: '(none)';
                            if (strlen($ctx_text) > 80 && $ctx_text !== '(none)'):
                        ?>
                            <span class="mlc-context-truncated">
                                <span class="mlc-context-truncated__short"><?php echo esc_html(substr($ctx_text, 0, 80)); ?>&hellip;</span>
                                <span class="mlc-context-truncated__full"><?php echo esc_html($ctx_text); ?></span>
                                <button type="button" class="mlc-context-toggle" onclick="this.parentElement.classList.toggle('is-expanded');this.textContent=this.parentElement.classList.contains('is-expanded')?'less':'more'">more</button>
                            </span>
                        <?php else: ?>
                            <?php echo esc_html($ctx_text); ?>
                        <?php endif; ?></td>
                        <td><?php echo esc_html($link->click_count); ?></td>
                        <td><?php echo esc_html(date('M j, g:i A', strtotime($link->created_at))); ?></td>
                        <td>
                            <code style="font-size: 11px;" id="url-<?php echo esc_attr($link->code); ?>"><?php echo esc_html(home_url('/s/' . $link->code)); ?></code>
                            <button type="button" class="button button-small mlc-copy-btn" data-code="<?php echo esc_attr($link->code); ?>">Copy</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo esc_html($total_links); ?> items</span>
                <span class="pagination-links">
                    <?php if ($paged > 1): ?>
                        <a class="first-page button" href="<?php echo esc_url(admin_url('admin.php?page=mlc-prompt-links&paged=1')); ?>">&laquo;</a>
                        <a class="prev-page button" href="<?php echo esc_url(admin_url('admin.php?page=mlc-prompt-links&paged=' . ($paged - 1))); ?>">&lsaquo;</a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled">&laquo;</span>
                        <span class="tablenav-pages-navspan button disabled">&lsaquo;</span>
                    <?php endif; ?>

                    <span class="paging-input">
                        <span class="tablenav-paging-text"><?php echo esc_html($paged); ?> of <span class="total-pages"><?php echo esc_html($total_pages); ?></span></span>
                    </span>

                    <?php if ($paged < $total_pages): ?>
                        <a class="next-page button" href="<?php echo esc_url(admin_url('admin.php?page=mlc-prompt-links&paged=' . ($paged + 1))); ?>">&rsaquo;</a>
                        <a class="last-page button" href="<?php echo esc_url(admin_url('admin.php?page=mlc-prompt-links&paged=' . $total_pages)); ?>">&raquo;</a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled">&rsaquo;</span>
                        <span class="tablenav-pages-navspan button disabled">&raquo;</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <p style="margin-top: 16px;">
        <a href="<?php echo admin_url('admin.php?page=mlc-share-analytics'); ?>">View all links in Share Analytics &rarr;</a>
    </p>
</div>

<script>
document.querySelectorAll('.mlc-copy-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var code = this.getAttribute('data-code');
        var url = document.getElementById('url-' + code).textContent;
        navigator.clipboard.writeText(url).then(function() {
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = 'Copy'; }, 2000);
        });
    });
});
</script>
