<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="sqp-quiz" data-quiz-id="<?php echo esc_attr((string) $quiz['id']); ?>" data-timer="<?php echo esc_attr((string) $quiz['timer_minutes']); ?>">
    <header class="sqp-quiz__header">
        <h2><?php echo esc_html($quiz['name']); ?></h2>
        <p><?php echo esc_html($quiz['description']); ?></p>
        <div class="sqp-progress"><span style="width:0%"></span></div>
        <strong class="sqp-progress-label">0%</strong>
    </header>
    <?php foreach ($questions as $index => $question) :
        $correct = array_values(array_filter($question['answers'], static fn($answer) => (int) $answer['is_correct'] === 1));
        $correct_answer = $correct[0]['answer_text'] ?? '';
    ?>
        <section class="sqp-question<?php echo $index === 0 ? ' is-active' : ''; ?>" data-question-index="<?php echo esc_attr((string) $index); ?>" data-correct-answer="<?php echo esc_attr($correct_answer); ?>" data-explanation="<?php echo esc_attr($question['explanation']); ?>">
            <p class="sqp-question-count"><?php echo esc_html(sprintf(__('Question %1$d / %2$d', 'skill-kshetra-pro-quiz'), $index + 1, count($questions))); ?></p>
            <h3><?php echo esc_html($question['question']); ?></h3>
            <?php if (!empty($question['reference_image'])) : ?><img class="sqp-reference" src="<?php echo esc_url($question['reference_image']); ?>" alt="" /><?php endif; ?>
            <?php if (!empty($question['video_url'])) : ?><p><a href="<?php echo esc_url($question['video_url']); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e('Watch reference video', 'skill-kshetra-pro-quiz'); ?></a></p><?php endif; ?>
            <div class="sqp-options">
                <?php foreach ($question['answers'] as $answer) : ?>
                    <button type="button" class="sqp-option" data-correct="<?php echo esc_attr((string) (int) $answer['is_correct']); ?>"><?php echo esc_html($answer['answer_text']); ?></button>
                <?php endforeach; ?>
            </div>
            <div class="sqp-feedback" aria-live="polite"></div>
            <button type="button" class="sqp-next" hidden><?php esc_html_e('Next Question', 'skill-kshetra-pro-quiz'); ?></button>
        </section>
    <?php endforeach; ?>
    <section class="sqp-result" hidden>
        <h2><?php esc_html_e('Congratulations', 'skill-kshetra-pro-quiz'); ?></h2>
        <p><strong><?php esc_html_e('Score', 'skill-kshetra-pro-quiz'); ?>:</strong> <span data-sqp-score></span></p>
        <p><strong><?php esc_html_e('Correct', 'skill-kshetra-pro-quiz'); ?>:</strong> <span data-sqp-correct></span></p>
        <p><strong><?php esc_html_e('Wrong', 'skill-kshetra-pro-quiz'); ?>:</strong> <span data-sqp-wrong></span></p>
        <p><strong><?php esc_html_e('Rank', 'skill-kshetra-pro-quiz'); ?>:</strong> <span data-sqp-rank></span></p>
        <button type="button" data-sqp-retry><?php esc_html_e('Retry', 'skill-kshetra-pro-quiz'); ?></button>
        <button type="button" data-sqp-review><?php esc_html_e('Review Answers', 'skill-kshetra-pro-quiz'); ?></button>
        <button type="button" data-sqp-pdf><?php esc_html_e('Download PDF', 'skill-kshetra-pro-quiz'); ?></button>
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'skill-kshetra-pro-quiz'); ?></a>
    </section>
    <section class="sqp-review" hidden></section>
</div>
