<?php
require_once('wp-load.php');
global $wpdb;
$res = $wpdb->get_results("SHOW TABLES");
print_r($res);
