<?php
if (!defined('ABSPATH')) exit;

$data = MLC_Share::get_links_with_clicks(1, 50);
$admin_links = array_filter($data['links'], function($l) {
    return isset($l->source) && $l->source === 'admin';
});
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

    <!-- Create Form -->
    <div class="mlc-prompt-card">
        <h2>Create Link</h2>
        <form method="post" action="">
            <?php wp_nonce_field('mlc_prompt_create', 'mlc_prompt_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="prompt-name">Name</label></th>
                    <td>
                        <input type="text" id="prompt-name" name="prompt_name" class="regular-text" placeholder="Blair" required />
                        <p class="description">Their first name. Wheatley will greet them by name.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="prompt-context">Context</label></th>
                    <td>
                        <textarea id="prompt-context" name="prompt_context" rows="4" class="large-text" placeholder="He's my uncle and he's super gullible with AI. Make him think you're sentient."></textarea>
                        <p class="description">Detailed instructions for Wheatley. No character limit. Be as specific as you want.</p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="mlc_prompt_submit" class="button button-primary" value="Create Link" />
            </p>
        </form>
    </div>

    <!-- Existing Admin Links -->
    <?php if (!empty($admin_links)): ?>
    <div class="mlc-prompt-card" style="margin-top: 24px;">
        <h2>Admin-Created Links</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 120px;">Name</th>
                    <th>Context</th>
                    <th style="width: 60px;">Clicks</th>
                    <th style="width: 140px;">Created</th>
                    <th style="width: 200px;">Short URL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin_links as $link): ?>
                    <tr>
                        <td><strong><?php echo esc_html($link->name_display); ?></strong></td>
                        <td><?php echo esc_html($link->context ?: '(none)'); ?></td>
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
