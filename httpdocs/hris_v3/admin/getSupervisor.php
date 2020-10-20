<?php
include '../db/connection.php';

if (!empty($_POST['departmentID'])){

    $deptID = $_POST['departmentID'];
    // select all data
    $query = "SELECT * FROM tbl_employees WHERE departmentID = '$deptID'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    // check if more than 0 record found
    if($num>0){
        echo '<option disabled selected value="">Select Supervisor </option>';
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo '<option value="'.$row['employeeID'].'">'.$row['firstName']. ' ' . $row['lastName'] . '</option>';

     }

     }else{
       echo "no records found";
     }

}




 ?>
