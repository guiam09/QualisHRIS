<?php
session_start();

 $s = $_GET['username'];

$con = new mysqli('107.180.58.58:3306', 'codeXL', 'Commlinked2019', 'pangalan_db');

$stmt = $con->prepare("SELECT first_name FROM user WHERE username=?");
$stmt->bind_param('s', $s);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($firstName);
if($stmt->num_rows > 0){
    while($stmt->fetch()){
       
        echo $firstName;
        
    }
}else{
    echo "s";
}
$stmt->close();
$con->close();


// require_once '../PHPMailer/src/PHPMailer.php';
// require_once '../PHPMailer/src/Exception.php';
// require_once '../PHPMailer/src/SMTP.php';

//  $mail = new PHPMailer\PHPMailer\PHPMailer();

// // $mail->isSMTP();                                      // Set mailer to use SMTP
// $mail->Host       = "relay-hosting.secureserver.net";
// $mail->Port       = 25;                   
// $mail->SMTPDebug  = 0;
// $mail->SMTPSecure = false;                 
// $mail->SMTPAuth   = false;
// $mail->Username   = "coresys@hris-coresys.info";
// $mail->Password   = "coresysadmin";


// $body = "Good Day Mr/Ms. TestName! <br><br>

// Here is your Log-in Credentials for your account in Coresys Solutions Human Resource Information System (HRIS) <br><br>

// Username: testUsername <br><br>
// Password: testPassword <br><br>

// To access your account, please visit hris-coresys.info <br><br>

// Have a good day.";

// $mail->SetFrom('coresys@hris-coresys.info', 'CoreSys Solutions Inc. HRIS');
// $mail->Subject = "Your Login Credentials";
// $mail->MsgHTML($body);
// $mail->AddAddress('jhaaanz@gmail.com', 'jhanz');

// if($mail->Send()) {
//   echo "Message sent!";
// } else {
//   echo "Mailer Error: " . $mail->ErrorInfo;
// }