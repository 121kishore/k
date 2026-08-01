<?php
namespace SkillKshetraProQuiz\Frontend;

use SkillKshetraProQuiz\Models\Question;
use SkillKshetraProQuiz\Models\Quiz;

if (!defined('ABSPATH')) {
    exit;
}

final class Shortcodes
{
    public function register(): void
    {
        add_shortcode('smartquiz', [$this, 'render']);
    }

    public function render(array $atts): string
    {
        wp_enqueue_style('skpq-frontend');
        wp_enqueue_script('skpq-frontend');
        $quiz = Quiz::latest();
        if (!$quiz) {
            return '<p>' . esc_html__('No quiz found.', 'skill-kshetra-pro-quiz') . '</p>';
        }
        $questions = Question::for_quiz((int) $quiz['id']);
        ob_start();
        include SKPQ_PLUGIN_DIR . 'includes/Frontend/quiz-template.php';
        return (string) ob_get_clean();
    }
}
