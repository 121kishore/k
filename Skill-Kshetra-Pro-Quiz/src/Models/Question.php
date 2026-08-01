<?php
namespace SkillKshetraProQuiz\Models;

use SkillKshetraProQuiz\Repositories\QuestionRepository;
use SkillKshetraProQuiz\Services\QuestionService;

if (!defined('ABSPATH')) { exit; }

final class Question
{
    public static function create(array $data): int { return (new QuestionService())->create($data); }
    public static function update(int $id, array $data): bool { return (new QuestionService())->update($id, $data); }
    public static function delete(int $id): bool { return (new QuestionRepository())->delete($id); }
    public static function find(int $id): ?array { return (new QuestionRepository())->find($id); }
    public static function findAll(array $filters = [], int $per_page = 20, int $page = 1): array { return (new QuestionRepository())->findAll($filters, $per_page, $page); }
    public static function duplicate(int $id): int { return (new QuestionService())->duplicate($id); }
    public static function count(array $filters = []): int { return (new QuestionRepository())->count($filters); }
    public static function for_quiz(int $quiz_id): array
    {
        $questions = (new QuestionRepository())->findAll(['quiz_id' => $quiz_id, 'status' => 1], 500, 1);
        $repository = new QuestionRepository();
        foreach ($questions as &$question) {
            $question['options'] = $repository->options_for_question((int) $question['id']);
        }
        return $questions;
    }
}
