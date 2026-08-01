<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrap skpq-admin-wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Question Bank', 'skill-kshetra-pro-quiz'); ?></h1>
    <a class="page-title-action" href="<?php echo esc_url(admin_url('admin.php?page=skpq-add-question')); ?>"><?php esc_html_e('Add Question', 'skill-kshetra-pro-quiz'); ?></a>
    <hr class="wp-header-end">
    <?php if (isset($_GET['skpq_message'])) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html(sanitize_text_field(wp_unslash($_GET['skpq_message']))); ?></p></div><?php endif; ?>
    <form method="get">
        <input type="hidden" name="page" value="skpq-questions">
        <p class="search-box"><label class="screen-reader-text" for="question-search-input"><?php esc_html_e('Search Questions', 'skill-kshetra-pro-quiz'); ?></label><input id="question-search-input" type="search" name="s" value="<?php echo esc_attr($filters['s']); ?>"><input type="submit" class="button" value="<?php echo esc_attr__('Search', 'skill-kshetra-pro-quiz'); ?>"></p>
        <div class="tablenav top skpq-filters">
            <select name="quiz_id"><option value="0"><?php esc_html_e('All quizzes', 'skill-kshetra-pro-quiz'); ?></option><?php foreach ($quizzes as $quiz) : ?><option value="<?php echo esc_attr((string) $quiz['id']); ?>" <?php selected((int) $filters['quiz_id'], (int) $quiz['id']); ?>><?php echo esc_html($quiz['title']); ?></option><?php endforeach; ?></select>
            <select name="difficulty"><option value=""><?php esc_html_e('All difficulties', 'skill-kshetra-pro-quiz'); ?></option><?php foreach (['easy', 'medium', 'hard'] as $difficulty) : ?><option value="<?php echo esc_attr($difficulty); ?>" <?php selected($filters['difficulty'], $difficulty); ?>><?php echo esc_html(ucfirst($difficulty)); ?></option><?php endforeach; ?></select>
            <select name="status"><option value=""><?php esc_html_e('All statuses', 'skill-kshetra-pro-quiz'); ?></option><option value="1" <?php selected($filters['status'], '1'); ?>><?php esc_html_e('Active', 'skill-kshetra-pro-quiz'); ?></option><option value="0" <?php selected($filters['status'], '0'); ?>><?php esc_html_e('Inactive', 'skill-kshetra-pro-quiz'); ?></option></select>
            <input type="submit" class="button" value="<?php echo esc_attr__('Filter', 'skill-kshetra-pro-quiz'); ?>">
        </div>
        <?php $list_table->display(); ?>
    </form>
</div>
