<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');



  $searchq =   $_POST['empCode'];
  $query = "SELECT * FROM tbl_employees WHERE employeeCode = '$searchq'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  // check if more than 0 record found
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $firstName = $row['firstName'];
  $middleName = $row['middleName'];
  $lastName = $row['lastName'];
  $email = $row['emailAddress'];

  try{
    $letters = '';
    $numbers = '';
    foreach (range('A', 'Z') as $char) {
        $letters .= $char;
    }
    for($i = 0; $i < 10; $i++){
      $numbers .= $i;
    }
    $password = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);
    
    
      $queryUpdate = "UPDATE tbl_employees SET password=:password WHERE employeeCode = '$searchq'";
        $stmt = $con->prepare($queryUpdate);
        // bind the parameters
        $stmt->bindParam(':password', $password);

        // Execute the query
        if($stmt->execute()){
            
                   // EMAIL
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


$body = "Good Day Mr/Ms. <b> $firstName $middleName $lastName! </b><br><br>

Your password has been reset successfully! Below is your new password. <br><br>

Password: <b> $password </b> <br><br>

To change your password, please visit hris-coresys.info <br><br>

After logging in, under profile, click account settings. <br><br>

Have a good day.";

$mail->SetFrom('coresys@hris-coresys.info', 'CoreSys Solutions Inc.');
$mail->Subject = "Password Reset";
$mail->MsgHTML($body);
$mail->AddAddress($email, $email);

if($mail->Send()) {
//   echo "Message sent!";
} else {
  echo "Mailer Error: " . $mail->ErrorInfo;
}
    // END EMAIL
    
    
    
          echo "
              <script>
                  window.open('update_employee_details.php?reset=success','_self');
              </script>
          ";
        }else {
          echo "
              <script>
                  window.open('update_employee_details.php','_self');
              </script>
          ";
        }

  }
  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }









?>