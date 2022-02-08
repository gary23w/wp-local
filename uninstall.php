<?php 
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;
$table_list = array(
    "wp_gary_ips",
    "wp_gary_profiles",
    "wp_gary_trackers",
    "wp_gary_browsers",
    "wp_gary_sessions",
    "wp_gary_requests"   
);
foreach($table_list as $table) {
    $table_name = $wpdb->prefix . $table;
    $wpdb->query("DROP TABLE IF EXISTS {$table_name};");
}
?>