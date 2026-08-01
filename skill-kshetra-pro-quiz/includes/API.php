<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_API
{
    public function register_ajax_routes(): void
    {
        add_action('wp_ajax_sqp_start_attempt', [$this, 'start_attempt']);
        add_action('wp_ajax_nopriv_sqp_start_attempt', [$this, 'start_attempt']);
    }

    public function start_attempt(): void
    {
        check_ajax_referer('sqp_public', 'nonce');
        $quiz_id = isset($_POST['quiz_id']) ? absint($_POST['quiz_id']) : 0;
        if (!$quiz_id) {
            wp_send_json_error(['message' => __('Missing quiz.', 'skill-kshetra-pro-quiz')], 400);
        }
        $attempt_id = Smart_Quiz_Pro_Attempt::create($quiz_id, get_current_user_id());
        wp_send_json_success(['attempt_id' => $attempt_id]);
    }
}
