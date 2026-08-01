<?php
namespace SkillKshetraProQuiz\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    private static function autoload(string $class): void
    {
        $prefix = 'SkillKshetraProQuiz\\';
        if (strpos($class, $prefix) !== 0) {
            return;
        }

        $relative = substr($class, strlen($prefix));
        $path = SKPQ_PLUGIN_DIR . 'includes/' . str_replace('\\', '/', $relative) . '.php';
        if (is_readable($path)) {
            require_once $path;
        }
    }
}
