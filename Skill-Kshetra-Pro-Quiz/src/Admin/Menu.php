<?php
namespace SkillKshetraProQuiz\Admin;

use SkillKshetraProQuiz\Admin\Controllers\QuestionController;

if (!defined('ABSPATH')) {
    exit;
}

final class Menu
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'add_pages']);
        add_action('admin_post_skpq_save_quiz', [QuizController::class, 'save']);
        add_action('admin_post_skpq_delete_quiz', [QuizController::class, 'delete']);
        add_action('admin_post_skpq_duplicate_quiz', [QuizController::class, 'duplicate']);
        add_action('admin_post_skpq_save_question', [QuestionController::class, 'save']);
        add_action('admin_post_skpq_delete_question', [QuestionController::class, 'delete']);
        add_action('admin_post_skpq_duplicate_question', [QuestionController::class, 'duplicate']);
    }

    public function add_pages(): void
    {
        add_menu_page(__('Skill Kshetra Pro Quiz', 'skill-kshetra-pro-quiz'), __('Skill Kshetra Quiz', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-dashboard', [Dashboard::class, 'render'], 'dashicons-welcome-learn-more', 26);
        add_submenu_page('skpq-dashboard', __('Dashboard', 'skill-kshetra-pro-quiz'), __('Dashboard', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-dashboard', [Dashboard::class, 'render']);
        add_submenu_page('skpq-dashboard', __('Quizzes', 'skill-kshetra-pro-quiz'), __('Quizzes', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-quizzes', [QuizController::class, 'index']);
        add_submenu_page('skpq-dashboard', __('Add Quiz', 'skill-kshetra-pro-quiz'), __('+ Add Quiz', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-add-quiz', [QuizController::class, 'add']);
        add_submenu_page(null, __('Edit Quiz', 'skill-kshetra-pro-quiz'), __('Edit Quiz', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-edit-quiz', [QuizController::class, 'edit']);
        add_submenu_page('skpq-dashboard', __('Questions', 'skill-kshetra-pro-quiz'), __('Questions', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-questions', [QuestionController::class, 'index']);
        add_submenu_page('skpq-dashboard', __('Add Question', 'skill-kshetra-pro-quiz'), __('➕ Add Question', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-add-question', [QuestionController::class, 'add']);
        add_submenu_page(null, __('Edit Question', 'skill-kshetra-pro-quiz'), __('Edit Question', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-edit-question', [QuestionController::class, 'edit']);
        add_submenu_page('skpq-dashboard', __('Categories', 'skill-kshetra-pro-quiz'), __('Categories', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-categories', [Dashboard::class, 'categories']);
        add_submenu_page('skpq-dashboard', __('Settings', 'skill-kshetra-pro-quiz'), __('Settings', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-settings', [Dashboard::class, 'settings']);
    }
}
