<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');

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

  $postID = $_POST['leaveID'];
// accept leave
try{
// insert query
$query = "DELETE FROM tbl_leave WHERE leaveID = '$postID'";
$stmt = $con->prepare($query);

// Execute the query
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
}else{
  echo "tet";
}
?>
