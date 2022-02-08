<?php
function _analytics_mail_menu(){
	add_menu_page(
		'Analytics Mail',
		'Analytics Mail',
		'manage_options',
		'analytics_mail',
		'_analytics_mail_options_page',
        'dashicons-businessperson'
	);
}
add_action('admin_menu', '_analytics_mail_menu');
function _analytics_mail_options_page()   {
        require_once( GARY_PLUGIN_URI . '/admin/admin-nav.php');
        if (!current_user_can('manage_options' )){
            wp_die('You do not have enough permission to view this page');
        }
}

add_action("admin_head",'ortho_admin_global_css');

function log_fun() {
    echo "<script>console.log('test');</script>";
}

function save_creds($mail) {
    $options = get_option('analytics-mail');
    switch ($mail) {
        case 0:
            log_fun();
            break;
        case 1:
            $options['destination_api'] = esc_html($_POST['destination_api']);
            $options['destination_port'] = esc_html($_POST['destination_port']);
            $options['destination_secure'] =  esc_html($_POST['destination_secure']); 
            $options['_user'] = esc_html($_POST['_user']);
            $options['_pass'] = empty(esc_html($_POST['_pass'])) ? $options['_pass'] : esc_html($_POST['_pass']);
            //(empty($options['euipo_username'])) ? '' : $options['euipo_username'];
            $options['email_list'] = esc_html($_POST['email-list']);
            log_fun();
            break;
        case 3:
            //$options['_pass'] = "";
            break;
        default:
            log_fun();
    }
    update_option('analytics-mail', $options);
}

function clear_logs() {
    $fn = GARY_PLUGIN_URI . "logs/mail.log"; 
    $fp = fopen(GARY_PLUGIN_URI . "logs/mail.log", "w") or die("Unable to open file!");
    fclose($fp);
}

function ortho_options_save_url($extra = '') {
    return admin_url('admin.php?page=analytics_mail' . $extra);
}

function ortho_admin_global_css() {
    global $ortho_version;
    wp_register_style( 'ortho_wp_admin_global_css', GARY_PLUGIN_URL . '/admin/global.css', false, $ortho_version );
    wp_enqueue_style( 'ortho_wp_admin_global_css' );
}

?>