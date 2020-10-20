<?php
include_once ('../includes/configuration.php');
include '../db/connection.php';

if (!empty($_POST['currentPassword'])){

    $id =  $_SESSION['user_id'];
    $pass = $_POST['currentPassword'];
    // select all data
    $query = "SELECT * FROM tbl_employees WHERE password = '$pass' AND employeeCode = '$id' ";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    // check if more than 0 record found
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

      echo "<span class='text-success'> Password is Correct </span>";

     }

     }else{

       echo "<span class='text-danger'> Invalid Password</span>";

     }

}




 ?>
