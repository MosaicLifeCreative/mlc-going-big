<?php
if (!defined('ABSPATH')) exit;

$proposals = MLC_Proposal::get_all();
$statuses  = MLC_Proposal::get_status_labels();
?>
<div class="wrap mlc-admin">
    <h1>
        Proposals
        <a href="<?php echo esc_url(admin_url('admin.php?page=mlc-proposals&action=new')); ?>" class="page-title-action">Add New</a>
    </h1>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="notice notice-success is-dismissible"><p>Proposal deleted.</p></div>
    <?php endif; ?>

    <?php if (empty($proposals)): ?>
        <div class="mlc-proposal-empty">
            <p>No proposals yet. Create your first one to get started.</p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=mlc-proposals&action=new')); ?>" class="button button-primary">Create Proposal</a>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 200px;">Client</th>
                    <th style="width: 160px;">Company</th>
                    <th style="width: 90px;">Status</th>
                    <th style="width: 100px;">Total</th>
                    <th style="width: 130px;">Created</th>
                    <th style="width: 130px;">Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proposals as $proposal):
                    $meta   = MLC_Proposal::get_meta($proposal->ID);
                    $totals = MLC_Proposal::calculate_totals($proposal->ID);
                    $status = $meta['status'];
                    $color  = MLC_Proposal::get_status_color($status);

                    // Check real-time expiration
                    if ($status !== 'accepted' && MLC_Proposal::is_expired($proposal->ID)) {
                        $status = 'expired';
                    }
                ?>
                    <tr>
                        <td>
                            <strong>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=mlc-proposals&action=edit&id=' . $proposal->ID)); ?>">
                                    <?php echo esc_html($meta['client_name'] ?: $proposal->post_title); ?>
                                </a>
                            </strong>
                        </td>
                        <td><?php echo esc_html($meta['client_company'] ?: '-'); ?></td>
                        <td>
                            <span class="mlc-proposal-status" style="background: <?php echo esc_attr($color); ?>;">
                                <?php echo esc_html($statuses[$status] ?? ucfirst($status)); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($totals['one_time'] > 0): ?>
                                <strong>$<?php echo esc_html(number_format($totals['one_time'])); ?></strong>
                            <?php endif; ?>
                            <?php if ($totals['monthly'] > 0): ?>
                                <span class="mlc-proposal-monthly">$<?php echo esc_html(number_format($totals['monthly'])); ?>/mo</span>
                            <?php endif; ?>
                            <?php if ($totals['one_time'] === 0.0 && $totals['monthly'] === 0.0): ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html(date('M j, Y', strtotime($proposal->post_date))); ?></td>
                        <td><?php echo esc_html(MLC_Proposal::get_expiry_date($proposal->ID)); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=mlc-proposals&action=edit&id=' . $proposal->ID)); ?>">Edit</a>
                            |
                            <a href="<?php echo esc_url(get_permalink($proposal->ID)); ?>" target="_blank">View</a>
                            |
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mlc-proposals&action=delete-proposal&id=' . $proposal->ID), 'mlc_delete_proposal')); ?>" class="mlc-delete-link" onclick="return confirm('Delete this proposal? This cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
