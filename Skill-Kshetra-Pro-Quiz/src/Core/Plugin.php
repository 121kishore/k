<?php
namespace SkillKshetraProQuiz\Core;

use SkillKshetraProQuiz\Admin\Menu;
use SkillKshetraProQuiz\Frontend\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}

final class Plugin
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function boot(): void
    {
        load_plugin_textdomain('skill-kshetra-pro-quiz', false, dirname(plugin_basename(SKPQ_PLUGIN_FILE)) . '/languages');
        (new Menu())->register();
        (new Shortcodes())->register();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_assets']);
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if (strpos($hook, 'skpq-') === false && $hook !== 'toplevel_page_skpq-dashboard') {
            return;
        }
        wp_enqueue_style('skpq-admin', SKPQ_PLUGIN_URL . 'assets/css/admin.css', [], SKPQ_VERSION);
        wp_enqueue_script('skpq-admin', SKPQ_PLUGIN_URL . 'assets/js/admin.js', [], SKPQ_VERSION, true);
    }

    public function register_frontend_assets(): void
    {
        wp_register_style('skpq-frontend', SKPQ_PLUGIN_URL . 'assets/css/frontend.css', [], SKPQ_VERSION);
        wp_register_script('skpq-frontend', SKPQ_PLUGIN_URL . 'assets/js/frontend.js', [], SKPQ_VERSION, true);
    }
}
