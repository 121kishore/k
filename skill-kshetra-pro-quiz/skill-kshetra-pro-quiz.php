<?php
/**
 * Plugin Name: Skill Kshetra Pro Quiz
 * Description: WordPress quiz management with custom database tables, question bank, CSV import, analytics, dashboards, and learning-focused answer explanations.
 * Version: 0.1.0
 * Author: Kwork
 * License: GPL-2.0-or-later
 * Text Domain: skill-kshetra-pro-quiz
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SQP_VERSION', '0.1.0');
define('SQP_PLUGIN_FILE', __FILE__);
define('SQP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SQP_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once SQP_PLUGIN_DIR . 'includes/Database.php';
require_once SQP_PLUGIN_DIR . 'includes/Helpers.php';
require_once SQP_PLUGIN_DIR . 'includes/Quiz.php';
require_once SQP_PLUGIN_DIR . 'includes/Question.php';
require_once SQP_PLUGIN_DIR . 'includes/Attempt.php';
require_once SQP_PLUGIN_DIR . 'includes/Result.php';
require_once SQP_PLUGIN_DIR . 'includes/Certificate.php';
require_once SQP_PLUGIN_DIR . 'includes/API.php';

final class Smart_Quiz_Pro
{
    private Smart_Quiz_Pro_API $api;

    public function __construct()
    {
        $this->api = new Smart_Quiz_Pro_API();
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'register_public_assets']);
        add_shortcode('smartquiz', [$this, 'render_shortcode']);
        add_action('init', [$this->api, 'register_ajax_routes']);
    }

    public function load_textdomain(): void
    {
        load_plugin_textdomain('skill-kshetra-pro-quiz', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function register_admin_menu(): void
    {
        add_menu_page(__('Skill Kshetra Quiz', 'skill-kshetra-pro-quiz'), __('Skill Kshetra Quiz', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-dashboard', [$this, 'render_admin_page'], 'dashicons-welcome-learn-more', 26);
        add_submenu_page('sqp-dashboard', __('Dashboard', 'skill-kshetra-pro-quiz'), __('Dashboard', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-dashboard', [$this, 'render_admin_page']);
        add_submenu_page('sqp-dashboard', __('Quizzes', 'skill-kshetra-pro-quiz'), __('Quizzes', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-quizzes', [$this, 'render_admin_page']);
        add_submenu_page('sqp-dashboard', __('Question Bank', 'skill-kshetra-pro-quiz'), __('Question Bank', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-questions', [$this, 'render_admin_page']);
        add_submenu_page('sqp-dashboard', __('CSV Import', 'skill-kshetra-pro-quiz'), __('CSV Import', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-import', [$this, 'render_admin_page']);
        add_submenu_page('sqp-dashboard', __('Analytics', 'skill-kshetra-pro-quiz'), __('Analytics', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-analytics', [$this, 'render_admin_page']);
        add_submenu_page('sqp-dashboard', __('Settings', 'skill-kshetra-pro-quiz'), __('Settings', 'skill-kshetra-pro-quiz'), 'manage_options', 'sqp-settings', [$this, 'render_admin_page']);
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if (strpos($hook, 'sqp-') === false && $hook !== 'toplevel_page_sqp-dashboard') {
            return;
        }
        wp_enqueue_style('sqp-admin', SQP_PLUGIN_URL . 'assets/admin.css', [], SQP_VERSION);
        wp_enqueue_script('sqp-admin', SQP_PLUGIN_URL . 'assets/admin.js', [], SQP_VERSION, true);
    }

    public function register_public_assets(): void
    {
        wp_register_style('sqp-public', SQP_PLUGIN_URL . 'public/css/skill-kshetra-pro-quiz.css', [], SQP_VERSION);
        wp_register_script('sqp-public', SQP_PLUGIN_URL . 'public/js/skill-kshetra-pro-quiz.js', [], SQP_VERSION, true);
        wp_localize_script('sqp-public', 'SmartQuizPro', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sqp_public'),
        ]);
    }

    public function render_shortcode(array $atts): string
    {
        $atts = shortcode_atts([
            'id' => 0,
            'category' => '',
            'latest' => false,
            'random' => false,
            'leaderboard' => false,
        ], $atts, 'smartquiz');

        wp_enqueue_style('sqp-public');
        wp_enqueue_script('sqp-public');

        $quiz = Smart_Quiz_Pro_Quiz::resolve_for_shortcode($atts);
        if (!$quiz) {
            return '<p>' . esc_html__('No quiz found.', 'skill-kshetra-pro-quiz') . '</p>';
        }

        $questions = Smart_Quiz_Pro_Question::for_quiz((int) $quiz['id']);
        ob_start();
        include SQP_PLUGIN_DIR . 'public/templates/quiz-screen.php';
        return (string) ob_get_clean();
    }

    public function render_admin_page(): void
    {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : 'sqp-dashboard';
        $map = [
            'sqp-dashboard' => 'admin/dashboard/index.php',
            'sqp-quizzes' => 'admin/quizzes/index.php',
            'sqp-questions' => 'admin/questions/index.php',
            'sqp-import' => 'admin/import/index.php',
            'sqp-analytics' => 'admin/analytics/index.php',
            'sqp-settings' => 'admin/settings/index.php',
        ];
        include SQP_PLUGIN_DIR . ($map[$page] ?? $map['sqp-dashboard']);
    }
}

register_activation_hook(__FILE__, ['Smart_Quiz_Pro_Database', 'activate']);
register_uninstall_hook(__FILE__, ['Smart_Quiz_Pro_Database', 'uninstall']);
new Smart_Quiz_Pro();
