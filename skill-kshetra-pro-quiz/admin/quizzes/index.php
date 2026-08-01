<?php if (!defined('ABSPATH')) { exit; } $quizzes = Smart_Quiz_Pro_Quiz::recent(); ?>
<div class="wrap sqp-admin-wrap">
    <h1><?php esc_html_e('Quiz Management', 'skill-kshetra-pro-quiz'); ?></h1>
    <p><?php esc_html_e('Create quizzes with name, description, category, difficulty, passing marks, timer, random questions/options, negative marks, maximum attempts, and status.', 'skill-kshetra-pro-quiz'); ?></p>
    <table class="widefat striped"><thead><tr><th><?php esc_html_e('Quiz Name', 'skill-kshetra-pro-quiz'); ?></th><th><?php esc_html_e('Difficulty', 'skill-kshetra-pro-quiz'); ?></th><th><?php esc_html_e('Status', 'skill-kshetra-pro-quiz'); ?></th><th><?php esc_html_e('Shortcode', 'skill-kshetra-pro-quiz'); ?></th></tr></thead><tbody>
    <?php foreach ($quizzes as $quiz) : ?><tr><td><?php echo esc_html($quiz['name']); ?></td><td><?php echo esc_html($quiz['difficulty']); ?></td><td><?php echo esc_html($quiz['status']); ?></td><td><code>[smartquiz id="<?php echo esc_attr((string) $quiz['id']); ?>"]</code></td></tr><?php endforeach; ?>
    </tbody></table>
</div>
