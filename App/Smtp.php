<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class Smtp {

    private static $config = null;

    /**
     * Load SMTP configuration.
     * 
     * @return void
     */
    public static function getConfiguration(){
        if( self::$config == null ){
            self::$config = json_decode( 
                file_get_contents( 
                    dirname( __DIR__ ) . '/config/smtp.json' 
                ),
                true 
            );
        }
    }

    /**
     * Sending email to smtp server
     *
     * for more detailed information on the PHPMailer itself please visit
     * https://github.com/PHPMailer/PHPMailer
     * 
     * @param String $to  The email address of the recipient
     * @param String $subject  The subject of the email to send
     * @param String $body  The content of the email to send
     *
     * @return void
     */
    public static function send( $to, $subject, $body ){
        static::getConfiguration();
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host       = self::$config["host"];  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = self::$config["requiresAuth"]; // Enable SMTP authentication
        $mail->Username   = self::$config["username"]; // SMTP username
        $mail->Password   = self::$config["password"]; // SMTP password
        $mail->SMTPSecure = self::$config["encryption"]; // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = self::$config["port"]; // TCP port to connect to
        $mail->SMTPOptions = [
            'ssl'=> [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        //Recipients
        $mail->setFrom( self::$config["fromEmail"] );
        $mail->addAddress( $to );
        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject; // 'my test subject';
        $mail->Body    = $body; // 'This is the text message body.';
        $mail->send();
    }

}