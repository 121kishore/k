<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrap skpq-admin-wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Quizzes', 'skill-kshetra-pro-quiz'); ?></h1>
    <a class="page-title-action" href="<?php echo esc_url(admin_url('admin.php?page=skpq-add-quiz')); ?>"><?php esc_html_e('Add Quiz', 'skill-kshetra-pro-quiz'); ?></a>
    <hr class="wp-header-end">
    <?php if (isset($_GET['skpq_message'])) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html(sanitize_text_field(wp_unslash($_GET['skpq_message']))); ?></p></div>
    <?php endif; ?>
    <form method="get">
        <input type="hidden" name="page" value="skpq-quizzes">
        <?php $list_table->display(); ?>
    </form>
</div>
