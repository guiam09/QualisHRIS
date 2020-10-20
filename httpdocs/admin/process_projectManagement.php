<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
$date = date_create()->format('Y-m-d H:i:s');

if (isset($_POST['addProject'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_project SET project_name=:projectName";
  $stmt = $con->prepare($query);

  // posted values
  $projectName=htmlspecialchars(strip_tags($_POST['projectName']));
  // bind the parameters
  $stmt->bindParam(':projectName', $projectName);
  // Execute the query
  
    // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Project Management';
  $sub_module = 'Project Management';
  $old_data = '';
  $new_data = $projectName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('projectManagement.php?add_project=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('projectManagement.php?add_project=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['editProject'])){
  $projectID = $_POST['projectID'];
  $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "UPDATE tbl_project
  SET project_name=:projectName
  WHERE project_ID = '$projectID'";
  $stmt = $con->prepare($query);

  // posted values
  $projectName=htmlspecialchars(strip_tags($_POST['projectName']));

  // bind the parameters
  $stmt->bindParam(':projectName', $projectName);

  // Execute the query
  
     // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Project Management';
  $sub_module = 'Project Management';
  $old_data = $formerName;
  $new_data = $projectName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('projectManagement.php?edit_project=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('projectManagement.php?edit_project=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}
elseif (isset($_POST['deleteProject'])){
  $projectID = $_POST['projectID'];
   $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "DELETE FROM tbl_project WHERE project_ID = '$projectID'";
  $stmt = $con->prepare($query);

  // Execute the query
  
           // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Project Management';
  $sub_module = 'Project Management';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('projectManagement.php?delete_project=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('projectManagement.php?delete_project=failed','_self');
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
