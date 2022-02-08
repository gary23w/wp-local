<?php
register_deactivation_hook( __FILE__, 'deactivate_cron_mailer' );

function my_cron_schedules($schedules){
    if(!isset($schedules["60sec"])){
        $schedules["60sec"] = array(
            'interval' => 60,
            'display' => __('Once every 60 secs'));
    }
    if(!isset($schedules["today"])){
        $schedules["today"] = array(
            'interval' => 86400,
            'display' => __('Once every day.'));
    }
    return $schedules;
}

add_filter('cron_schedules','my_cron_schedules');
wp_clear_scheduled_hook('email_analytics');

// MAIL SYSTEM
if (!wp_next_scheduled('email_analytics_')) {
	wp_schedule_event( time(), 'today', 'email_analytics_' );
}
add_action ( 'email_analytics_', 'do_this_email' );

function deactivate_cron_mailer() {
    wp_clear_scheduled_hook('email_analytics_');
}
//

function do_this_email() {
    $options = get_option('analytics-mail');
    if ($options != '') {
      $destination_api = $options['destination_api'];
      $_user = $options['_user'];
      $_pass = $options['_pass'];
      $destination_port = $options['destination_port'];
      $destination_secure = $options['destination_secure']; 
      require_once(GARY_PLUGIN_URI . "/includes/mail/Mail.php");
      $mail = new GaryMailer( $destination_api, $destination_port, $destination_secure, $_user, $_pass );
      $mail->send_mail("Cron");
    } else {
        $fn = 'cron.log'; 
        $fp = fopen($fn, 'a');
        fputs($fp, "Failed to send daily user report \n");
        fclose($fp);
    }
}
?>