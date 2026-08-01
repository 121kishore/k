<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Question
{
    public static function for_quiz(int $quiz_id): array
    {
        global $wpdb;
        $t = Smart_Quiz_Pro_Database::tables();
        $quiz = $wpdb->get_row($wpdb->prepare("SELECT random_questions, random_options FROM {$t['quizzes']} WHERE id = %d", $quiz_id), ARRAY_A);
        $order = !empty($quiz['random_questions']) ? 'RAND()' : 'sort_order ASC, id ASC';
        $questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$t['questions']} WHERE quiz_id = %d ORDER BY $order", $quiz_id), ARRAY_A);
        foreach ($questions as &$question) {
            $answer_order = !empty($quiz['random_options']) ? 'RAND()' : 'sort_order ASC, id ASC';
            $question['answers'] = $wpdb->get_results($wpdb->prepare("SELECT id, answer_text, is_correct FROM {$t['answers']} WHERE question_id = %d ORDER BY $answer_order", (int) $question['id']), ARRAY_A);
        }
        return $questions;
    }
}
