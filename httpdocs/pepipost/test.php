 <?php
// require_once "vendor/autoload.php";
// $client = new PepipostAPILib\PepipostAPIClient();
// $emailController = $client->getEmail();

// // Your Pepipost API Key
// $apiKey = 'api-XX-key-XX-here'; #add apikey from panel here

// $body = new PepipostAPILib\Models\EmailBody();

// // List of Email Recipients
// $body->personalizations = array();
// $body->personalizations[0] = new PepipostAPILib\Models\Personalizations;
// $body->personalizations[0]->recipient = 'Youremailid@XXX.com';               #To/Recipient email address

// // Email Header
// $body->from = new PepipostAPILib\Models\From;
// $body->from->fromEmail = 'admin@myfirsttest.com';   #Sender Domain. Note: The sender domain should be verified and active under your Pepipost account.
// $body->from->fromName = 'Test Admin';       #Sender/From name

// //Email Body Content
// $body->subject = 'Pepipost mail through php sdk';               #Subject of email
// $body->content = '<html><body>Hello, Email testing is successful. <br> Hope you enjoyed this integration. <br></html>'; #HTML content which need to be send in the mail body

// // Email Settings
// $body->settings = new PepipostAPILib\Models\Settings;
// $body->settings->clicktrack = 1;    #clicktrack for emails enable=1 | disable=0
// $body->settings->opentrack = 1;     #opentrack for emails enable=1 | disable=0
// $body->settings->unsubscribe = 1;   #unsubscribe for emails enable=1 | disable=0

// $response = $emailController->createSendEmail($apiKey,$body);   #function sends email
// print_r(json_encode($response));
 ?>
 
 <?php 
 
 require_once "phpmailer/PHPMailerAutoload.php";
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = 'smtp.pepipost.com';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = 'admin5ins06';
//Password to use for SMTP authentication
$mail->Password = 'Valentino@212';
//Set who the message is to be sent from
$mail->setFrom('info@codexl.com', 'First Last');
//Set an alternative reply-to address
$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress('admin@codexl.ph', 'John Doe');
//Set the subject line
$mail->Subject = 'PHPMailer SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
//Replace the plain text body with one created manually
$message = 'This is a plain-text message body';
$mail->Body = $message;
//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
 
 
 echo "ee";
 
 
 
 
 
 ?>