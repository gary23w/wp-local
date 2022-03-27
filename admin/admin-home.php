<?php


if( is_admin()) {

function ortho_apisettings_settings_panel(){
    $options = get_option('analytics-mail');
    if ($options != '') {
        $destination_api = $options['destination_api'];
        $destination_port = $options['destination_port'];
        $destination_secure = $options['destination_secure'];
        $_user = $options['_user'];
        $_pass = $options['_pass'];
        $email_list = $options['email_list'];
    } 
    require('admin-interface.php');
}

function reload_page() {
    echo '<script type="text/javascript">';
    echo 'window.location.reload();';
    echo '</script>';
}

if(isset($_POST['article_form_submit'])) {
    save_creds(1);
    reload_page();
}
if(isset($_POST['reset_password'])) {
    save_creds(3);
    reload_page();
}
if(isset($_POST['article_form_update_mail'])) {
    save_creds(0);
    reload_page();
}

//Add action to wordpress
add_action( 'ortho_admin_panel', 'ortho_apisettings_settings_panel' );

}
?>