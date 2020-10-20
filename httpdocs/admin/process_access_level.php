<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');
include ('auditLog.php');
   $date = date_create()->format('Y-m-d H:i:s');
$user = $_SESSION['user_id'];


       

   

if (isset($_POST['addAccessLevel'])){

  // accept leave
  try{
  // insert query
  $query = "INSERT INTO tbl_accesslevels SET accessLevelName=:accessLevelName";
  $stmt = $con->prepare($query);

  // posted values
  $accessLevelName=htmlspecialchars(strip_tags($_POST['accessLevelName']));
  // bind the parameters
  $stmt->bindParam(':accessLevelName', $accessLevelName);
  // Execute the query
  
   // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Access Level Configurations';
  $old_data = '';
  $new_data = $accessLevelName;
  $action = 'ADD';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
      
  if($stmt->execute()){

  $last_id = $con->lastInsertId();
  $employees = $_POST['employees'];

    
  foreach ($employees as $e){

     $query2 = "INSERT INTO tbl_accessLevelEmp SET accessLevelID=:accessLevelID, employeeID=:employeeID";
     $stmt2 = $con->prepare($query2);
  
     $stmt2->bindParam(':accessLevelID', $last_id);
     $stmt2->bindParam(':employeeID', $e);
     
     if($stmt2->execute()){
         echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";
  
     }
     else{
           echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";
     }
     
   
     
     
     
  }
  
  foreach ($employees as $em){


        $AL = 1;   
     $insertQuery = "UPDATE tbl_employees
  SET accessLevel=:al
  WHERE employeeID=:employeeID";
     $iq = $con->prepare($insertQuery);
  
     $iq->bindParam(':al', $AL);
     $iq->bindParam(':employeeID', $em);
     
     if($iq->execute()){
            echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";
     }
  }
  
     $modules = $_POST['modulesAccesed'];


      foreach ($modules as $m){

     $query3 = "INSERT INTO tbl_accessLevelModules SET accessLevelID=:accessLevelID, accessedModules=:accessedModules";
     $stmt3 = $con->prepare($query3);
  
     $stmt3->bindParam(':accessLevelID', $last_id);
     $stmt3->bindParam(':accessedModules', $m);
     
     if($stmt3->execute()){
           echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";
  
     }
     else{
         echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";
     }
  }



       echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=success','_self');
        </script>
    ";





  }else{
    echo "
        <script>
            window.open('configure_accesslevels.php?add_access_level=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }



 }
 
 
elseif (isset($_POST['editAccessLevel'])){
  $id = $_POST['accessLevelID'];
    $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "UPDATE tbl_accesslevels
  SET accessLevelName=:accessLevelName
  WHERE accessLevelID = '$id'";
  $stmt = $con->prepare($query);

  // posted values
  $accessLevelName=htmlspecialchars(strip_tags($_POST['accessLevelName']));

  // bind the parameters
  $stmt->bindParam(':accessLevelName', $accessLevelName);

  // Execute the query
  
        // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Access Level Configurations';
  $old_data = $formerName;
  $new_data = $accessLevelName;
  $action = 'UPDATE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
      
    $employees = $_POST['employees'];
      
     $d = "DELETE FROM tbl_accessLevelEmp WHERE accessLevelID ='".$id."'";
     $ds = $con->prepare($d);
     $ds->execute();
      


     foreach ($employees as $e){

     $query2 = "INSERT INTO tbl_accessLevelEmp SET accessLevelID=:accessLevelID, employeeID=:employeeID";
     $stmt2 = $con->prepare($query2);
  
     $stmt2->bindParam(':accessLevelID', $id);
     $stmt2->bindParam(':employeeID', $e);
     
     if($stmt2->execute()){
         echo "
        <script>
            window.open('configure_accesslevels.php?edit_access_level=success','_self');
        </script>
    ";
  
     }
     else{
           echo "
        <script>
            window.open('configure_accesslevels.php?edit_access_level=failed','_self');
        </script>
    ";
     }
  }
  
   


  }else{
    echo "
        <script>
            window.open('configure_accesslevels.php?edit_access_level=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }
}
elseif (isset($_POST['deleteAccessLevel'])){
    $id = $_POST['accessLevelID'];
        $formerName = $_POST['formerName'];
  // accept leave
  try{
  // insert query
  $query = "DELETE FROM tbl_accesslevels WHERE accessLevelID = '$id'";
  $stmt = $con->prepare($query);

  // Execute the query
    // posted values
  $modified_by_id = $_SESSION['employeeID'];
  $modified_timestamp = $date;
  $module = 'Configurations';
  $sub_module = 'Access Level Configurations';
  $old_data = $formerName;
  $new_data = '';
  $action = 'DELETE';
      audit($con, $modified_by_id, $modified_timestamp , $module, $sub_module, $old_data, $new_data, $action);
      
      
  if($stmt->execute()){
    echo "
        <script>
            window.open('configure_accesslevels.php?delete_access_level=success','_self');
        </script>
    ";


  }else{
    echo "
        <script>
            window.open('configure_accesslevels.php?delete_access_level=failed','_self');
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
