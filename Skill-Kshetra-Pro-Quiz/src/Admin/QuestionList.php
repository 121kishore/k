<?php
namespace SkillKshetraProQuiz\Admin;

use SkillKshetraProQuiz\Models\Question;

if (!defined('ABSPATH')) { exit; }
if (!class_exists('WP_List_Table')) { require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php'; }

final class QuestionList extends \WP_List_Table
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        parent::__construct(['singular' => 'question', 'plural' => 'questions', 'ajax' => false]);
        $this->filters = $filters;
    }

    public function get_columns(): array
    {
        return ['cb' => '<input type="checkbox" />', 'question' => __('Question', 'skill-kshetra-pro-quiz'), 'quiz_title' => __('Quiz', 'skill-kshetra-pro-quiz'), 'difficulty' => __('Difficulty', 'skill-kshetra-pro-quiz'), 'status' => __('Status', 'skill-kshetra-pro-quiz')];
    }

    public function get_bulk_actions(): array
    {
        return ['delete' => __('Delete', 'skill-kshetra-pro-quiz'), 'active' => __('Change Status: Active', 'skill-kshetra-pro-quiz'), 'inactive' => __('Change Status: Inactive', 'skill-kshetra-pro-quiz'), 'duplicate' => __('Duplicate', 'skill-kshetra-pro-quiz')];
    }

    public function prepare_items(): void
    {
        $per_page = 20;
        $page = $this->get_pagenum();
        $this->_column_headers = [$this->get_columns(), [], []];
        $this->items = Question::findAll($this->filters, $per_page, $page);
        $this->set_pagination_args(['total_items' => Question::count($this->filters), 'per_page' => $per_page]);
    }

    public function column_cb($item): string
    {
        return '<input type="checkbox" name="question_ids[]" value="' . esc_attr((string) $item['id']) . '" />';
    }

    public function column_default($item, $column_name): string
    {
        if ($column_name === 'status') { return (int) $item['status'] === 1 ? esc_html__('Active', 'skill-kshetra-pro-quiz') : esc_html__('Inactive', 'skill-kshetra-pro-quiz'); }
        return esc_html((string) ($item[$column_name] ?? ''));
    }

    public function column_question($item): string
    {
        $id = (int) $item['id'];
        $edit = admin_url('admin.php?page=skpq-edit-question&id=' . $id);
        $delete = wp_nonce_url(admin_url('admin-post.php?action=skpq_delete_question&id=' . $id), 'skpq_delete_question_' . $id);
        $duplicate = wp_nonce_url(admin_url('admin-post.php?action=skpq_duplicate_question&id=' . $id), 'skpq_duplicate_question_' . $id);
        $actions = ['edit' => '<a href="' . esc_url($edit) . '">' . esc_html__('Edit', 'skill-kshetra-pro-quiz') . '</a>', 'delete' => '<a href="' . esc_url($delete) . '">' . esc_html__('Delete', 'skill-kshetra-pro-quiz') . '</a>', 'duplicate' => '<a href="' . esc_url($duplicate) . '">' . esc_html__('Duplicate', 'skill-kshetra-pro-quiz') . '</a>'];
        return '<strong>' . esc_html(wp_trim_words($item['question'], 12)) . '</strong>' . $this->row_actions($actions);
    }
}
