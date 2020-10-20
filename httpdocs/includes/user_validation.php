<?php
include ('../db/connection.php');


function getEmployee_AccessLevel($con, $searchQuery){
  $searchq = $searchQuery;
  $query = "SELECT employeeID, accessedModules, accessLevelName FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID 
WHERE tbl_accessLevelEmp.employeeID = '$searchq'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
   
      return $stmt;


}

 ?>
