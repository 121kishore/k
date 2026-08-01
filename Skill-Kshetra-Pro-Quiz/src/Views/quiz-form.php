<?php if (!defined('ABSPATH')) { exit; } ?>
<?php
$is_edit = is_array($quiz);
$title = $is_edit ? $quiz['title'] : '';
$slug = $is_edit ? $quiz['slug'] : '';
$description = $is_edit ? $quiz['description'] : '';
$mode = $is_edit ? $quiz['mode'] : 'learn';
$timer = $is_edit ? (int) $quiz['timer'] : 0;
$passing_score = $is_edit ? (int) $quiz['passing_score'] : 70;
$status = $is_edit ? $quiz['status'] : 'draft';
?>
<div class="wrap skpq-admin-wrap">
    <h1><?php echo esc_html($is_edit ? __('Edit Quiz', 'skill-kshetra-pro-quiz') : __('Add Quiz', 'skill-kshetra-pro-quiz')); ?></h1>
    <?php if (isset($_GET['skpq_error'])) : ?>
        <div class="notice notice-error"><p><?php esc_html_e('Please enter a quiz title and a passing score between 0 and 100.', 'skill-kshetra-pro-quiz'); ?></p></div>
    <?php endif; ?>
    <form method="post" action="<?php echo esc_url($action); ?>" class="skpq-form">
        <input type="hidden" name="action" value="skpq_save_quiz">
        <input type="hidden" name="id" value="<?php echo esc_attr((string) ($quiz['id'] ?? 0)); ?>">
        <?php wp_nonce_field('skpq_save_quiz'); ?>
        <table class="form-table" role="presentation">
            <tr><th><label for="skpq-title"><?php esc_html_e('Quiz Title', 'skill-kshetra-pro-quiz'); ?></label></th><td><input class="regular-text" id="skpq-title" name="title" required value="<?php echo esc_attr($title); ?>"></td></tr>
            <tr><th><label for="skpq-slug"><?php esc_html_e('Slug', 'skill-kshetra-pro-quiz'); ?></label></th><td><input class="regular-text" id="skpq-slug" name="slug" value="<?php echo esc_attr($slug); ?>"><p class="description"><?php esc_html_e('Leave empty to auto-generate from the title.', 'skill-kshetra-pro-quiz'); ?></p></td></tr>
            <tr><th><label for="skpq-description"><?php esc_html_e('Description', 'skill-kshetra-pro-quiz'); ?></label></th><td><textarea class="large-text" id="skpq-description" name="description" rows="5"><?php echo esc_textarea($description); ?></textarea></td></tr>
            <tr><th><?php esc_html_e('Mode', 'skill-kshetra-pro-quiz'); ?></th><td><label><input type="radio" name="mode" value="learn" <?php checked($mode, 'learn'); ?>> <?php esc_html_e('Learn', 'skill-kshetra-pro-quiz'); ?></label><br><label><input type="radio" name="mode" value="exam" <?php checked($mode, 'exam'); ?>> <?php esc_html_e('Exam', 'skill-kshetra-pro-quiz'); ?></label></td></tr>
            <tr><th><label for="skpq-passing-score"><?php esc_html_e('Passing %', 'skill-kshetra-pro-quiz'); ?></label></th><td><input id="skpq-passing-score" name="passing_score" type="number" min="0" max="100" value="<?php echo esc_attr((string) $passing_score); ?>"></td></tr>
            <tr><th><label for="skpq-timer"><?php esc_html_e('Timer', 'skill-kshetra-pro-quiz'); ?></label></th><td><input id="skpq-timer" name="timer" type="number" min="0" value="<?php echo esc_attr((string) $timer); ?>"> <?php esc_html_e('minutes', 'skill-kshetra-pro-quiz'); ?></td></tr>
            <tr><th><label for="skpq-status"><?php esc_html_e('Status', 'skill-kshetra-pro-quiz'); ?></label></th><td><select id="skpq-status" name="status"><option value="draft" <?php selected($status, 'draft'); ?>><?php esc_html_e('Draft', 'skill-kshetra-pro-quiz'); ?></option><option value="published" <?php selected($status, 'published'); ?>><?php esc_html_e('Published', 'skill-kshetra-pro-quiz'); ?></option></select></td></tr>
        </table>
        <?php submit_button(__('Save Quiz', 'skill-kshetra-pro-quiz')); ?>
    </form>
</div>
