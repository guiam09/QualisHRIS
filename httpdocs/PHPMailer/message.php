<?php
	include("php_connect.php");
 if(isset($_POST["msgx"])){
	 
	$message = $_POST["msg"];
	$mid = $_POST["lbcid"];
	
	//get clientID base on lbcid
	$get = "SELECT * FROM tbl_messages WHERE MessageID=$mid";
	$resget = mysqli_query($conn, $get);
	$rowget = mysqli_fetch_array($resget);
	$email = $rowget["Email"];
	$name = $rowget["ClientName"];
	$headers = 
	'From: empoweringfilipinos.ph' . "\r\n" .
    'Reply-To: jaypcavitana@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	// send email
	
require_once "phpmailer/PHPMailerAutoload.php";

//PHPMailer Object
$mail = new PHPMailer;

//From email address and name
$mail->From = "empoweringfilipinos.ph";
$mail->FromName = "Administrator";

//To address and name
$mail->addAddress($email, $name);
$mail->addAddress($email); //Recipient name is optional

//Address to which recipient will reply
$mail->addReplyTo("admin@empoweringfilipinos.ph", "Reply");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Empowering Filipinos";
$mail->Body = "<i>$message</i>";
$mail->AltBody = "";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}
	

	$upd = "UPDATE tbl_messages SET Status='REPLIED' WHERE MessageID=$mid";
	$resupd = mysqli_query($conn, $upd);

	header("location:messages.php?messagexxx");

 }
?>