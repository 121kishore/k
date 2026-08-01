<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Result
{
    public static function analytics(): array
    {
        global $wpdb;
        $t = Smart_Quiz_Pro_Database::tables();
        return [
            'attempts' => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$t['attempts']}"),
            'completion_rate' => (float) $wpdb->get_var("SELECT AVG(status = 'finished') * 100 FROM {$t['attempts']}"),
            'average_score' => (float) $wpdb->get_var("SELECT AVG(is_correct) * 100 FROM {$t['results']}"),
            'most_missed' => $wpdb->get_results("SELECT question_id, COUNT(*) misses FROM {$t['results']} WHERE is_correct = 0 GROUP BY question_id ORDER BY misses DESC LIMIT 10", ARRAY_A),
        ];
    }
}
