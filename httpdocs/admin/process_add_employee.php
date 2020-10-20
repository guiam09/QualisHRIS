<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
$date = date_create()->format('Y-m-d H:i:s');
// if(isset($_POST['add'])){

    // include database connection


    try{
    // insert query

    $query3 = "INSERT INTO tbl_employees SET employeeCode=:employeeID, firstName=:firstName, lastName=:lastName, middleName=:middleName,
    birthdate=:birthDate, gender=:gender, contactInfo=:contactNumber, address=:address, dateHired=:dateHired,
    departmentID=:department, positionID=:position, civilStatus=:civilStatus, emailAddress=:emailAddress, pagibigID=:pagibigID, sssID=:sssID,
    tinID=:tinID, philhealthID=:philhealthID, reportingTo=:reportingTo,
    username=:username, password=:password, age=:age, coreTimeID=:shiftID,
    status=:status, dateAdded=:dateAdded, photo=:image, whoAdded=:whoAdded";
    $stmt3 = $con->prepare($query3);

    // posted values

    $firstName=htmlspecialchars(strip_tags($_POST['firstName']));
    $firstName = strtolower($firstName);
    $firstName = ucwords($firstName); 
    
    $lastName=htmlspecialchars(strip_tags($_POST['lastName']));
    
    $lastName = strtolower($lastName);
    $lastName = ucwords($lastName); 
    
    $gender=htmlspecialchars(strip_tags($_POST['gender']));
    $middleName=htmlspecialchars(strip_tags($_POST['middleName']));
    
    $middleName = strtolower($middleName);
    $middleName = ucwords($middleName); 
    
    $birthDate=htmlspecialchars(strip_tags($_POST['birthDate']));
    $contactNumber=htmlspecialchars(strip_tags($_POST['contactNumber']));
    $address=htmlspecialchars(strip_tags($_POST['address']));
    $dateHired=htmlspecialchars(strip_tags($_POST['dateHired']));

    $civilStatus = htmlspecialchars(strip_tags($_POST['civilStatus']));
    $email = htmlspecialchars(strip_tags($_POST['emailAddress']));
    $pagibig = htmlspecialchars(strip_tags($_POST['pagibigID']));
    $sss = htmlspecialchars(strip_tags($_POST['sssID']));
    $tin = htmlspecialchars(strip_tags($_POST['tinID']));
    $philhealth = htmlspecialchars(strip_tags($_POST['philhealthID']));

    $position = htmlspecialchars(strip_tags($_POST['position']));
    $department = htmlspecialchars(strip_tags($_POST['department']));
    $reportingTo = htmlspecialchars(strip_tags($_POST['reportingTo']));

    $shiftID = htmlspecialchars(strip_tags($_POST['coreTime']));

    $Allow = array("jpg", "jpeg", "png");
    $ToDirectory = "../images/";
    if(!!$_FILES['Image']['tmp_name']) {
        $Info = explode('.', strtolower($_FILES['Image']['name']));
        $Image = round(microtime(true)) . '.' . end ($Info);
        if(in_array(end($Info), $Allow)) {
            if(move_uploaded_file($_FILES['Image']['tmp_name'], $ToDirectory . $Image )) {

            }
        }
    }

    // converting one date format into another
   $birthDate = DateTime::createFromFormat('m/d/Y', $birthDate);
   $birthDate = $birthDate->format('Y-m-d');
  
   $dateHired = DateTime::createFromFormat('m/d/Y', $dateHired);
   $dateHired = $dateHired->format('Y-m-d');

  //age
  $age = date_diff(date_create($birthDate), date_create('now'))->y;


    $moment = date('Y-m-d');


    //creating employeeid
    // $query1 = "SELECT * FROM tbl_department WHERE departmentID= $department";
    // $stmt1 = $con->prepare($query1);
    // $stmt1->execute();
    // $rows = $stmt1->fetch(PDO::FETCH_ASSOC);
    // $depts = $rows['departmentName'];
    // $dept = $depts[0];
    //
    // $query = "SELECT MAX(employeeID) AS max_id FROM tbl_employees";
    // $stmt = $con->prepare($query);
    // $stmt->execute();
    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // $num = $row['max_id'] + 1;
    // $num = sprintf('%04d', $num);
    // $empID = $dept . $num;

//     $query = "SELECT MAX(employeeCode) FROM tbl_employees";
//     $stmt = $con->prepare($query);
//     $stmt->execute();
//
// //get last used employee_ID. used FETCH_BOTH to output index 0
//     $lastEmpID = $stmt->fetch(PDO::FETCH_BOTH);
//     $empID = $lastEmpID[0];
//
//     //extract employee number from last employee_ID
//     $lastEmpNumber = substr($empID,2);
//
//     //generate new employee number
//     $newEmpNumber = (int)$lastEmpNumber + 1;
$query = "SELECT MAX(employeeID) AS max_id FROM tbl_employees";
$stmt = $con->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num = $row['max_id'] + 1;
$num = sprintf('%04d', $num);

    //get current year in 2 digits
    $currentYear = date("y");
    //format new employee Number
    $formattedEmpNumber = str_pad($num, 3, '0', STR_PAD_LEFT);

    //generate new employee_ID
    $empID = (int)($currentYear.$formattedEmpNumber);




    //creating employeeid
    $letters = '';
    $numbers = '';
    foreach (range('A', 'Z') as $char) {
        $letters .= $char;
    }
    for($i = 0; $i < 10; $i++){
      $numbers .= $i;
    }
    $password = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);


    $currentUser = $_SESSION['user_id'];
    $status = "Probation";
    // bind the parameters
    $stmt3->bindParam(':civilStatus', $civilStatus);
    $stmt3->bindParam(':emailAddress', $email);
    $stmt3->bindParam(':pagibigID', $pagibig);
    $stmt3->bindParam(':sssID', $sss);
    $stmt3->bindParam(':tinID', $tin);
    $stmt3->bindParam(':philhealthID', $philhealth);
    $stmt3->bindParam(':employeeID', $empID);
    $stmt3->bindParam(':firstName', $firstName);
    $stmt3->bindParam(':lastName', $lastName);
    $stmt3->bindParam(':middleName', $middleName);
    $stmt3->bindParam(':birthDate', $birthDate);
    $stmt3->bindParam(':gender', $gender);
    $stmt3->bindParam(':contactNumber', $contactNumber);
    $stmt3->bindParam(':address', $address);
    $stmt3->bindParam(':dateHired', $dateHired);
    $stmt3->bindParam(':reportingTo', $reportingTo);
    $stmt3->bindParam(':department', $department);
    $stmt3->bindParam(':position', $position);
    $stmt3->bindParam(':username', $empID);
    $stmt3->bindParam(':password', $password);
    $stmt3->bindParam(':age', $age);
    $stmt3->bindParam(':shiftID', $shiftID);
    $stmt3->bindParam(':status', $status);
    $stmt3->bindParam(':dateAdded', $moment);
    $stmt3->bindParam(':whoAdded', $currentUser);
    $stmt3->bindParam(':image', $Image);

  // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'AED Employee';
  $sub_module = 'Add Employee';
  $old_data = '';
  $new_data = $empID;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);

    // Execute the query
    if($stmt3->execute()){
        
         //   access level insert
          $last_id = $con->lastInsertId();
          $accessLevel=htmlspecialchars(strip_tags($_POST['accessLevel']));
          $accessLevelQuery = "INSERT INTO tbl_accessLevelEmp SET accessLevelID=:accessLevelID, employeeID=:employeeID";
          $accessLevelStatement = $con->prepare($accessLevelQuery);
          $accessLevelStatement->bindParam(':accessLevelID', $accessLevel);
          $accessLevelStatement->bindParam(':employeeID', $last_id);
          
          $accessLevelStatement->execute();
    // end access level insert

      $queryLeave = "SELECT * FROM tbl_leave WHERE required = 1";
      $leavestmt = $con->prepare($queryLeave);
      $leavestmt->execute();
      $number = $leavestmt->rowCount();
      // check if more than 0 record found
      if($number>0){
        while ($leaveRow = $leavestmt->fetch(PDO::FETCH_ASSOC)){
          $leaveID = $leaveRow['leaveID'];
          $leaveRemaining = $leaveRow['leaveCount'];
          $query4 = "INSERT INTO tbl_leaveinfo SET
                      leaveID=:leaveID,
                      employeeID=:employeeID,
                      leaveRemaining=:leaveRemaining, allowedLeave=:leaveRemaining
      ";
        $stmt4=$con->prepare($query4);

        $stmt4->bindparam(':leaveID',$leaveID);
        $stmt4->bindparam(':leaveRemaining',$leaveRemaining);
        $stmt4->bindparam(':allowedLeave',$leaveRemaining);
        $stmt4->bindparam(':employeeID',$last_id);


        if($stmt4->execute()){

        } else{
            echo "<div class='alert alert-danger'>Unable to ajijjijudd employee.</div>";
        }

        }
      }
      
      
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


$body = "Hi <b> $firstName! </b><br><br>

Log-in details for your account in our Human Resource Information System (HRIS): <br><br>

Username: <b> $empID </b><br>
Password: <b> $password </b> <br><br>

To access your account, go to qualisph.com/index.php <br><br>

This is an automated generated email.  Please do not reply. <br><br>

Thank you.";

$mail->SetFrom('info@qualisph.com', 'Qualis Consulting, Inc.');
$mail->Subject = "Your HRIS Login details";
$mail->MsgHTML($body);
$mail->AddAddress($email, $email);

if($mail->Send()) {
  echo "Message sent!";
} else {
  echo "Mailer Error: " . $mail->ErrorInfo;
}
    // END EMAIL
   

      echo "
          <script>
              window.open('add_employee.php?add_employee=success&empID=$empID','_self');
          </script>
      ";
      $query5 = "INSERT INTO tbl_dependents SET
                  employeeCode=:employeeID,
                  dependentName=:dependentName,
                  dependentRelation=:dependentRelation
  ";
    $stmt5=$con->prepare($query5);

    $dependentName = htmlspecialchars(strip_tags($_POST['dependentName']));
    $dependentRelation = htmlspecialchars(strip_tags($_POST['dependentRelation']));

    $stmt5->bindparam(':employeeID',$empID);
    $stmt5->bindparam(':dependentName',$dependentName);
    $stmt5->bindparam(':dependentRelation',$dependentRelation);

    $stmt5->execute();

    $query6 = "INSERT INTO tbl_attendancedetails SET
    employeeCode=:employeeCode,
    attendanceDetails_maxtimein=:maxtimein";

    $stmt6=$con->prepare($query6);
    $maxtimein = 3;

    $stmt6->bindparam(':employeeCode',$empID);
    $stmt6->bindparam(':maxtimein',$maxtimein);

    $stmt6->execute();
    

    }else{

        echo "
            <script>
                window.open('add_employee.php?add_employee=failed','_self');
            </script>
        ";
    }


    }

    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
        error_log($e->getMessage());
    }
// }else{
//   echo "tet;";
// }
?>
