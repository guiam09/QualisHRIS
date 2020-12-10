<?php
include ('../db/connection.php');

function getEmployeeData($con, $searchQuery){
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

function getUserInfo($con, $searchQuery){
  $searchq = $searchQuery;
  $query = "SELECT * FROM tbl_employees WHERE employeeCode = '$searchq'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function getReportingTo($con, $reportingToID){
  $reportingToID = $reportingToID;
  $query2 = "SELECT * FROM tbl_employees WHERE employeeID = '$reportingTo'";
  $stmt7 = $con->prepare($query2);
  $stmt7->execute();
  $rows = $stmt7->fetch(PDO::FETCH_ASSOC);
  return $rows;
}

function getLeaveTypes($con){
  $query = "SELECT * FROM tbl_leave";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  if($num>0){
    return $stmt;
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //   return $row;
    // }
  }

}
function getAccessLevels($con){
  $query = "SELECT * FROM tbl_accesslevels";
  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  if($num>0){
    return $stmt;
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //   return $row;
    // }
  }

}

function getEmployeeID($con, $searchQuery){
  $searchq = $searchQuery;
  $query = "SELECT * FROM tbl_employees INNER JOIN tbl_department ON tbl_employees.departmentID = tbl_department.departmentID
  INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE employeeID = '$searchq'";
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
