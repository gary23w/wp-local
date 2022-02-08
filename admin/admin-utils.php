<?php

function ortho_testing_module(){
    require('admin-testing.php');
}

add_action( 'ortho_admin_panel', 'ortho_testing_module' );

if(isset($_POST['article_form_clear_logs'])) {
    clear_logs();
}
if(isset($_POST['article_form_test'])) {
    $options = get_option('analytics-mail');
    $destination_api = $options['destination_api'];
    $destination_port = $options['destination_port'];
    $destination_secure = $options['destination_secure'];
    $_user = $options['_user'];
    $_pass = $options['_pass'];
    $mail = new GaryMailer( $destination_api, $destination_port, $destination_secure, $_user, $_pass);
    $err = $mail->send_mail("DEVELOPMENT");
}

?>