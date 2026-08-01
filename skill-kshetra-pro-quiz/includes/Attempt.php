<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Attempt
{
    public static function create(int $quiz_id, int $user_id = 0, string $guest_email = ''): int
    {
        global $wpdb;
        $t = Smart_Quiz_Pro_Database::tables();
        $wpdb->insert($t['attempts'], [
            'quiz_id' => $quiz_id,
            'user_id' => $user_id ?: null,
            'guest_email' => $guest_email ?: null,
            'started_at' => current_time('mysql'),
            'status' => 'started',
        ]);
        return (int) $wpdb->insert_id;
    }
}
