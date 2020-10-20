<?php
include '../db/connection.php';

if (!empty($_POST['firstName'])){

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    // select all data
    $query = "SELECT * FROM tbl_employees WHERE firstName = '$firstName' AND lastName = '$lastName'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    // check if more than 0 record found
    if($num>0){
      echo "yes";

     }else{
      echo "no";
     }

}




 ?>
