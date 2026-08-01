<?php
namespace SkillKshetraProQuiz\Services;

use SkillKshetraProQuiz\Repositories\QuestionRepository;

if (!defined('ABSPATH')) { exit; }

final class QuestionService
{
    private QuestionRepository $repository;

    public function __construct(?QuestionRepository $repository = null)
    {
        $this->repository = $repository ?: new QuestionRepository();
    }

    public function create(array $data): int
    {
        [$question, $options] = $this->sanitize_and_validate($data);
        $question['created_at'] = current_time('mysql');
        $question['updated_at'] = current_time('mysql');
        return $this->repository->create($question, $options);
    }

    public function update(int $id, array $data): bool
    {
        [$question, $options] = $this->sanitize_and_validate($data);
        $question['updated_at'] = current_time('mysql');
        return $this->repository->update($id, $question, $options);
    }

    public function duplicate(int $id): int
    {
        return $this->repository->duplicate($id);
    }

    private function sanitize_and_validate(array $data): array
    {
        $quiz_id = absint($data['quiz_id'] ?? 0);
        $question_text = isset($data['question']) ? sanitize_textarea_field(wp_unslash($data['question'])) : '';
        $difficulty = isset($data['difficulty']) && in_array($data['difficulty'], ['easy', 'medium', 'hard'], true) ? $data['difficulty'] : 'medium';
        $status = isset($data['status']) ? absint($data['status']) : 1;
        $correct = isset($data['correct_answer']) ? absint($data['correct_answer']) : 0;
        $raw_options = isset($data['options']) && is_array($data['options']) ? wp_unslash($data['options']) : [];
        $options = [];
        foreach (array_slice($raw_options, 0, 6) as $index => $option) {
            $text = sanitize_textarea_field($option);
            if ($text === '') { continue; }
            $options[] = ['option_text' => $text, 'is_correct' => $index === $correct ? 1 : 0];
        }
        $correct_count = array_sum(array_column($options, 'is_correct'));
        if ($quiz_id <= 0 || $question_text === '' || count($options) < 2 || count($options) > 6 || $correct_count !== 1) {
            throw new \InvalidArgumentException('invalid_question');
        }
        return [[
            'quiz_id' => $quiz_id,
            'question' => $question_text,
            'explanation' => isset($data['explanation']) ? sanitize_textarea_field(wp_unslash($data['explanation'])) : '',
            'explanation_image' => isset($data['explanation_image']) ? esc_url_raw(wp_unslash($data['explanation_image'])) : '',
            'explanation_video' => isset($data['explanation_video']) ? esc_url_raw(wp_unslash($data['explanation_video'])) : '',
            'difficulty' => $difficulty,
            'points' => max(1, absint($data['points'] ?? 1)),
            'status' => $status ? 1 : 0,
        ], $options];
    }
}
