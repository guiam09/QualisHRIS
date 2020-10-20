<?php
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

 $mail = new PHPMailer\PHPMailer\PHPMailer();

// $mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host       = "relay-hosting.secureserver.net";
$mail->Port       = 25;                   
$mail->SMTPDebug  = 0;
$mail->SMTPSecure = false;                 
$mail->SMTPAuth   = false;
$mail->Username   = "coresys@hris-coresys.info";
$mail->Password   = "coresysadmin";


$body = "Good Day Mr/Ms. TestName! <br><br>

Here is your Log-in Credentials for your account in Coresys Solutions Human Resource Information System (HRIS) <br><br>

Username: testUsername <br><br>
Password: testPassword <br><br>

To access your account, please visit hris-coresys.info <br><br>

Have a good day.";

$mail->SetFrom('coresys@hris-coresys.info', 'CoreSys Solutions Inc. HRIS');
$mail->Subject = "Your Login Credentials";
$mail->MsgHTML($body);
$mail->AddAddress('jhaaanz@gmail.com', 'jhanz');

if($mail->Send()) {
  echo "Message sent!";
} else {
  echo "Mailer Error: " . $mail->ErrorInfo;
}