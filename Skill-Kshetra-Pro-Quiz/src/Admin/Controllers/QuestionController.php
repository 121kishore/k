<?php
namespace SkillKshetraProQuiz\Admin\Controllers;

use SkillKshetraProQuiz\Admin\QuestionList;
use SkillKshetraProQuiz\Models\Question;
use SkillKshetraProQuiz\Models\Quiz;

if (!defined('ABSPATH')) { exit; }

final class QuestionController
{
    public static function index(): void
    {
        self::authorize();
        $filters = self::filters();
        $list_table = new QuestionList($filters);
        $list_table->prepare_items();
        $quizzes = Quiz::findAll(500, 1);
        include SKPQ_PLUGIN_DIR . 'src/Admin/Views/question-list.php';
    }

    public static function add(): void
    {
        self::authorize();
        $question = null;
        $quizzes = Quiz::findAll(500, 1);
        include SKPQ_PLUGIN_DIR . 'src/Admin/Views/question-form.php';
    }

    public static function edit(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        $question = $id ? Question::find($id) : null;
        if (!$question) { wp_die(esc_html__('Question not found.', 'skill-kshetra-pro-quiz')); }
        $quizzes = Quiz::findAll(500, 1);
        include SKPQ_PLUGIN_DIR . 'src/Admin/Views/question-form.php';
    }

    public static function save(): void
    {
        self::authorize();
        check_admin_referer('skpq_save_question');
        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        try {
            if ($id > 0) { Question::update($id, $_POST); } else { Question::create($_POST); }
            wp_safe_redirect(admin_url('admin.php?page=skpq-questions&skpq_message=saved'));
        } catch (\InvalidArgumentException $exception) {
            wp_safe_redirect(add_query_arg('skpq_error', 'validation', wp_get_referer() ?: admin_url('admin.php?page=skpq-add-question')));
        }
        exit;
    }

    public static function delete(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        check_admin_referer('skpq_delete_question_' . $id);
        if ($id) { Question::delete($id); }
        wp_safe_redirect(admin_url('admin.php?page=skpq-questions&skpq_message=deleted'));
        exit;
    }

    public static function duplicate(): void
    {
        self::authorize();
        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        check_admin_referer('skpq_duplicate_question_' . $id);
        if ($id) { Question::duplicate($id); }
        wp_safe_redirect(admin_url('admin.php?page=skpq-questions&skpq_message=duplicated'));
        exit;
    }

    private static function filters(): array
    {
        return [
            's' => isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '',
            'quiz_id' => isset($_GET['quiz_id']) ? absint($_GET['quiz_id']) : 0,
            'difficulty' => isset($_GET['difficulty']) ? sanitize_text_field(wp_unslash($_GET['difficulty'])) : '',
            'status' => isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '',
        ];
    }

    private static function authorize(): void
    {
        if (!current_user_can('manage_options')) { wp_die(esc_html__('You do not have permission to manage questions.', 'skill-kshetra-pro-quiz')); }
    }
}
