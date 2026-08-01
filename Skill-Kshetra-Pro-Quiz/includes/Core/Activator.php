<?php
namespace SkillKshetraProQuiz\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class Activator
{
    public static function activate(): void
    {
        Database::create_tables();
        update_option('skpq_version', SKPQ_VERSION);
    }
}
