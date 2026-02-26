<?php
if (!defined('ABSPATH')) exit;

$stats    = MLC_Share::get_stats();
$page_num = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
$data     = MLC_Share::get_links_with_clicks($page_num);
$top_ctx  = MLC_Share::get_top_contexts(10);
$recent   = MLC_Share::get_recent_activity(10);
?>
<div class="wrap mlc-admin">
    <h1>Share Analytics</h1>
    <p class="description">Track share link creation, click-throughs, and conversion rates.</p>

    <!-- Summary Cards -->
    <div class="mlc-stats-grid">
        <div class="mlc-stat-card">
            <div class="mlc-stat-card__value"><?php echo esc_html($stats['total_links']); ?></div>
            <div class="mlc-stat-card__label">Total Links Created</div>
        </div>
        <div class="mlc-stat-card">
            <div class="mlc-stat-card__value"><?php echo esc_html($stats['total_clicks']); ?></div>
            <div class="mlc-stat-card__label">Total Clicks</div>
        </div>
        <div class="mlc-stat-card mlc-stat-card--highlight">
            <div class="mlc-stat-card__value"><?php echo esc_html($stats['conversion_rate']); ?>%</div>
            <div class="mlc-stat-card__label">Click-through Rate</div>
        </div>
        <div class="mlc-stat-card">
            <div class="mlc-stat-card__value"><?php echo esc_html($stats['links_today']); ?> / <?php echo esc_html($stats['clicks_today']); ?></div>
            <div class="mlc-stat-card__label">Today (Created / Clicked)</div>
        </div>
        <div class="mlc-stat-card">
            <div class="mlc-stat-card__value"><?php echo esc_html($stats['links_week']); ?> / <?php echo esc_html($stats['clicks_week']); ?></div>
            <div class="mlc-stat-card__label">This Week (Created / Clicked)</div>
        </div>
    </div>

    <div class="mlc-analytics-columns">
        <!-- Left: Links Table -->
        <div class="mlc-analytics-main">
            <h2>All Share Links</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">Code</th>
                        <th style="width: 120px;">Name</th>
                        <th>Context</th>
                        <th style="width: 60px;">Clicks</th>
                        <th style="width: 140px;">Created</th>
                        <th style="width: 200px;">Short URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['links'])): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 20px; color: #999;">No share links created yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($data['links'] as $link): ?>
                            <tr>
                                <td><code><?php echo esc_html($link->code); ?></code></td>
                                <td><strong><?php echo esc_html($link->name_display); ?></strong></td>
                                <td><?php echo esc_html($link->context ?: '(none)'); ?></td>
                                <td>
                                    <strong><?php echo esc_html($link->click_count); ?></strong>
                                </td>
                                <td><?php echo esc_html(date('M j, g:i A', strtotime($link->created_at))); ?></td>
                                <td><code style="font-size: 11px;"><?php echo esc_html(home_url('/s/' . $link->code)); ?></code></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($data['pages'] > 1): ?>
                <div class="mlc-pagination">
                    <span class="mlc-pagination__info">
                        Page <?php echo $data['page']; ?> of <?php echo $data['pages']; ?>
                        (<?php echo $data['total']; ?> links)
                    </span>
                    <?php if ($data['page'] > 1): ?>
                        <a class="button" href="<?php echo esc_url(admin_url("admin.php?page=mlc-share-analytics&paged=" . ($data['page'] - 1))); ?>">&laquo; Prev</a>
                    <?php endif; ?>
                    <?php for ($p = 1; $p <= $data['pages']; $p++): ?>
                        <?php if ($p == $page_num): ?>
                            <span class="button button-primary disabled"><?php echo $p; ?></span>
                        <?php else: ?>
                            <a class="button" href="<?php echo esc_url(admin_url("admin.php?page=mlc-share-analytics&paged={$p}")); ?>"><?php echo $p; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($data['page'] < $data['pages']): ?>
                        <a class="button" href="<?php echo esc_url(admin_url("admin.php?page=mlc-share-analytics&paged=" . ($data['page'] + 1))); ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Sidebar -->
        <div class="mlc-analytics-sidebar">
            <!-- Top Contexts -->
            <div class="mlc-sidebar-card">
                <h3>Top Contexts</h3>
                <?php if (empty($top_ctx)): ?>
                    <p class="mlc-sidebar-empty">No contexts yet.</p>
                <?php else: ?>
                    <ul class="mlc-context-list">
                        <?php foreach ($top_ctx as $ctx): ?>
                            <li>
                                <span class="mlc-context-list__text"><?php echo esc_html($ctx->context); ?></span>
                                <span class="mlc-context-list__count"><?php echo esc_html($ctx->count); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Recent Activity -->
            <div class="mlc-sidebar-card">
                <h3>Recent Activity</h3>
                <?php if (empty($recent)): ?>
                    <p class="mlc-sidebar-empty">No activity yet.</p>
                <?php else: ?>
                    <ul class="mlc-activity-list">
                        <?php foreach ($recent as $event): ?>
                            <li class="mlc-activity-item mlc-activity-item--<?php echo esc_attr($event->event_type); ?>">
                                <span class="mlc-activity-item__icon">
                                    <?php echo $event->event_type === 'created' ? '&#x1F517;' : '&#x1F4C8;'; ?>
                                </span>
                                <span class="mlc-activity-item__text">
                                    <strong><?php echo esc_html($event->name_display); ?></strong>
                                    <?php echo $event->event_type === 'created' ? 'created' : 'clicked'; ?>
                                    <?php if ($event->context): ?>
                                        <em>"<?php echo esc_html($event->context); ?>"</em>
                                    <?php endif; ?>
                                </span>
                                <span class="mlc-activity-item__time">
                                    <?php echo esc_html(human_time_diff(strtotime($event->event_time), current_time('timestamp')) . ' ago'); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
