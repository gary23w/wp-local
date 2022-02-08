<?php
register_uninstall_hook( __FILE__, 'deactivate_tables' );

function deactivate_tables() {
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
        $wpdb->query("DROP TABLE IF EXISTS $table;");
    }
}
?>