<?php

class GaryMailer {
    function __construct( $host, $port, $secure, $user, $pass) {
        $this->sendTo = $this->get_emails();
        $this->host = $host;
        $this->port = $port;
        $this->secure = $secure;
        $this->user = $user;
        $this->pass = $pass;
    }
    function get_emails() {
        // keep email list within obj for future use outside of interface
        $emails = get_option('analytics-mail');
        $e = $emails['email_list'];
        $list = explode(",", $e);
        return $list;
    }
    function send_mail($type) {
        require_once('Exception.php');
        require_once('PHPMailer.php');
        require_once('SMTP.php');
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP(); 
    
        $mail->CharSet="UTF-8";
        $mail->Host = $this->host;
        $mail->SMTPDebug = 0; 
        $mail->Port = $this->port; 
        $mail->SMTPSecure = $this->secure;  
        $mail->SMTPAuth = true; 
        $mail->IsHTML(true);
    
        //Authentication
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        
        //Collect "current" blog name and details 
        $site_title = get_bloginfo( 'name' );
        $site_url = network_site_url( '/' );
        $site_description = get_bloginfo( 'description' );
        $date = date('m-d-Y', time());

        //Build template.
        require_once(GARY_PLUGIN_URI . "templates/content.php");
        create_html_content($site_title, $site_description, $site_url, $date, $type);

        //Set Params
        foreach($this->sendTo as $key => $value) {
            $mail->SetFrom($this->user);
            $mail->addReplyTo($this->user);
            $mail->AddAddress($value);

            $mail->Subject = "Daily Analytics " . $site_title;
            $mail->msgHTML(file_get_contents(GARY_PLUGIN_URI . 'templates/contents.html'), __DIR__);
            if(!$mail->Send()) {
                $this->log_mailer_errors($date . " | " . $mail->ErrorInfo);
                return "An error occured during execution. Please confirm all mailing options are correct.";
            } else {
                $this->log_mailer_errors($date . " | SENT TO: " . $value . " | " . $type);
            } 
        }
        return "MAIL: process completed succesfully.";
    }

    function log_mailer_errors( $wp_error ){
      $fn = GARY_PLUGIN_URI . 'logs/mail.log'; 
      $fp = fopen($fn, 'a');
      fputs($fp, "Mailer | " . $wp_error ." | \n");
      fclose($fp);
    }

}

?>