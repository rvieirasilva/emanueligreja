<?php
    /* Este trecho estava dando errado continuamente em 2022
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    include "./lib/vendor/autoload.php";

    $mail = new PHPMailer(true);
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'email-ssl.com.br';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'contato@igrejaemanuel.com.br';                     //SMTP username
    $mail->Password   = '01Jesussalva@';                               //SMTP password
    $mail->SMTPSecure = "ssl";            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Encoding = "7bit";
    $mail->CharSet='UTF-8';
    $mail->setFrom("noreply@igrejaemanuel.com.br", "Igreja Emanuel");
    */

    //Email teste
    require_once "PHPMailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'email-ssl.com.br';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'contato@igrejaemanuel.com.br';                     //SMTP username
    $mail->Password   = '01Jesussalva@';                               //SMTP password
    $mail->SMTPSecure = "ssl";            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Encoding = "7bit";
    $mail->CharSet='UTF-8';
    $mail->setFrom("noreply@igrejaemanuel.com.br", "Igreja Emanuel");

    //Financas-01Emanuel - Financeiro
