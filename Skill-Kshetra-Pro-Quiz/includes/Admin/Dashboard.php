<?php
namespace SkillKshetraProQuiz\Admin;

if (!defined('ABSPATH')) {
    exit;
}

final class Dashboard
{
    public static function render(): void
    {
        ?>
        <div class="wrap skpq-admin-wrap">
            <h1><?php esc_html_e('Skill Kshetra Pro Quiz', 'skill-kshetra-pro-quiz'); ?></h1>
            <p><?php esc_html_e('Quiz management foundation is loaded with Core, Admin, Models, and Frontend modules.', 'skill-kshetra-pro-quiz'); ?></p>
            <div class="skpq-cards">
                <div><?php esc_html_e('Create Quiz', 'skill-kshetra-pro-quiz'); ?></div>
                <div><?php esc_html_e('Question Bank', 'skill-kshetra-pro-quiz'); ?></div>
                <div><?php esc_html_e('CSV Import', 'skill-kshetra-pro-quiz'); ?></div>
                <div><?php esc_html_e('Analytics', 'skill-kshetra-pro-quiz'); ?></div>
            </div>
        </div>
        <?php
    }
}
