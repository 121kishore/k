<?php
namespace SkillKshetraProQuiz\Models;

use SkillKshetraProQuiz\Core\Database;

if (!defined('ABSPATH')) {
    exit;
}

final class Quiz
{
    public int $id = 0;
    public string $title = '';
    public string $slug = '';
    public string $description = '';
    public string $mode = 'learn';
    public int $timer = 0;
    public int $passing_score = 70;
    public string $status = 'draft';

    public static function create(array $data): int
    {
        global $wpdb;
        $payload = self::sanitize($data);
        $payload['created_at'] = current_time('mysql');
        $payload['updated_at'] = current_time('mysql');
        $wpdb->insert(Database::tables()['quizzes'], $payload);
        return (int) $wpdb->insert_id;
    }

    public static function update(int $id, array $data): bool
    {
        global $wpdb;
        $payload = self::sanitize($data, $id);
        $payload['updated_at'] = current_time('mysql');
        return false !== $wpdb->update(Database::tables()['quizzes'], $payload, ['id' => $id]);
    }

    public static function delete(int $id): bool
    {
        global $wpdb;
        return false !== $wpdb->delete(Database::tables()['quizzes'], ['id' => $id]);
    }

    public static function find(int $id): ?array
    {
        global $wpdb;
        $quiz = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . Database::tables()['quizzes'] . ' WHERE id = %d', $id), ARRAY_A);
        return is_array($quiz) ? $quiz : null;
    }

    public static function findAll(int $per_page = 20, int $page = 1): array
    {
        global $wpdb;
        $offset = max(0, ($page - 1) * $per_page);
        return $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . Database::tables()['quizzes'] . ' ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d', $per_page, $offset), ARRAY_A);
    }

    public static function exists(string $slug, int $exclude_id = 0): bool
    {
        global $wpdb;
        $sql = 'SELECT COUNT(*) FROM ' . Database::tables()['quizzes'] . ' WHERE slug = %s';
        $args = [$slug];
        if ($exclude_id > 0) {
            $sql .= ' AND id != %d';
            $args[] = $exclude_id;
        }
        return (int) $wpdb->get_var($wpdb->prepare($sql, $args)) > 0;
    }

    public static function count(string $status = ''): int
    {
        global $wpdb;
        if ($status !== '') {
            return (int) $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . Database::tables()['quizzes'] . ' WHERE status = %s', $status));
        }
        return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . Database::tables()['quizzes']);
    }


    public static function questions_count(int $quiz_id): int
    {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . Database::tables()['questions'] . ' WHERE quiz_id = %d', $quiz_id));
    }

    public static function attempts_count(int $quiz_id): int
    {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . Database::tables()['attempts'] . ' WHERE quiz_id = %d', $quiz_id));
    }

    public static function dashboard_stats(): array
    {
        global $wpdb;
        $tables = Database::tables();
        return [
            'total_quizzes' => self::count(),
            'published' => self::count('published'),
            'draft' => self::count('draft'),
            'questions' => (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $tables['questions']),
            'attempts' => (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $tables['attempts']),
            'average_score' => (float) $wpdb->get_var('SELECT AVG(score) FROM ' . $tables['results']),
        ];
    }

    public static function latest(): ?array
    {
        global $wpdb;
        $quiz = $wpdb->get_row("SELECT * FROM " . Database::tables()['quizzes'] . " WHERE status = 'published' ORDER BY id DESC LIMIT 1", ARRAY_A);
        return is_array($quiz) ? $quiz : null;
    }

    public static function make_unique_slug(string $slug, int $exclude_id = 0): string
    {
        $base = sanitize_title($slug) ?: 'quiz';
        $candidate = $base;
        $suffix = 2;
        while (self::exists($candidate, $exclude_id)) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }
        return $candidate;
    }

    private static function sanitize(array $data, int $exclude_id = 0): array
    {
        $title = isset($data['title']) ? sanitize_text_field(wp_unslash($data['title'])) : '';
        $slug_source = isset($data['slug']) && $data['slug'] !== '' ? $data['slug'] : $title;
        $mode = isset($data['mode']) && in_array($data['mode'], ['learn', 'exam'], true) ? $data['mode'] : 'learn';
        $status = isset($data['status']) && in_array($data['status'], ['draft', 'published'], true) ? $data['status'] : 'draft';
        return [
            'title' => $title,
            'slug' => self::make_unique_slug(sanitize_text_field(wp_unslash($slug_source)), $exclude_id),
            'description' => isset($data['description']) ? sanitize_textarea_field(wp_unslash($data['description'])) : '',
            'mode' => $mode,
            'timer' => max(0, absint($data['timer'] ?? 0)),
            'passing_score' => min(100, max(0, absint($data['passing_score'] ?? 70))),
            'status' => $status,
        ];
    }
}
