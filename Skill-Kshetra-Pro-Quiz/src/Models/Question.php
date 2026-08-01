<?php
namespace SkillKshetraProQuiz\Models;

use SkillKshetraProQuiz\Core\Database;

if (!defined('ABSPATH')) {
    exit;
}

final class Question
{
    public static function for_quiz(int $quiz_id): array
    {
        global $wpdb;
        $tables = Database::tables();
        $questions = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $tables['questions'] . ' WHERE quiz_id = %d ORDER BY sort_order ASC, id ASC', $quiz_id), ARRAY_A);
        foreach ($questions as &$question) {
            $question['answers'] = $wpdb->get_results($wpdb->prepare('SELECT answer_text, is_correct FROM ' . $tables['answers'] . ' WHERE question_id = %d ORDER BY sort_order ASC, id ASC', (int) $question['id']), ARRAY_A);
        }
        return $questions;
    }
}
