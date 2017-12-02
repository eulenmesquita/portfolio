<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require 'PHPMailer.php';
require 'Exception.php';
require 'POP3.php';
require 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\POP3;

class Mailer {
    private $mail;
    private $env;
    private $message;
    
    public function __construct($env, $message) {
        $this->mail  = new PHPMailer(true);
        $this->env = $env;
        $this->message = $message;
    }
    
    public function send() {
        try {
            //Server settings
            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();                                      
            $this->mail->Host = $this->env->HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->env->USER_EMAIL;
            $this->mail->Password = $this->env->PASSWORD;
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = $this->env->PORT;

            //Recipient
            $this->mail->setFrom($this->env->USER_EMAIL, $this->message->name);
            $this->mail->addAddress($this->env->USER_EMAIL, 'Contact Form');
            $this->mail->AddReplyTo($this->message->email); 
            
            //Content
            $this->mail->isHTML(false);
            $this->mail->Subject = 'Website Contact Form';
            $this->mail->Body    = 
                'Name: '. $this->message->name . '\r\n' .
                'Date: '. date('d/m/Y') . '\r\n' .
                'Phone: ' . $this->message->phone . '\r\n' .
                'e-mail: ' . $this->message->email . '\r\n' .
                'message: ' . $this->message->content;
            
            $this->mail->send();
            
            $result = array(
                'status' => 'success',
                'message' =>'Your message has been sent'
            );
        } catch (Exception $e) {
            $result = array(
                'status' => 'error',
                'message' =>'The message could not be sent',
                'details' => $this->mail->ErrorInfo
            );
        }
        return $result;
    }
}