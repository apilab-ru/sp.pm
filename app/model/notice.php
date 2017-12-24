<?php

//use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

namespace app\model;

class notice extends base
{
    private $account = null;
    public function getAccount()
    {
        if($this->account){
            return $this->account;
        }else{
            return $this->account = $this->db->selectRow("select * from smtp_accounts order by id DESC limit 1");
        }
    }
    
    public function send($email, $subject,  $text)
    {
        $acc    = $this->getAccount();
        $login  = $acc['login'];
        $pass   = $acc['pass'];
        $host   = $acc['host'];
        $secure = $acc['secure'];
        $port   = $acc['port'];
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                            // Enable verbose debug output
            $mail->isSMTP();                                 // Set mailer to use SMTP
            $mail->Host        =  $host;                     // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                        // Enable SMTP authentication
            $mail->Username   = $login;                      // SMTP username
            $mail->Password   = $pass;                       // SMTP password
            $mail->SMTPSecure = $secure;                     // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = $port;                       // TCP port to connect to
            //Recipients
            $mail->setFrom($login);
            $mail->addAddress($email);     // Add a recipient

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $text;
            $mail->AltBody = strip_tags($text);
            $mail->send();
            return true;
        } catch (\Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}
