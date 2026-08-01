<?php
namespace SkillKshetraProQuiz\Admin;

use SkillKshetraProQuiz\Models\Quiz;

if (!defined('ABSPATH')) {
    exit;
}

final class Dashboard
{
    public static function render(): void
    {
        $stats = Quiz::dashboard_stats();
        ?>
        <div class="wrap skpq-admin-wrap">
            <h1><?php esc_html_e('Skill Kshetra Pro Quiz Dashboard', 'skill-kshetra-pro-quiz'); ?></h1>
            <div class="skpq-cards">
                <div><strong><?php esc_html_e('Total Quizzes', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $stats['total_quizzes']); ?></span></div>
                <div><strong><?php esc_html_e('Published', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $stats['published']); ?></span></div>
                <div><strong><?php esc_html_e('Draft', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $stats['draft']); ?></span></div>
                <div><strong><?php esc_html_e('Questions', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $stats['questions']); ?></span></div>
                <div><strong><?php esc_html_e('Attempts', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $stats['attempts']); ?></span></div>
                <div><strong><?php esc_html_e('Average Score', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html(number_format_i18n($stats['average_score'], 1)); ?>%</span></div>
            </div>
        </div>
        <?php
    }

    public static function settings(): void
    {
        echo '<div class="wrap"><h1>' . esc_html__('Settings', 'skill-kshetra-pro-quiz') . '</h1></div>';
    }
}
