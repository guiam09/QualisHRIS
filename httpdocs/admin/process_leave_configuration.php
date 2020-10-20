<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
$date = date_create()->format('Y-m-d H:i:s');
if (isset($_POST['addLeave'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_leave SET leaveName=:leaveName, leaveCount=:leaveCount, required=:required ";
  $stmt = $con->prepare($query);

  // posted values
  $leaveName=htmlspecialchars(strip_tags($_POST['leaveName']));
  $leaveCount=htmlspecialchars(strip_tags($_POST['leaveCount']));

  $required = $_POST['required'];

  // bind the parameters
  $stmt->bindParam(':leaveName', $leaveName);
  $stmt->bindParam(':leaveCount', $leaveCount);
  $stmt->bindParam(':required', $required);

  // Execute the query
  
   // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Leave Configurations';
  $old_data = '';
  $new_data = $leaveName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_leaves.php?add_leave=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_leaves.php?add_leave=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
  // end of accept leave
}elseif (isset($_POST['editLeave'])){

  $postID = $_POST['leaveID'];
  $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "UPDATE tbl_leave
  SET leaveName=:leaveName, leaveCount=:leaveCount, required=:required
  WHERE leaveID = '$postID'";
  $stmt = $con->prepare($query);

  // posted values
  $leaveName=htmlspecialchars(strip_tags($_POST['leaveName']));
  $leaveCount=htmlspecialchars(strip_tags($_POST['leaveCount']));
  $required = $_POST['required'];
  // bind the parameters
  $stmt->bindParam(':leaveName', $leaveName);
  $stmt->bindParam(':leaveCount', $leaveCount);
  $stmt->bindParam(':required', $required);

  // Execute the query
  
       // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Leave Configurations';
  $old_data = $formerName;
  $new_data = $leaveName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_leaves.php?edit_leaveType=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_leaves.php?edit_leaveType=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['deleteLeave'])){
  $formerName = $_POST['formerName'];
  $postID = $_POST['leaveID'];
// accept leave
try{
// insert query
$query = "DELETE FROM tbl_leave WHERE leaveID = '$postID'";
$stmt = $con->prepare($query);

// Execute the query
          // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Leave Configurations';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
if($stmt->execute()){
  echo "
      <script>
          window.open('configure_leaves.php?delete_leaveType=success','_self');
      </script>
  ";


}else{
  echo "
      <script>
          window.open('configure_leaves.php?delete_leaveType=failed','_self');
      </script>
  ";
}


}

// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
}elseif(isset($_POST['resetAssignedLeave'])){
  $id = $_POST['id'];
  $emp = $_POST['emp'];
  $formerName = $_POST['formerName'];
  
  $q = "SELECT * FROM tbl_leave JOIN tbl_leaveinfo ON tbl_leave.leaveID = tbl_leaveinfo.leaveID WHERE leaveInfoID = '$id'";
  $s = $con->prepare($q);
  $s->execute();
  $row = $s->fetch(PDO::FETCH_ASSOC);
  $baseLeave = $row['leaveCount'];
  
    $query = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed, allowedLeave=:allowedLeave
  WHERE leaveInfoID=:id";
  $stmt = $con->prepare($query);
  
    // posted values
  $allowedLeave=$baseLeave;
  $leaveUsed=0;
  $leaveRemaining=$baseLeave;

  // bind the parameters
  $stmt->bindParam(':allowedLeave', $allowedLeave);
  $stmt->bindParam(':leaveUsed', $leaveUsed);
  $stmt->bindParam(':leaveRemaining', $leaveRemaining);
  $stmt->bindParam(':id', $id);
  
  
//   AUDIT
 // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Assign Leave Configurations';
  $old_data = '';
  $new_data = 'Reset '.$formerName.' for '.$emp;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
// END AUDIT
  
  if($stmt->execute()){
       echo "
      <script>
          window.open('assign_leave.php?assign_leave=reset','_self');
      </script>
  ";
  }else{
      
  }
  

  
  
}elseif(isset($_POST['updateAssignedLeave'])){
   $id = $_POST['id'];
   $formerName = $_POST['formerName'];
   
     $query = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed, allowedLeave=:allowedLeave
  WHERE leaveInfoID=:id";
  $stmt = $con->prepare($query);
  
    // posted values
  $allowedLeave=htmlspecialchars(strip_tags($_POST['leaveCredits']));
  $leaveUsed=htmlspecialchars(strip_tags($_POST['leaveUsed']));
  $leaveRemaining=htmlspecialchars(strip_tags($_POST['leaveBalance']));

  // bind the parameters
  $stmt->bindParam(':allowedLeave', $allowedLeave);
  $stmt->bindParam(':leaveUsed', $leaveUsed);
  $stmt->bindParam(':leaveRemaining', $leaveRemaining);
  $stmt->bindParam(':id', $id);
  
  
//   AUDIT
 // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Assign Leave Configurations';
  $old_data = '';
  $new_data = 'Updated '.$formerName.' for '.$emp;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
// END AUDIT
  
   if($stmt->execute()){
       echo "
      <script>
          window.open('assign_leave.php?assign_leave=updated','_self');
      </script>
  ";
  }else{
      
  }
  
  
  
}elseif(isset($_POST['deleteAssignedLeave'])){
   $id = $_POST['id'];
   $emp = $_POST['emp'];
   $formerName = $_POST['formerName'];
   
   $query = "DELETE FROM tbl_leaveinfo WHERE leaveInfoID = '$id'";
   $stmt = $con->prepare($query);
   
   // Execute the query
          // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Assign Leave Configurations';
  $old_data = 'Deleted '.$formerName.' for '.$emp;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
if($stmt->execute()){
  echo "
      <script>
          window.open('assign_leave.php?assign_leave=deleted','_self');
      </script>
  ";


}else{
  echo "
      <script>
          window.open('assign_leave.php?assign_leave=failed','_self');
      </script>
  ";
}

   
}elseif(isset($_POST['assignLeave'])){
    

$employee = $_POST['employee'];
$leave = $_POST['leaveName'];
$leaveCredits = $_POST['leaveCredits'];
$leaveUsed = $_POST['leaveUsed'];

 
    $query = "INSERT INTO tbl_leaveinfo 
    SET leaveID=:leaveID, 
    employeeID=:employeeID, 
    leaveRemaining=:leaveRemaining, 
    leaveUsed=:leaveUsed, 
    allowedLeave=:allowedLeave";
  $stmt = $con->prepare($query);
  
    // posted values
  $allowedLeave = !empty($baseLeave) ? $baseLeave : 0;
  $leaveUsed=0;
  $leaveRemaining = !empty($baseLeave) ? $baseLeave : 0;

  // bind the parameters
  $stmt->bindParam(':employeeID', $employee);
  $stmt->bindParam(':leaveID', $leave);
  $stmt->bindParam(':leaveRemaining', $leaveCredits);
  $stmt->bindParam(':leaveUsed', $leaveUsed);
  $stmt->bindParam(':allowedLeave', $leaveCredits);
  
  
//   AUDIT
 // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Assign Leave Configurations';
  $old_data = '';
  $new_data = 'Assigned Leave for ' .$employee;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
// END AUDIT
  
  if($stmt->execute()){
      echo "
      <script>
          window.open('assign_leave.php?assign_leave=added','_self');
      </script>
  ";
  }else{
      print_r($stmt->errorInfo());
      die();
  }

}elseif(isset($_POST['resetAllLeaves'])){
    
    //   AUDIT
 // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Assign Leave Configurations';
  $old_data = 'Old Leave Assigned';
  $new_data = 'Reset Leave Assigned';
  $action = 'RESET LEAVE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
// END AUDIT

    
    $q = "SELECT * FROM tbl_leave";
    $s = $con->prepare($q);
    $s->execute();
    while ($row = $s->fetch(PDO::FETCH_ASSOC)){
        
        $query = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed, allowedLeave=:allowedLeave WHERE leaveID =:leaveID";
        $stmt = $con->prepare($query);
        
        $leaveRemaining = $row['leaveCount'];
        $leaveUsed = 0;
        $allowedLeave = $row['leaveCount'];
        $leaveID = $row['leaveID'];
        
         $stmt->bindParam(':leaveRemaining', $leaveRemaining);
         $stmt->bindParam(':leaveUsed', $leaveUsed);
         $stmt->bindParam(':allowedLeave', $allowedLeave);
         $stmt->bindParam(':leaveID', $leaveID);
         
        $stmt->execute();
    }
      echo "
      <script>
          window.open('assign_leave.php?assign_leave=resetAll','_self');
      </script>
  ";
    
}
?>
