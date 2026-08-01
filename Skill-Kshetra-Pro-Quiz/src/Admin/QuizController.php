<?php
namespace SkillKshetraProQuiz\Admin;

use SkillKshetraProQuiz\Models\Quiz;

if (!defined('ABSPATH')) {
    exit;
}

final class QuizController
{
    public static function index(): void
    {
        self::authorize();
        $list_table = new QuizList();
        $list_table->prepare_items();
        include SKPQ_PLUGIN_DIR . 'src/Views/quiz-list.php';
    }

    public static function add(): void
    {
        self::authorize();
        $quiz = null;
        $action = admin_url('admin-post.php');
        include SKPQ_PLUGIN_DIR . 'src/Views/quiz-form.php';
    }

    public static function edit(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        $quiz = $id ? Quiz::find($id) : null;
        if (!$quiz) {
            wp_die(esc_html__('Quiz not found.', 'skill-kshetra-pro-quiz'));
        }
        $action = admin_url('admin-post.php');
        include SKPQ_PLUGIN_DIR . 'src/Views/quiz-form.php';
    }

    public static function save(): void
    {
        self::authorize();
        check_admin_referer('skpq_save_quiz');
        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $passing_score = isset($_POST['passing_score']) ? absint($_POST['passing_score']) : 70;
        if ($title === '' || $passing_score > 100) {
            wp_safe_redirect(add_query_arg('skpq_error', 'validation', wp_get_referer() ?: admin_url('admin.php?page=skpq-add-quiz')));
            exit;
        }
        if ($id > 0) {
            Quiz::update($id, $_POST);
        } else {
            $id = Quiz::create($_POST);
        }
        wp_safe_redirect(admin_url('admin.php?page=skpq-quizzes&skpq_message=saved'));
        exit;
    }

    public static function delete(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        check_admin_referer('skpq_delete_quiz_' . $id);
        if ($id) {
            Quiz::delete($id);
        }
        wp_safe_redirect(admin_url('admin.php?page=skpq-quizzes&skpq_message=deleted'));
        exit;
    }

    public static function duplicate(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        check_admin_referer('skpq_duplicate_quiz_' . $id);
        $quiz = $id ? Quiz::find($id) : null;
        if ($quiz) {
            unset($quiz['id']);
            $quiz['title'] = sprintf(__('%s Copy', 'skill-kshetra-pro-quiz'), $quiz['title']);
            $quiz['slug'] = $quiz['slug'] . '-copy';
            Quiz::create($quiz);
        }
        wp_safe_redirect(admin_url('admin.php?page=skpq-quizzes&skpq_message=duplicated'));
        exit;
    }

    private static function authorize(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to manage quizzes.', 'skill-kshetra-pro-quiz'));
        }
    }
}
