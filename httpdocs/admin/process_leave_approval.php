<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');

if (isset($_POST['approve'])){
  $employeeID = $_POST['employeeID'];
  $leaveType = $_POST['leaveType'];
  $leaveDetails = $_POST['leaveDetailsID'];
  $q = "SELECT * FROM tbl_leaveinfo INNER JOIN tbl_leavedetails ON tbl_leaveinfo.employeeID = tbl_leavedetails.employeeID WHERE tbl_leavedetails.leaveDetailsID = '$leaveDetails' AND tbl_leaveinfo.leaveID = '$leaveType'";
  $statement = $con->prepare($q);
  $statement->execute();
  $rowd = $statement->fetch(PDO::FETCH_ASSOC);

  $leaveRemaining = $rowd['leaveRemaining'];
  $durations = $rowd['duration'];

  if ($leaveRemaining == 0){
    echo "
        <script>
            window.open('leave_management.php?leave_result=used_all_leave','_self');
        </script>
    ";
    $queryUpdate6 = "UPDATE tbl_leavedetails SET leaveStatus=:status WHERE leaveDetailsID = '$leaveDetails'";
    $stmt6 = $con->prepare($queryUpdate6);

    $status = 'Cancelled';
    // bind the parameters
    $stmt6->bindParam(':status', $status);
    // Execute the query
    if($stmt6->execute()){

    }
  }elseif ($durations <= $leaveRemaining) {
    //approve leave
    try{
    $id = $_POST['leaveDetailsID'];

    $employeeID = $_POST['employeeID'];
    // insert query
    $queryUpdate = "UPDATE tbl_leavedetails SET leaveStatus=:Approved, approver=:approver WHERE leaveDetailsID = '$id'";
    $stmt = $con->prepare($queryUpdate);

    $approved = 'Approved';
    // bind the parameters
    $stmt->bindParam(':Approved', $approved);
    $stmt->bindParam(':approver', $user);

    // Execute the query
    if($stmt->execute()){
      // echo "
      //     <script>
      //         window.open('leave_management.php?leave_result=approved','_self');
      //     </script>
      // ";
      // echo "<div class='alert alert-success'>Leave Approved!</div>";
      $employeeID = $_POST['employeeID'];
      $leaveID = $_POST['leaveType'];
      $queryUpdate5 = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed WHERE employeeID = '$employeeID' AND leaveID = '$leaveID'";

      $stmt5 = $con->prepare($queryUpdate5);

      $q = "SELECT * FROM tbl_leaveinfo WHERE employeeID = '$employeeID' AND leaveID = $leaveID";
      $stmt9 = $con->prepare($q);
      $stmt9->execute();
      $rowz = $stmt9->fetch(PDO::FETCH_ASSOC);

      $z = "SELECT * FROM tbl_leavedetails WHERE leaveDetailsID = '$id'";
      $stmt10 = $con->prepare($z);
      $stmt10->execute();
      $row = $stmt10->fetch(PDO::FETCH_ASSOC);

       $currentLeaveRemain = $rowz['leaveRemaining'];

       $duration = $row['duration'];

      $leaveRemaining = $currentLeaveRemain - $duration;

     $currentLeaveUsed = $rowz['leaveUsed'];

     $leaveUsed =$currentLeaveUsed + $duration;

      // bind the parameters
      $stmt5->bindParam(':leaveRemaining', $leaveRemaining);
      $stmt5->bindParam(':leaveUsed', $leaveUsed);

      // Execute the query
      if($stmt5->execute()){

          echo "
              <script>
                  window.open('leave_management.php?leave_result=approved','_self');
              </script>
          ";
      }else{
        echo "
            <script>
                window.open('leave_management.php?leave_result=failed','_self');
            </script>
        ";
      }

    }else{
      echo "
          <script>
              window.open('leave_management.php?leave_result=failed','_self');
          </script>
      ";
    }


    }
    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }

    //end approve leave

  }else {
    echo "
        <script>
            window.open('leave_management.php?leave_result=exceeded','_self');
        </script>
    ";
    $queryUpdate7 = "UPDATE tbl_leavedetails SET leaveStatus=:status WHERE leaveDetailsID = '$leaveDetails'";
    $stmt7 = $con->prepare($queryUpdate7);

    $status = 'Cancelled';
    // bind the parameters
    $stmt7->bindParam(':status', $status);
    // Execute the query
    if($stmt7->execute()){

    }

  }

}elseif (isset($_POST['decline'])) {
  $leaveDetails = $_POST['leaveDetailsID'];
  $declineQuery = "UPDATE tbl_leavedetails SET leaveStatus=:status WHERE leaveDetailsID = '$leaveDetails'";
  $decstmt = $con->prepare($declineQuery);

  $status = 'Declined';
  // bind the parameters
  $decstmt->bindParam(':status', $status);
  // Execute the query
  if($decstmt->execute()){
    echo "
        <script>
            window.open('leave_management.php?leave_result=declined','_self');
        </script>
    ";
  }else {
    echo "
        <script>
            window.open('leave_management.php?leave_result=decline_failed','_self');
        </script>
    ";
  }


}

 ?>
