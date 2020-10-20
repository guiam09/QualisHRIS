<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');


if (isset($_POST['edit_currentPosition'])) {
  try{
        $user = $_POST['editedUser'];
  // insert query
  $queryUpdate = "UPDATE tbl_employees SET departmentID=:department, address=:location, positionID=:position, reportingTo=:reportingTo, civilStatus=:civilStatus, contactInfo=:contactNumber WHERE employeeCode=:employeeCode";
    // $query = "INSERT INTO tbl_employees (employeeID, firstName, lastName, middleInitial, birthdate, gender, contactNumber, address, dateHired, dateStarted, position, department, reportingTo)
    // VALUES ('$employeeID', '$firstName', '$lastName', '$middleInitial', '$birthDate', '$gender', '$contactNumber', '$address', '$dateHired', '$dateStarted', '$position', '$department', '$reportingTo')";
  // prepare query for execution
  $stmt = $con->prepare($queryUpdate);

  // posted values
  $department=htmlspecialchars(strip_tags($_POST['department']));
  $location=htmlspecialchars(strip_tags($_POST['location']));
  $position=htmlspecialchars(strip_tags($_POST['position']));
  $reportingTo=htmlspecialchars(strip_tags($_POST['reportingTo']));
  $civilStatus=htmlspecialchars(strip_tags($_POST['civilStatus']));
  $contactNumber=htmlspecialchars(strip_tags($_POST['contactNumber']));



  // bind the parameters
  $stmt->bindParam(':department', $department);
  $stmt->bindParam(':location', $location);
  $stmt->bindParam(':position', $position);
  $stmt->bindParam(':reportingTo', $reportingTo);
  $stmt->bindParam(':employeeCode', $user);
  $stmt->bindParam(':civilStatus', $civilStatus);
  $stmt->bindParam(':contactNumber', $contactNumber);



  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
         window.open('update_employee_details.php?searchq=$user&search_result=successful','_self');
        </script>
    ";
  }else{
    echo "
        <script>
            window.open('update_employee_details.php?searchq=$user&search_result=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['updateImage'])) {
  try{
       $user = $_POST['editedUser'];
  // insert query
  $queryUpdate = "UPDATE tbl_employees SET photo=:photo WHERE employeeCode=:employeeCode";
    // $query = "INSERT INTO tbl_employees (employeeID, firstName, lastName, middleInitial, birthdate, gender, contactNumber, address, dateHired, dateStarted, position, department, reportingTo)
    // VALUES ('$employeeID', '$firstName', '$lastName', '$middleInitial', '$birthDate', '$gender', '$contactNumber', '$address', '$dateHired', '$dateStarted', '$position', '$department', '$reportingTo')";
  // prepare query for execution
  $stmt = $con->prepare($queryUpdate);

  $Allow = array("jpg", "jpeg", "png");
  $ToDirectory = "../images/";
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
    echo "
        <script>
            window.open('update_employee_details.php?searchq=$user&search_result=successful','_self');
        </script>
    ";
  }else{
    echo "
        <script>
              window.open('update_employee_details.php?searchq=$user&search_result=failed','_self');
        </script>
    ";
  }


  }

  // show error
  catch(PDOException $exception){
      die('ERROR: ' . $exception->getMessage());
  }

}elseif (isset($_POST['editID'])) {
  try{
   $user = $_POST['editedUser'];
  // insert query
  $queryUpdate = "UPDATE tbl_employees SET sssID=:sssID, philhealthID=:philhealthID, tinID=:tinID, pagibigID=:pagibigID WHERE employeeCode=:employeeCode";
    // $query = "INSERT INTO tbl_employees (employeeID, firstName, lastName, middleInitial, birthdate, gender, contactNumber, address, dateHired, dateStarted, position, department, reportingTo)
    // VALUES ('$employeeID', '$firstName', '$lastName', '$middleInitial', '$birthDate', '$gender', '$contactNumber', '$address', '$dateHired', '$dateStarted', '$position', '$department', '$reportingTo')";
  // prepare query for execution
  $stmt = $con->prepare($queryUpdate);

  // posted values
 $sssID=htmlspecialchars(strip_tags($_POST['sssID']));
  $philhealthID=htmlspecialchars(strip_tags($_POST['philhealthID']));
  $tinID=htmlspecialchars(strip_tags($_POST['tinID']));
  $pagibigID=htmlspecialchars(strip_tags($_POST['pagibigID']));

  // bind the parameters
  $stmt->bindParam(':sssID', $sssID);
  $stmt->bindParam(':philhealthID', $philhealthID);
  $stmt->bindParam(':tinID', $tinID);
  $stmt->bindParam(':employeeCode', $user);
  $stmt->bindParam(':pagibigID', $pagibigID);
  // Execute the query
  if($stmt->execute()){
    echo "
        <script>
           window.open('update_employee_details.php?searchq=$user&search_result=successful','_self');
        </script>
    ";
  }else{
    echo "
        <script>
           window.open('update_employee_details.php?searchq=$user&search_result=failed','_self');
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
