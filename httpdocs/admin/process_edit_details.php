<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');


 $searchq =   $_SESSION['user_id'];
  $query = "SELECT password FROM tbl_employees WHERE employeeCode = '$searchq'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  // check if more than 0 record found
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $currentPassword = $row['password'];
  
if (isset($_POST['updatePassword'])) {
 
  $enteredCurrentPassword = $_POST['currentPassword'];

  try{
    if($currentPassword == $enteredCurrentPassword){
        // echo "<div class='alert alert-info'>Password Unchanged!</div>";


        $queryUpdate = "UPDATE tbl_employees SET password=:password WHERE employeeCode = '$searchq'";
        $stmt = $con->prepare($queryUpdate);
        $password=htmlspecialchars(strip_tags($_POST['newPassword']));
        // bind the parameters
        $stmt->bindParam(':password', $password);

        // Execute the query
        if($stmt->execute()){
          echo "
              <script>
                  window.open('account_settings.php?update_password=success','_self');
              </script>
          ";
        }else {
          echo "
              <script>
                  window.open('account_settings.php?update_password=failed','_self');
              </script>
          ";
        }

    }else {
      ?>
      <script type="text/javascript">
          alert("Invalid Current Password.");
      </script>

        <?php
        echo "
            <script>
                window.open('account_settings.php?update_password=failed','_self');
            </script>
        ";
    }

  }
  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }


if (isset($_GET['update'])){

    if($_GET['update'] == "successful") {
        echo "<div class='alert alert-success'>Update Success!</div>";
    }elseif ($_GET['update'] == "unchanged") {
        echo "<div class='alert alert-danger'>Password Unchanged!</div>";
    }elseif ($_GET['update'] == "error") {
        echo "<div class='alert alert-danger'>Update Failed!</div>";
    }
}

}elseif (isset($_POST['updateImage'])) {
  try{
        $user = $_SESSION['user_id'];
  // insert query
  $queryUpdate = "UPDATE tbl_employees SET photo=:photo WHERE employeeCode=:employeeCode";
    // $query = "INSERT INTO tbl_employees (employeeID, firstName, lastName, middleInitial, birthdate, gender, contactNumber, address, dateHired, dateStarted, position, department, reportingTo)
    // VALUES ('$employeeID', '$firstName', '$lastName', '$middleInitial', '$birthDate', '$gender', '$contactNumber', '$address', '$dateHired', '$dateStarted', '$position', '$department', '$reportingTo')";
  // prepare query for execution
  $stmt = $con->prepare($queryUpdate);

  $Allow = array("jpg", "jpeg", "png");
  $ToDirectory = "../Images/";
  if(!!$_FILES['Image']['tmp_name']) {
      $Info = explode('.', strtolower($_FILES['Image']['name']));
      $Image = round(microtime(true)) . '.' . end ($Info);
      if(in_array(end($Info), $Allow)) {
          if(move_uploaded_file($_FILES['Image']['tmp_name'], $ToDirectory . $Image)) {

          }
      }
  }

  // bind the parameters
  $stmt->bindParam(':photo', $Image);

    $stmt->bindParam(':employeeCode', $user);

  // Execute the query
  if($stmt->execute()){
        echo "<div class='alert alert-success'>Update Success!</div>";
  }else{
        echo "<div class='alert alert-success'>Update Failed!</div>";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif(isset($_POST['editUsername'])){
    

    $enteredCurrentPassword = $_POST['currentPassword2'];
    
      try{
    if($currentPassword == $enteredCurrentPassword){
        // echo "<div class='alert alert-info'>Password Unchanged!</div>";


        $queryUpdate = "UPDATE tbl_employees SET username=:username WHERE employeeCode = '$searchq'";
        $stmt = $con->prepare($queryUpdate);
        $username=htmlspecialchars(strip_tags($_POST['username']));
        // bind the parameters
        $stmt->bindParam(':username', $username);

        // Execute the query
        if($stmt->execute()){
          echo "
              <script>
                  window.open('account_settings.php?update_password=usernameChanged','_self');
              </script>
          ";
        }else {
          echo "
              <script>
                  window.open('account_settings.php?update_password=failedToUpdateUsername','_self');
              </script>
          ";
        }

    }else {
      ?>
      <script type="text/javascript">
          alert("Invalid Current Password.");
      </script>

        <?php
        echo "
            <script>
                window.open('account_settings.php?update_password=failedToUpdateUsername','_self');
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
