<?php if (!defined('ABSPATH')) { exit; } ?>
<?php
$is_edit = is_array($question);
$options = $is_edit ? array_pad($question['options'], 6, ['option_text' => '', 'is_correct' => 0]) : array_fill(0, 6, ['option_text' => '', 'is_correct' => 0]);
$correct_index = 0;
foreach ($options as $index => $option) { if (!empty($option['is_correct'])) { $correct_index = $index; } }
?>
<div class="wrap skpq-admin-wrap">
    <h1><?php echo esc_html($is_edit ? __('Edit Question', 'skill-kshetra-pro-quiz') : __('Add Question', 'skill-kshetra-pro-quiz')); ?></h1>
    <?php if (isset($_GET['skpq_error'])) : ?><div class="notice notice-error"><p><?php esc_html_e('Question, quiz, at least two options, and exactly one correct answer are required.', 'skill-kshetra-pro-quiz'); ?></p></div><?php endif; ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="skpq-question-builder">
        <input type="hidden" name="action" value="skpq_save_question"><input type="hidden" name="id" value="<?php echo esc_attr((string) ($question['id'] ?? 0)); ?>"><?php wp_nonce_field('skpq_save_question'); ?>
        <div class="skpq-builder-grid">
            <table class="form-table" role="presentation">
                <tr><th><label for="skpq-quiz-id"><?php esc_html_e('Quiz *', 'skill-kshetra-pro-quiz'); ?></label></th><td><select id="skpq-quiz-id" name="quiz_id" required><option value=""><?php esc_html_e('Select quiz', 'skill-kshetra-pro-quiz'); ?></option><?php foreach ($quizzes as $quiz) : ?><option value="<?php echo esc_attr((string) $quiz['id']); ?>" <?php selected((int) ($question['quiz_id'] ?? 0), (int) $quiz['id']); ?>><?php echo esc_html($quiz['title']); ?></option><?php endforeach; ?></select></td></tr>
                <tr><th><label for="skpq-question"><?php esc_html_e('Question *', 'skill-kshetra-pro-quiz'); ?></label></th><td><textarea id="skpq-question" name="question" class="large-text skpq-preview-question" rows="4" required><?php echo esc_textarea($question['question'] ?? ''); ?></textarea></td></tr>
                <?php foreach (range(0, 5) as $index) : ?><tr><th><label for="skpq-option-<?php echo esc_attr((string) $index); ?>"><?php echo esc_html(sprintf(__('Option %s%s', 'skill-kshetra-pro-quiz'), chr(65 + $index), $index < 2 ? ' *' : '')); ?></label></th><td><input id="skpq-option-<?php echo esc_attr((string) $index); ?>" class="regular-text skpq-preview-option" name="options[<?php echo esc_attr((string) $index); ?>]" value="<?php echo esc_attr($options[$index]['option_text']); ?>" <?php echo $index < 2 ? 'required' : ''; ?>></td></tr><?php endforeach; ?>
                <tr><th><?php esc_html_e('Correct Answer *', 'skill-kshetra-pro-quiz'); ?></th><td><?php foreach (range(0, 5) as $index) : ?><label><input type="radio" name="correct_answer" value="<?php echo esc_attr((string) $index); ?>" <?php checked($correct_index, $index); ?>> <?php echo esc_html(chr(65 + $index)); ?></label> <?php endforeach; ?></td></tr>
                <tr><th><label for="skpq-explanation"><?php esc_html_e('Explanation', 'skill-kshetra-pro-quiz'); ?></label></th><td><textarea id="skpq-explanation" name="explanation" class="large-text" rows="4"><?php echo esc_textarea($question['explanation'] ?? ''); ?></textarea></td></tr>
                <tr><th><label for="skpq-explanation-image"><?php esc_html_e('Explanation Image', 'skill-kshetra-pro-quiz'); ?></label></th><td><input id="skpq-explanation-image" class="regular-text" name="explanation_image" value="<?php echo esc_attr($question['explanation_image'] ?? ''); ?>"></td></tr>
                <tr><th><label for="skpq-explanation-video"><?php esc_html_e('Explanation Video URL', 'skill-kshetra-pro-quiz'); ?></label></th><td><input id="skpq-explanation-video" class="regular-text" name="explanation_video" value="<?php echo esc_attr($question['explanation_video'] ?? ''); ?>"></td></tr>
                <tr><th><label for="skpq-difficulty"><?php esc_html_e('Difficulty', 'skill-kshetra-pro-quiz'); ?></label></th><td><select id="skpq-difficulty" name="difficulty"><?php foreach (['easy', 'medium', 'hard'] as $difficulty) : ?><option value="<?php echo esc_attr($difficulty); ?>" <?php selected($question['difficulty'] ?? 'medium', $difficulty); ?>><?php echo esc_html(ucfirst($difficulty)); ?></option><?php endforeach; ?></select></td></tr>
                <tr><th><label for="skpq-points"><?php esc_html_e('Points', 'skill-kshetra-pro-quiz'); ?></label></th><td><input id="skpq-points" type="number" min="1" name="points" value="<?php echo esc_attr((string) ($question['points'] ?? 1)); ?>"></td></tr>
                <tr><th><label for="skpq-status"><?php esc_html_e('Status', 'skill-kshetra-pro-quiz'); ?></label></th><td><select id="skpq-status" name="status"><option value="1" <?php selected((string) ($question['status'] ?? '1'), '1'); ?>><?php esc_html_e('Active', 'skill-kshetra-pro-quiz'); ?></option><option value="0" <?php selected((string) ($question['status'] ?? '1'), '0'); ?>><?php esc_html_e('Inactive', 'skill-kshetra-pro-quiz'); ?></option></select></td></tr>
            </table>
            <aside class="skpq-preview"><h2><?php esc_html_e('Question Preview', 'skill-kshetra-pro-quiz'); ?></h2><p data-skpq-preview-question><?php echo esc_html($question['question'] ?? __('Your question preview appears here.', 'skill-kshetra-pro-quiz')); ?></p><div data-skpq-preview-options></div></aside>
        </div>
        <?php submit_button(__('Save Question', 'skill-kshetra-pro-quiz')); ?>
    </form>
</div>
