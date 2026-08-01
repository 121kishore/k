<?php
if (!defined('WP_UNINSTALL_PLUGIN')) { exit; }
require_once __DIR__ . '/includes/Database.php';
Smart_Quiz_Pro_Database::uninstall();
