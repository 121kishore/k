<?php
namespace SkillKshetraProQuiz\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class Database
{
    public static function tables(): array
    {
        global $wpdb;
        return [
            'quizzes' => $wpdb->prefix . 'skpq_quizzes',
            'questions' => $wpdb->prefix . 'skpq_questions',
            'answers' => $wpdb->prefix . 'skpq_answers',
            'attempts' => $wpdb->prefix . 'skpq_attempts',
            'results' => $wpdb->prefix . 'skpq_results',
            'categories' => $wpdb->prefix . 'skpq_categories',
            'logs' => $wpdb->prefix . 'skpq_logs',
        ];
    }

    public static function create_tables(): void
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();
        $tables = self::tables();
        dbDelta("CREATE TABLE {$tables['quizzes']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, title VARCHAR(190) NOT NULL, slug VARCHAR(190) NOT NULL, description LONGTEXT NULL, mode VARCHAR(20) DEFAULT 'learn', timer INT UNSIGNED DEFAULT 0, passing_score TINYINT UNSIGNED DEFAULT 70, status VARCHAR(20) DEFAULT 'draft', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id), UNIQUE KEY slug (slug), KEY status (status), KEY mode (mode)) $charset;");
        dbDelta("CREATE TABLE {$tables['questions']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, quiz_id BIGINT UNSIGNED NULL, question LONGTEXT NOT NULL, explanation LONGTEXT NULL, category_id BIGINT UNSIGNED NULL, difficulty VARCHAR(50) DEFAULT 'medium', reference_image VARCHAR(255) NULL, video_url VARCHAR(255) NULL, sort_order INT UNSIGNED DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id), KEY quiz_id (quiz_id)) $charset;");
        dbDelta("CREATE TABLE {$tables['answers']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, question_id BIGINT UNSIGNED NOT NULL, answer_text LONGTEXT NOT NULL, is_correct TINYINT(1) DEFAULT 0, sort_order INT UNSIGNED DEFAULT 0, PRIMARY KEY (id), KEY question_id (question_id)) $charset;");
        dbDelta("CREATE TABLE {$tables['attempts']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, quiz_id BIGINT UNSIGNED NOT NULL, user_id BIGINT UNSIGNED NULL, started_at DATETIME NOT NULL, finished_at DATETIME NULL, status VARCHAR(20) DEFAULT 'started', PRIMARY KEY (id), KEY quiz_id (quiz_id)) $charset;");
        dbDelta("CREATE TABLE {$tables['results']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, attempt_id BIGINT UNSIGNED NOT NULL, question_id BIGINT UNSIGNED NOT NULL, selected_answer_id BIGINT UNSIGNED NULL, correct_answer_id BIGINT UNSIGNED NULL, is_correct TINYINT(1) DEFAULT 0, score DECIMAL(6,2) DEFAULT 0, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY attempt_id (attempt_id)) $charset;");
        dbDelta("CREATE TABLE {$tables['categories']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(190) NOT NULL, slug VARCHAR(190) NOT NULL, PRIMARY KEY (id), UNIQUE KEY slug (slug)) $charset;");
        dbDelta("CREATE TABLE {$tables['logs']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, object_type VARCHAR(50) NOT NULL, object_id BIGINT UNSIGNED NULL, message LONGTEXT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY object_type (object_type)) $charset;");
    }

    public static function uninstall(): void
    {
        delete_option('skpq_version');
    }
}
