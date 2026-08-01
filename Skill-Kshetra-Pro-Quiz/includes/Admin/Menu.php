<?php
namespace SkillKshetraProQuiz\Admin;

if (!defined('ABSPATH')) {
    exit;
}

final class Menu
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'add_pages']);
    }

    public function add_pages(): void
    {
        add_menu_page(__('Skill Kshetra Pro Quiz', 'skill-kshetra-pro-quiz'), __('Skill Kshetra Quiz', 'skill-kshetra-pro-quiz'), 'manage_options', 'skpq-dashboard', [Dashboard::class, 'render'], 'dashicons-welcome-learn-more', 26);
    }
}
