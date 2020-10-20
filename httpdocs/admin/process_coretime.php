<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
   $date = date_create()->format('Y-m-d H:i:s');
if (isset($_POST['addCoretime'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_coretime SET coretimeName=:coretimeName, timeIn=:timeIn, timeOut=:timeOut";
  $stmt = $con->prepare($query);

  // posted values
$coretimeName=htmlspecialchars(strip_tags($_POST['coretimeName']));
$timeIn=htmlspecialchars(strip_tags($_POST['timeIn']));
$timeOut=htmlspecialchars(strip_tags($_POST['timeOut']));
  $timeIn  = date("H:i", strtotime($timeIn));
  $timeOut  = date("H:i", strtotime($timeOut));
  // bind the parameters
  $stmt->bindParam(':coretimeName', $coretimeName);
  $stmt->bindParam(':timeIn', $timeIn);
  $stmt->bindParam(':timeOut', $timeOut);
  // Execute the query
  if($stmt->execute()){
      
   
        // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Coretime Configurations';
  $old_data = '';
  $new_data = $coretimeName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);


        echo "
        <script>
            window.open('configure_coretime.php?add_coretime=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_coretime.php?add_coretime=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['editCoreTime'])){
  $id = $_POST['coreTimeID'];
  $formerName = $_POST['formerName'];
  
  
  // accept leave
  try{
      

      
  // insert query
  $query = "UPDATE tbl_coretime
  SET coretimeName=:coretimeName, timeIn=:timeIn, timeOut=:timeOut
  WHERE coreTimeID = '$id'";
  $stmt = $con->prepare($query);

  $coretimeName=htmlspecialchars(strip_tags($_POST['coretimeName']));
  $timeIn=htmlspecialchars(strip_tags($_POST['timeIn']));
  $timeOut=htmlspecialchars(strip_tags($_POST['timeOut']));
  $timeIn  = date("H:i", strtotime($timeIn));
   $timeOut  = date("H:i", strtotime($timeOut));
    // bind the parameters
    $stmt->bindParam(':coretimeName', $coretimeName);
    $stmt->bindParam(':timeIn', $timeIn);
    $stmt->bindParam(':timeOut', $timeOut);

                // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Coretime Configurations';
  $old_data = $formerName;
  $new_data = $coretimeName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  // Execute the query
  if($stmt->execute()){
      

      
    echo "
        <script>
            window.open('configure_coretime.php?edit_coretime=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_coretime.php?edit_coretime=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}
elseif (isset($_POST['deleteCoreTime'])){
    $id = $_POST['coreTimeID'];
    $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "DELETE FROM tbl_coretime WHERE coreTimeID = '$id'";
  $stmt = $con->prepare($query);
  
             // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Coretime Configurations';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      

  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_coretime.php?delete_coretime=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_coretime.php?delete_coretime=failed','_self');
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
