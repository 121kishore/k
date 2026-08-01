<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

define('SKPQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once __DIR__ . '/src/Core/Autoloader.php';
SkillKshetraProQuiz\Core\Autoloader::register();
SkillKshetraProQuiz\Core\Database::uninstall();
