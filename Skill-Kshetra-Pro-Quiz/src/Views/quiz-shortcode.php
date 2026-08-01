<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="skpq-quiz">
    <h2><?php echo esc_html($quiz['title']); ?></h2>
    <?php foreach ($questions as $index => $question) : ?>
        <section class="skpq-question<?php echo $index === 0 ? ' is-active' : ''; ?>" data-explanation="<?php echo esc_attr($question['explanation']); ?>">
            <p><?php echo esc_html(sprintf(__('Question %1$d / %2$d', 'skill-kshetra-pro-quiz'), $index + 1, count($questions))); ?></p>
            <h3><?php echo esc_html($question['question']); ?></h3>
            <?php foreach ($question['options'] as $answer) : ?>
                <button type="button" class="skpq-option" data-correct="<?php echo esc_attr((string) (int) $answer['is_correct']); ?>"><?php echo esc_html($answer['option_text']); ?></button>
            <?php endforeach; ?>
            <div class="skpq-feedback" aria-live="polite"></div>
        </section>
    <?php endforeach; ?>
</div>
