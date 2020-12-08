<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');


  $userId = $_SESSION['user_id'];
  $query = "SELECT emailAddress FROM tbl_employees WHERE employeeCode = '$userId'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  // check if more than 0 record found
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $currentEmail = $row['emailAddress'];
  
if (isset($_POST['enteredEmail'])) {
 
  try{
        $queryUpdate = "UPDATE tbl_employees SET emailAddress=:enteredEmail WHERE employeeCode='$userId'";
        $stmt = $con->prepare($queryUpdate);

        // enter value parameters
        $enteredEmail = htmlspecialchars(strip_tags($_POST['enteredEmail']));
        // bind the parameters
        $stmt->bindParam(':enteredEmail', $enteredEmail);

        // Execute the query
        if($stmt->execute()){
          echo "
              <script>
                  window.open('account_settings.php?update_email=success','_self');
              </script>
          ";
        }else {
          echo "
              <script>
                  window.open('account_settings.php?update_email=failed','_self');
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
