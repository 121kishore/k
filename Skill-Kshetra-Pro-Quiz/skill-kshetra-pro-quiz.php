<?php
/**
 * Plugin Name: Skill Kshetra Pro Quiz
 * Description: Structured WordPress quiz plugin foundation with admin, frontend, models, and custom database services.
 * Version: 0.1.0
 * Author: Kwork
 * License: GPL-2.0-or-later
 * Text Domain: skill-kshetra-pro-quiz
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SKPQ_VERSION', '0.1.0');
define('SKPQ_PLUGIN_FILE', __FILE__);
define('SKPQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SKPQ_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once SKPQ_PLUGIN_DIR . 'includes/Core/Autoloader.php';
SkillKshetraProQuiz\Core\Autoloader::register();

register_activation_hook(__FILE__, [SkillKshetraProQuiz\Core\Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [SkillKshetraProQuiz\Core\Deactivator::class, 'deactivate']);
register_uninstall_hook(__FILE__, [SkillKshetraProQuiz\Core\Database::class, 'uninstall']);

add_action('plugins_loaded', static function (): void {
    SkillKshetraProQuiz\Core\Plugin::instance()->boot();
});
