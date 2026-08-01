<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Quiz
{
    public static function resolve_for_shortcode(array $atts): ?array
    {
        global $wpdb;
        $t = Smart_Quiz_Pro_Database::tables();
        if (!empty($atts['id'])) {
            $quiz = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t['quizzes']} WHERE id = %d AND status = 'published'", absint($atts['id'])), ARRAY_A);
        } elseif (!empty($atts['random'])) {
            $quiz = $wpdb->get_row("SELECT * FROM {$t['quizzes']} WHERE status = 'published' ORDER BY RAND() LIMIT 1", ARRAY_A);
        } else {
            $quiz = $wpdb->get_row("SELECT * FROM {$t['quizzes']} WHERE status = 'published' ORDER BY id DESC LIMIT 1", ARRAY_A);
        }
        return is_array($quiz) ? $quiz : null;
    }

    public static function recent(int $limit = 20): array
    {
        global $wpdb;
        $t = Smart_Quiz_Pro_Database::tables();
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$t['quizzes']} ORDER BY id DESC LIMIT %d", $limit), ARRAY_A);
    }
}
