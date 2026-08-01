<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrap sqp-admin-wrap">
    <h1><?php esc_html_e('CSV Import', 'skill-kshetra-pro-quiz'); ?></h1>
    <p><?php esc_html_e('Bulk import thousands of questions with columns: Question, Option A, Option B, Option C, Option D, Correct, Explanation, Category, Difficulty.', 'skill-kshetra-pro-quiz'); ?></p>
    <input type="file" accept=".csv" /> <button class="button button-primary"><?php esc_html_e('Import CSV', 'skill-kshetra-pro-quiz'); ?></button>
</div>
