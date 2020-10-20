<?php
include '../db/connection.php';

if (!empty($_POST['departmentID'])){
   $deptID = $_POST['departmentID'];

    // $posID = $_POST['positionID'];


    // select all data
    $query = "SELECT * FROM tbl_employees INNER JOIN tbl_department ON tbl_employees.departmentID = tbl_department.departmentID
    INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE tbl_employees.departmentID ='$deptID' ";
    // if(isset($deptID) && isset($postID)){
    //   $query .= " tbl_employees.departmentID ='$deptID' AND tbl_employees.positionID ='$posID' ";
    // }elseif (isset($deptID) && !isset($postID)) {
    //   $query .= " tbl_employees.departmentID ='$deptID'";
    // }elseif (!isset($deptID) && isset($posID)) {
    //   $query .= " tbl_employees.positionID ='$posID'";
    // }

    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "  <thead>
          <tr>
              <th>Employee Code</th>
              <th>Name</th>
              <th>Department</th>
              <th>Position</th>
          </tr>
      </thead>";
    if($num>0){

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $employeeCode = $row['employeeCode'];
        echo "        <tr>
                        <td>" . $row['employeeCode'] . "</td>
                        <td>" . $row["firstName"] . " " . $row['lastName'].  "</td>
                        <td>" . $row["departmentName"] . "</td>
                          <td>" . $row["positionName"] . "</td>
                      </tr>
                      <input type='hidden' name='deptID' value='$deptID'></input>";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       echo "no records found";
     }

}elseif(!empty($_POST['positionID'])){
  $posID = $_POST['positionID'];


  // select all data
  $query = "SELECT * FROM tbl_employees INNER JOIN tbl_department ON tbl_employees.departmentID = tbl_department.departmentID
  INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE tbl_employees.positionID ='$posID' ";
  // if(isset($deptID) && isset($postID)){
  //   $query .= " tbl_employees.departmentID ='$deptID' AND tbl_employees.positionID ='$posID' ";
  // }elseif (isset($deptID) && !isset($postID)) {
  //   $query .= " tbl_employees.departmentID ='$deptID'";
  // }elseif (!isset($deptID) && isset($posID)) {
  //   $query .= " tbl_employees.positionID ='$posID'";
  // }

  $stmt = $con->prepare($query);
  $stmt->execute();
  $num = $stmt->rowCount();
  echo "  <thead>
        <tr>
            <th>Employee Code</th>
            <th>Name</th>
            <th>Department</th>
            <th>Position</th>
        </tr>
    </thead>";
  if($num>0){

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $employeeCode = $row['employeeCode'];
      echo "        <tr>
                      <td>" . $row['employeeCode'] . "</td>
                      <td>" . $row["firstName"] . " " . $row['lastName'].  "</td>
                      <td>" . $row["departmentName"] . "</td>
                        <td>" . $row["positionName"] . "</td>
                    </tr>
                      <input type='hidden' name='posID' value='$posID'></input>''
                        <input type='hidden' name='deptID' value='$deptID'></input>";



   }
    // echo "  <tr><td> No Records Found </td></tr>";
   }else{
     echo "no records found";
   }
}




 ?>
