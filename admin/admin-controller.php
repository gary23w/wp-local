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
add_action("admin_head",'ortho_admin_global_js');

function admin_ajax(){
    ?>
<script>
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var pathPlu = '<?php echo GARY_PLUGIN_URL ?>';
</script>
<?php
    }
add_action('admin_head', 'admin_ajax');

function login_check()
{
    $username = strval($_REQUEST['username']);
    $password = strval($_REQUEST['password']);
    $file = file_get_contents(plugin_dir_path( __FILE__ ) . 'users.json');
    $user_json = json_decode($file, true);
    $cur_user = json_encode(array("login" => "false"));
    foreach ($user_json['users'] as $user => $value) {
        $u_j = $value['username'];
        $p_j = $value['password'];
        if ($u_j == $username && $p_j == $password) {
            $cur_user = json_encode(array('login' => 'true', 'user' => $value['username'], 'id' => $value['id'], 'hash' => $value['hash']));
        }
    }
    echo $cur_user;
    die();
}

add_action('wp_ajax_nopriv_login_check', 'login_check'); 
add_action('wp_ajax_login_check', 'login_check');

function hash_check()
{
    $cookie_id = strval($_REQUEST['cookie_id']);
    $file = file_get_contents(plugin_dir_path( __FILE__ ) . 'users.json');
    $user_json = json_decode($file, true);
    $check = false;
    foreach ($user_json['users'] as $user => $value) { // expand on this some time.
        $h_j = $value['hash'];
        if ($h_j == $cookie_id) {
            $check = true;
        }
    }
    if($check == true){
       echo json_encode(array('hash' => 'true'));
    } else {
       echo json_encode(array('hash' => 'false')); 
    }
    
    die();
}

add_action('wp_ajax_nopriv_hash_check', 'hash_check'); 
add_action('wp_ajax_hash_check', 'hash_check');

function save_creds() {
    $options = get_option('analytics-mail');
    $server = strval($_REQUEST['server']);
    $port = strval($_REQUEST['port']);
    $secure = strval($_REQUEST['secure']);
    $user = strval($_REQUEST['username']);
    $pass = strval($_REQUEST['password']);
    $email_list = strval($_REQUEST['list']);
    if (substr($email_list, 0, 1) == ',') {
        $email_list = substr($email_list, 1);
    }
    if ($server) {
        $options['destination_api'] = $server;
    }
    if ($port) {
        $options['destination_port'] = $port;
    }
    if ($secure) {
        $options['destination_secure'] = $secure;
    }
    if ($user) {
        $options['_user'] = $user;
    }
    if ($pass) {
        $options['_pass'] = $pass;
    }

    $options['email_list'] = $email_list;

    update_option('analytics-mail', $options);
}

add_action('wp_ajax_nopriv_save_creds', 'save_creds'); 
add_action('wp_ajax_save_creds', 'save_creds');

function add_user_local() {
    $name = strval($_REQUEST['personname']);
    $username_new = strval($_REQUEST['username']);
    $pass = strval($_REQUEST['password']);
    $file = file_get_contents(plugin_dir_path( __FILE__ ) . 'users.json');
    $user_json = json_decode($file, true);
    //get last id
    $last_id = 0;
    foreach ($user_json['users'] as $user => $value) {
        $id = $value['id'];
        if ($id > $last_id) {
            $last_id = $id;
        }
    }
    $new_id = $last_id + 1;
    $hash = md5($name);
    $user_json['users'][] = array('id' => $new_id, 'name' => $name, 'username' => $username_new, 'password' => $pass, 'hash' => $hash);
    //write to file
    $file = fopen(GARY_PLUGIN_URI . 'admin/users.json', 'w');
    fwrite($file, json_encode($user_json));
    fclose($file);
    echo json_encode(array('user_created' => 'true', $user_json));
    die();
}

add_action('wp_ajax_nopriv_add_user_local', 'add_user_local'); 
add_action('wp_ajax_add_user_local', 'add_user_local');

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

function ortho_admin_global_js() {
    global $ortho_version;
    if (strpos($_SERVER['REQUEST_URI'], 'analytics_mail') !== false) {
        wp_register_script( 'ortho_wp_admin_global_js', GARY_PLUGIN_URL . '/admin/admin-login.js', array('jquery'), $ortho_version, true );
        wp_enqueue_script( 'ortho_wp_admin_global_js' );
    }
}

?>