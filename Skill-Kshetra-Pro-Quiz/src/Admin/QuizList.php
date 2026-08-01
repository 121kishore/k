<?php
namespace SkillKshetraProQuiz\Admin;

use SkillKshetraProQuiz\Models\Quiz;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

final class QuizList extends \WP_List_Table
{
    public function get_columns(): array
    {
        return [
            'title' => __('Title', 'skill-kshetra-pro-quiz'),
            'mode' => __('Mode', 'skill-kshetra-pro-quiz'),
            'questions' => __('Questions', 'skill-kshetra-pro-quiz'),
            'attempts' => __('Attempts', 'skill-kshetra-pro-quiz'),
            'status' => __('Status', 'skill-kshetra-pro-quiz'),
            'created_at' => __('Created', 'skill-kshetra-pro-quiz'),
        ];
    }

    public function prepare_items(): void
    {
        $per_page = 20;
        $page = $this->get_pagenum();
        $this->_column_headers = [$this->get_columns(), [], []];
        $this->items = Quiz::findAll($per_page, $page);
        $this->set_pagination_args(['total_items' => Quiz::count(), 'per_page' => $per_page]);
    }

    public function column_default($item, $column_name): string
    {
        return esc_html((string) ($item[$column_name] ?? ''));
    }

    public function column_title($item): string
    {
        $id = (int) $item['id'];
        $edit = admin_url('admin.php?page=skpq-edit-quiz&id=' . $id);
        $delete = wp_nonce_url(admin_url('admin-post.php?action=skpq_delete_quiz&id=' . $id), 'skpq_delete_quiz_' . $id);
        $duplicate = wp_nonce_url(admin_url('admin-post.php?action=skpq_duplicate_quiz&id=' . $id), 'skpq_duplicate_quiz_' . $id);
        $actions = [
            'edit' => '<a href="' . esc_url($edit) . '">' . esc_html__('Edit', 'skill-kshetra-pro-quiz') . '</a>',
            'delete' => '<a href="' . esc_url($delete) . '" onclick="return confirm(\'' . esc_js(__('Delete this quiz?', 'skill-kshetra-pro-quiz')) . '\')">' . esc_html__('Delete', 'skill-kshetra-pro-quiz') . '</a>',
            'duplicate' => '<a href="' . esc_url($duplicate) . '">' . esc_html__('Duplicate', 'skill-kshetra-pro-quiz') . '</a>',
        ];
        return '<strong>' . esc_html($item['title']) . '</strong>' . $this->row_actions($actions);
    }

    public function column_questions($item): string
    {
        return esc_html((string) Quiz::questions_count((int) $item['id']));
    }

    public function column_attempts($item): string
    {
        return esc_html((string) Quiz::attempts_count((int) $item['id']));
    }
}
