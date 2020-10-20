<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
   $date = date_create()->format('Y-m-d H:i:s');
$user = $_SESSION['user_id'];
if (isset($_POST['addDepartment'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_department SET departmentName=:departmentName";
  $stmt = $con->prepare($query);

  // posted values
  $departmentName=htmlspecialchars(strip_tags($_POST['departmentName']));
  // bind the parameters
  $stmt->bindParam(':departmentName', $departmentName);
  // Execute the query
          // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Designation Configurations';
  $old_data = '';
  $new_data = $departmentName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);


  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?add_department=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php?add_department=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['editDepartment'])){
  $deptID = $_POST['departmentID'];
  $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "UPDATE tbl_department
  SET departmentName=:departmentName
  WHERE departmentID = '$deptID'";
  $stmt = $con->prepare($query);

  // posted values
  $departmentName=htmlspecialchars(strip_tags($_POST['departmentName']));

  // bind the parameters
  $stmt->bindParam(':departmentName', $departmentName);
  
           // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Designation Configurations';
  $old_data = $formerName;
  $new_data = $departmentName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);

  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?edit_department=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php?edit_department=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}
elseif (isset($_POST['deleteDepartment'])){
    $deptID = $_POST['departmentID'];
    $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "DELETE FROM tbl_department WHERE departmentID = '$deptID'";
  $stmt = $con->prepare($query);
    
      // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Designation Configurations';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?delete_department=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php?delete_department=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}



elseif (isset($_POST['addPosition'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_position SET positionName=:positionName, departmentID=:departmentID, addedBy=:user";
  $stmt = $con->prepare($query);

  // posted values
  $positionName=htmlspecialchars(strip_tags($_POST['positionName']));
  $departmentID=htmlspecialchars(strip_tags($_POST['departmentName']));
  // bind the parameters
  $stmt->bindParam(':positionName', $positionName);
  $stmt->bindParam(':departmentID', $departmentID);
  $stmt->bindParam(':user', $user);
  // Execute the query
        // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Position Configurations';
  $old_data = '';
  $new_data = $positionName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?add_position=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php.php?add_position=failed','_self');
        </script>
    ";

  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['editPosition'])){
  $postID = $_POST['positionID'];
  
    $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "UPDATE tbl_position
  SET positionName=:positionName, departmentID=:departmentID, modifiedBy=:user
  WHERE positionID = '$postID'";
  $stmt = $con->prepare($query);

  // posted values
  $positionName=htmlspecialchars(strip_tags($_POST['positionName']));
  $department=htmlspecialchars(strip_tags($_POST['department']));

  // bind the parameters
  $stmt->bindParam(':positionName', $positionName);
  $stmt->bindParam(':departmentID', $department);
  $stmt->bindParam(':user', $user);
  // Execute the query
          // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Position Configurations';
  $old_data = $formerName;
  $new_data = $positionName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?edit_position=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php?edit_position=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}
elseif (isset($_POST['deletePosition'])){
    $postID = $_POST['positionID'];
      $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "DELETE FROM tbl_position WHERE positionID = '$postID'";
  $stmt = $con->prepare($query);
  // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Position Configurations';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_department.php?delete_position=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_department.php?delete_position=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}


 ?>
