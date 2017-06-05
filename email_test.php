<?php

echo "THIS BE THE email TESTER . . . <br><br>";

$message = "Just some simple text . . . This time . . . Bwahaha!";

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'localhost';
$mail->Port = 25;
$mail->SMTPSecure = false;
$mail->SMTPAutoTLS = false;
$mail->SMTPAuth = false;
$mail->setFrom('help@vinceco.com', 'Vince Tester Gonzales');
$mail->addAddress('vinceco2003@swcp.com', 'Vince SWCP');
$mail->addAddress('vinceco2014@gmail.com', 'Vince Gorgle');
$mail->Subject = 'Re: StackChampion Inquest';
$mail->Body = $message;

if (!$mail->send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    echo "Message sent!";
    }


 ?>
