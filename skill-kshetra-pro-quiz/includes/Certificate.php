<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Certificate
{
    public static function verification_code(int $attempt_id): string
    {
        return strtoupper(substr(wp_hash('sqp-certificate-' . $attempt_id), 0, 12));
    }
}
