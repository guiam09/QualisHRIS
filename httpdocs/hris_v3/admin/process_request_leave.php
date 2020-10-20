<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');

if (isset($_POST['requestLeave'])){

  $start = strtotime($_POST['startDate']);
  $end = strtotime($_POST['endDate']);

  $timediff = abs($end - $start);

  $out = ($timediff/86400) + 1;
  $user = $_SESSION['user_id'];
  $leave = $_POST['leaveType'];

  $duration = $out;

  $q = "SELECT * FROM tbl_leaveinfo WHERE employeeCode ='$user' AND leaveID = '$leave'";
  $statement = $con->prepare($q);
  $statement->execute();
  $rowx = $statement->fetch(PDO::FETCH_ASSOC);

  $q2 = "SELECT * tbl_leave WHERE leaveID ='$leave'";
  $statement2 = $con->prepare($q2);
  $statement2->execute();
  $rowz = $statement2->fetch(PDO::FETCH_ASSOC);

  $leaveRemaining = $rowx['leaveRemaining'];
  $totalDuration = $leaveRemaining + $duration;

  $leaveName = $rowz['leaveName'];


  if ($rowx['leaveRemaining'] == 0){
  echo "
      <script>
          window.open('leave_application.php?request=used_all_leave','_self');
      </script>
  ";
}elseif ($duration <= $leaveRemaining) {


// accept leave
try{
// insert query
$query = "INSERT INTO tbl_leavedetails SET employeeCode=:employeeCode, duration=:duration, leaveFrom=:leaveFrom, leaveTo=:leaveTo, reason=:reason, leaveStatus=:leaveStatus, approval=:approval, dateFiled=:dateFiled,
leaveID=:leaveID";
$stmt = $con->prepare($query);

// posted values
$employeeCode= htmlspecialchars(strip_tags($_SESSION['user_id']));
// $duration=htmlspecialchars(strip_tags($_POST['numDays']));
$startDate=htmlspecialchars(strip_tags($_POST['startDate']));
$endDate=htmlspecialchars(strip_tags($_POST['endDate']));
$reason=htmlspecialchars(strip_tags($_POST['reason']));
$status = 'Pending';
$approval=htmlspecialchars(strip_tags($_POST['search']));
$dateNow = date('Y-m-d');
$leaveType= htmlspecialchars(strip_tags($_POST['leaveType']));


//converting one date format into another
$startDate = date("Y/m/d", strtotime($startDate));
$endDate = date("Y/m/d", strtotime($endDate));



// bind the parameters
$stmt->bindParam(':employeeCode', $employeeCode);
$stmt->bindParam(':duration', $out);
$stmt->bindParam(':leaveFrom', $startDate);
$stmt->bindParam(':leaveTo', $endDate);
$stmt->bindParam(':reason', $reason);
$stmt->bindParam(':leaveStatus', $status);
$stmt->bindParam(':approval', $approval);
$stmt->bindParam(':dateFiled', $dateNow);
$stmt->bindParam(':leaveID', $leaveType);

// Execute the query
if($stmt->execute()){
  echo "
      <script>
          window.open('leave_application.php?request=success','_self');
      </script>
  ";


}else{
  echo "
      <script>
          window.open('leave_application.php?request=failed','_self');
      </script>
  ";
}


}

// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
// end of accept leave


  }else {
    echo "
        <script>
            window.open('leave_application.php?request=exceeded','_self');
        </script>
    ";

}

}elseif (isset($_POST['cancelLeave'])) {
  try{
    $id = $_POST['leaveDetailsID'];
    $q2 = "SELECT * FROM tbl_leavedetails WHERE leaveDetailsID ='$id'";
    $statement2 = $con->prepare($q2);
    $statement2->execute();
    $rowz = $statement2->fetch(PDO::FETCH_ASSOC);
  echo  $status = $rowz['leaveStatus'];
    $empCode = $rowz['employeeCode'];
    $leaveID = $rowz['leaveID'];
    $q3 = "SELECT * FROM tbl_leaveinfo WHERE employeeCode = '$empCode' AND leaveID = $leaveID";
    $statement3 = $con->prepare($q3);
    $statement3->execute();
    $rowq = $statement3->fetch(PDO::FETCH_ASSOC);

    $leaveTableID = $rowq['leaveInfoID'];
  echo  $exisitngLeaveRemaining = $rowq['leaveRemaining'];
  echo "<br>";
  echo   $existingLeaveUsed  = $rowq['leaveUsed'];
  echo "<br>";
  echo  $existingDuration = $rowz['duration'];
echo "<br>";
if($status == "Approved"){
  echo $leaveRemaining = $existingDuration + $exisitngLeaveRemaining;
  echo "<br>";
  echo $leaveUsed = $existingLeaveUsed - $existingDuration;

}

  // insert query
  $queryUpdate = "UPDATE tbl_leavedetails SET leaveStatus=:status WHERE leaveDetailsID = '$id'";
  $stmt = $con->prepare($queryUpdate);

  $status = 'Cancelled';
  // bind the parameters
  $stmt->bindParam(':status', $status);
  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
            window.open('leave_application.php?request=cancelled','_self');
        </script>
    ";
    if($status == "Approved"){
      $updateLeave = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed WHERE leaveInfoID = '$leaveTableID'";
      $updateStmt = $con->prepare($updateLeave);


      // bind the parameters
      $updateStmt->bindParam(':leaveRemaining', $leaveRemaining);
      $updateStmt->bindParam(':leaveUsed', $leaveUsed);
      // Execute the query
      if($updateStmt->execute()){

      }
    }
  }else{
    echo "
        <script>
            window.open('leave_application.php?request=cancelled_failed','_self');
        </script>
    ";

  }

  }
  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}else {
  // code...
}
 ?>
