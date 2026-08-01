<?php if (!defined('ABSPATH')) { exit; } $analytics = Smart_Quiz_Pro_Result::analytics(); ?>
<div class="wrap sqp-admin-wrap">
    <h1><?php esc_html_e('Skill Kshetra Quiz Dashboard', 'skill-kshetra-pro-quiz'); ?></h1>
    <div class="sqp-cards">
        <div><strong><?php esc_html_e('Daily Attempts', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html((string) $analytics['attempts']); ?></span></div>
        <div><strong><?php esc_html_e('Average Scores', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html(number_format_i18n($analytics['average_score'], 1)); ?>%</span></div>
        <div><strong><?php esc_html_e('Completion Rate', 'skill-kshetra-pro-quiz'); ?></strong><span><?php echo esc_html(number_format_i18n($analytics['completion_rate'], 1)); ?>%</span></div>
        <div><strong><?php esc_html_e('Pass %', 'skill-kshetra-pro-quiz'); ?></strong><span>—</span></div>
    </div>
    <h2><?php esc_html_e('Most Missed Questions', 'skill-kshetra-pro-quiz'); ?></h2>
    <pre><?php echo esc_html(wp_json_encode($analytics['most_missed'], JSON_PRETTY_PRINT)); ?></pre>
</div>
