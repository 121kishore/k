<?php
if (!defined('ABSPATH')) { exit; }

final class Smart_Quiz_Pro_Helpers
{
    public static function grade(float $percent): string
    {
        if ($percent >= 90) { return 'A+'; }
        if ($percent >= 80) { return 'A'; }
        if ($percent >= 70) { return 'B'; }
        if ($percent >= 60) { return 'C'; }
        return 'Needs Practice';
    }
}
