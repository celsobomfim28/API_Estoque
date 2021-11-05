<?php
namespace App\Utils;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \App\Utils\Encrypt;
use \App\Utils\Config;

error_reporting(E_ALL & ~E_NOTICE);
//Load Composer's autoloader
define('__ROOT__', dirname(dirname(__FILE__)));
include_once(__ROOT__.'/App/Utils/Encrypt.php'); 
include_once(__ROOT__.'/App/Utils/config.php'); 
require (__ROOT__.'/vendor/autoload.php');


class Mail{

public function sendEmail($email, $nome, $token){

$encrypt = new Encrypt();
        //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$conf = new Config();

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $encrypt->base64url_decode($conf->getHost());      //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $encrypt->base64url_decode($conf->getUser());      //SMTP username
    $mail->Password   = $encrypt->base64url_decode($conf->getPass());      //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('celsobomfim20@hotmail.com', 'Mailer');
    $mail->addAddress($email);     //Add a recipient
    $mail->addReplyTo('celsobomfim20@hotmail.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');


    //Content
    $body = '<p>Olá <strong>'.$nome.'</strong>! <br>Tudo bem com você?
    <br>Vimos que você pediu para recuperar sua senha?<br>Estamos aqui para te ajudar, 
    utilize esse token <br>'.$token.'<br><br>Tchau Tchau!</p>';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Resetar Senha!';
    $mail->Body    = $body;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
}