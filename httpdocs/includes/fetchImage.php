<?php
include ('../db/connection.php');

function getImage($con, $searchQuery){
  $searchq = $searchQuery;
  $query = "SELECT * FROM tbl_employees INNER JOIN tbl_department ON tbl_employees.departmentID = tbl_department.departmentID
  INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE employeeCode = '$searchq'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  // check if more than 0 record found
  if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      return $row;
}
}
}

?>