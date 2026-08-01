<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Database
{
    public static function tables(): array
    {
        global $wpdb;
        return [
            'quizzes' => $wpdb->prefix . 'sqp_quizzes',
            'questions' => $wpdb->prefix . 'sqp_questions',
            'answers' => $wpdb->prefix . 'sqp_answers',
            'attempts' => $wpdb->prefix . 'sqp_attempts',
            'results' => $wpdb->prefix . 'sqp_results',
            'categories' => $wpdb->prefix . 'sqp_categories',
            'tags' => $wpdb->prefix . 'sqp_tags',
            'bookmarks' => $wpdb->prefix . 'sqp_bookmarks',
            'logs' => $wpdb->prefix . 'sqp_logs',
        ];
    }

    public static function activate(): void
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();
        $t = self::tables();
        dbDelta("CREATE TABLE {$t['quizzes']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(190) NOT NULL, description LONGTEXT NULL, category_id BIGINT UNSIGNED NULL, difficulty VARCHAR(50) DEFAULT 'medium', passing_marks DECIMAL(5,2) DEFAULT 70, timer_minutes INT UNSIGNED DEFAULT 0, random_questions TINYINT(1) DEFAULT 0, random_options TINYINT(1) DEFAULT 0, negative_marks DECIMAL(5,2) DEFAULT 0, maximum_attempts INT UNSIGNED DEFAULT 0, status VARCHAR(20) DEFAULT 'draft', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id), KEY status (status), KEY category_id (category_id)) $charset;");
        dbDelta("CREATE TABLE {$t['questions']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, quiz_id BIGINT UNSIGNED NULL, question LONGTEXT NOT NULL, explanation LONGTEXT NULL, category_id BIGINT UNSIGNED NULL, difficulty VARCHAR(50) DEFAULT 'medium', reference_image VARCHAR(255) NULL, video_url VARCHAR(255) NULL, sort_order INT UNSIGNED DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id), KEY quiz_id (quiz_id), KEY category_id (category_id), KEY difficulty (difficulty)) $charset;");
        dbDelta("CREATE TABLE {$t['answers']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, question_id BIGINT UNSIGNED NOT NULL, answer_text LONGTEXT NOT NULL, is_correct TINYINT(1) DEFAULT 0, sort_order INT UNSIGNED DEFAULT 0, PRIMARY KEY (id), KEY question_id (question_id), KEY is_correct (is_correct)) $charset;");
        dbDelta("CREATE TABLE {$t['attempts']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, quiz_id BIGINT UNSIGNED NOT NULL, user_id BIGINT UNSIGNED NULL, guest_email VARCHAR(190) NULL, started_at DATETIME NOT NULL, finished_at DATETIME NULL, status VARCHAR(20) DEFAULT 'started', PRIMARY KEY (id), KEY quiz_id (quiz_id), KEY user_id (user_id)) $charset;");
        dbDelta("CREATE TABLE {$t['results']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, attempt_id BIGINT UNSIGNED NOT NULL, question_id BIGINT UNSIGNED NOT NULL, selected_answer_id BIGINT UNSIGNED NULL, correct_answer_id BIGINT UNSIGNED NULL, is_correct TINYINT(1) DEFAULT 0, score_delta DECIMAL(6,2) DEFAULT 0, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY attempt_id (attempt_id), KEY question_id (question_id), KEY is_correct (is_correct)) $charset;");
        dbDelta("CREATE TABLE {$t['categories']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(190) NOT NULL, slug VARCHAR(190) NOT NULL, PRIMARY KEY (id), UNIQUE KEY slug (slug)) $charset;");
        dbDelta("CREATE TABLE {$t['tags']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, name VARCHAR(190) NOT NULL, slug VARCHAR(190) NOT NULL, PRIMARY KEY (id), UNIQUE KEY slug (slug)) $charset;");
        dbDelta("CREATE TABLE {$t['bookmarks']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, quiz_id BIGINT UNSIGNED NULL, question_id BIGINT UNSIGNED NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY user_id (user_id)) $charset;");
        dbDelta("CREATE TABLE {$t['logs']} (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, object_type VARCHAR(50) NOT NULL, object_id BIGINT UNSIGNED NULL, message LONGTEXT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY object_type (object_type)) $charset;");
        add_option('sqp_db_version', SQP_VERSION);
    }

    public static function uninstall(): void
    {
        delete_option('sqp_db_version');
    }
}
