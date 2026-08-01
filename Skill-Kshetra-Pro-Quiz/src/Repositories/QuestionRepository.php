<?php
namespace SkillKshetraProQuiz\Repositories;

use SkillKshetraProQuiz\Core\Database;

if (!defined('ABSPATH')) { exit; }

final class QuestionRepository
{
    public function create(array $question, array $options): int
    {
        global $wpdb;
        $tables = Database::tables();
        $wpdb->insert($tables['questions'], $question);
        $question_id = (int) $wpdb->insert_id;
        $this->replace_options($question_id, $options);
        return $question_id;
    }

    public function update(int $id, array $question, array $options): bool
    {
        global $wpdb;
        $tables = Database::tables();
        $updated = false !== $wpdb->update($tables['questions'], $question, ['id' => $id]);
        $this->replace_options($id, $options);
        return $updated;
    }

    public function delete(int $id): bool
    {
        global $wpdb;
        $tables = Database::tables();
        $wpdb->delete($tables['options'], ['question_id' => $id]);
        return false !== $wpdb->delete($tables['questions'], ['id' => $id]);
    }

    public function find(int $id): ?array
    {
        global $wpdb;
        $tables = Database::tables();
        $question = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $tables['questions'] . ' WHERE id = %d', $id), ARRAY_A);
        if (!is_array($question)) { return null; }
        $question['options'] = $this->options_for_question($id);
        return $question;
    }

    public function findAll(array $filters = [], int $per_page = 20, int $page = 1): array
    {
        global $wpdb;
        $tables = Database::tables();
        [$where, $args] = $this->where($filters);
        $offset = max(0, ($page - 1) * $per_page);
        $sql = 'SELECT q.*, z.title AS quiz_title FROM ' . $tables['questions'] . ' q LEFT JOIN ' . $tables['quizzes'] . ' z ON z.id = q.quiz_id ' . $where . ' ORDER BY q.created_at DESC, q.id DESC LIMIT %d OFFSET %d';
        $args[] = $per_page;
        $args[] = $offset;
        return $wpdb->get_results($wpdb->prepare($sql, $args), ARRAY_A);
    }

    public function count(array $filters = []): int
    {
        global $wpdb;
        $tables = Database::tables();
        [$where, $args] = $this->where($filters);
        $sql = 'SELECT COUNT(*) FROM ' . $tables['questions'] . ' q ' . $where;
        return $args ? (int) $wpdb->get_var($wpdb->prepare($sql, $args)) : (int) $wpdb->get_var($sql);
    }

    public function duplicate(int $id): int
    {
        $question = $this->find($id);
        if (!$question) { return 0; }
        $options = $question['options'];
        unset($question['id'], $question['options']);
        $question['question'] = sprintf(__('%s Copy', 'skill-kshetra-pro-quiz'), $question['question']);
        $question['created_at'] = current_time('mysql');
        $question['updated_at'] = current_time('mysql');
        return $this->create($question, $options);
    }

    public function options_for_question(int $question_id): array
    {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . Database::tables()['options'] . ' WHERE question_id = %d ORDER BY sort_order ASC, id ASC', $question_id), ARRAY_A);
    }

    private function replace_options(int $question_id, array $options): void
    {
        global $wpdb;
        $tables = Database::tables();
        $wpdb->delete($tables['options'], ['question_id' => $question_id]);
        foreach ($options as $index => $option) {
            $wpdb->insert($tables['options'], [
                'question_id' => $question_id,
                'option_text' => $option['option_text'],
                'is_correct' => $option['is_correct'],
                'sort_order' => $index,
            ]);
        }
    }

    private function where(array $filters): array
    {
        global $wpdb;
        $where = [];
        $args = [];
        if (!empty($filters['s'])) {
            $where[] = 'q.question LIKE %s';
            $args[] = '%' . $wpdb->esc_like($filters['s']) . '%';
        }
        if (!empty($filters['quiz_id'])) {
            $where[] = 'q.quiz_id = %d';
            $args[] = absint($filters['quiz_id']);
        }
        if (!empty($filters['difficulty']) && in_array($filters['difficulty'], ['easy', 'medium', 'hard'], true)) {
            $where[] = 'q.difficulty = %s';
            $args[] = $filters['difficulty'];
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $where[] = 'q.status = %d';
            $args[] = absint($filters['status']);
        }
        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $args];
    }
}
