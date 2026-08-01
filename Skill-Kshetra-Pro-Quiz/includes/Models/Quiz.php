<?php
namespace SkillKshetraProQuiz\Models;

use SkillKshetraProQuiz\Core\Database;

if (!defined('ABSPATH')) {
    exit;
}

final class Quiz
{
    public static function latest(): ?array
    {
        global $wpdb;
        $tables = Database::tables();
        $quiz = $wpdb->get_row("SELECT * FROM {$tables['quizzes']} WHERE status = 'published' ORDER BY id DESC LIMIT 1", ARRAY_A);
        return is_array($quiz) ? $quiz : null;
    }
}
